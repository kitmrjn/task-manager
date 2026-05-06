<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $user        = auth()->user();
        $tasksByDate  = [];
        $eventsByDate = [];

        // 1. Fetch Tasks (unchanged)
        $allTasks = Task::with(['assignee', 'column'])->whereNotNull('due_date')->get();

        foreach ($allTasks as $task) {
            $dateKey = Carbon::parse($task->due_date)->format('Y-m-d');
            $tasksByDate[$dateKey][] = [
                'id'           => $task->id,
                'title'        => $task->title,
                'priority'     => $task->priority,
                'assignee'     => $task->assignee?->name,
                'column'       => $task->column?->title,
                'is_completed' => $task->is_completed,
                'color'        => match($task->priority) {
                    'high'   => 'red',
                    'medium' => 'amber',
                    'low'    => 'green',
                    default  => 'blue',
                },
            ];
        }

        // 2. Fetch Upcoming tasks for sidebar (unchanged)
        $upcomingTasks = Task::with(['assignee'])
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now()->startOfDay())
            ->where('due_date', '<=', now()->addDays(30))
            ->orderBy('due_date')
            ->take(10)
            ->get();

        // 3. Fetch Calendar Events filtered by sub-calendar visibility
        $rawEvents = CalendarEvent::with('user')
            ->where(function ($q) use ($user) {
                // Personal: only your own
                $q->where(function ($q2) use ($user) {
                    $q2->where('calendar_type', 'personal')
                       ->where('user_id', $user->id);
                })
                // Team: same campaign_id
                ->orWhere(function ($q2) use ($user) {
                    $q2->where('calendar_type', 'team');
                    if ($user->campaign_id) {
                        $q2->whereHas('user', fn($q3) => $q3->where('campaign_id', $user->campaign_id));
                    } else {
                        // If user has no campaign, only see their own team events
                        $q2->where('user_id', $user->id);
                    }
                })
                // General: everyone sees these
                ->orWhere('calendar_type', 'general');
            })
            ->get();

        foreach ($rawEvents as $event) {
            $eventsByDate[$event->date][] = [
                'id'               => $event->id,
                'title'            => $event->title,
                'color'            => $event->color,
                'type'             => $event->type,
                'time'             => $event->time,
                'description'      => $event->description,
                'creator'          => $event->user?->name ?? 'System',
                'date'             => $event->date,
                'original_date'    => $event->date,
                'recurrence_until' => $event->recurrence_until,
                'calendar_type'    => $event->calendar_type,
                'is_mine'          => $event->user_id === $user->id,
            ];

            if ($event->recurrence && $event->recurrence !== 'none' && $event->recurrence_until) {
                $startDate = Carbon::parse($event->date);
                $untilDate = Carbon::parse($event->recurrence_until);
                $tempDate  = $startDate->copy();
                $limit     = 0;

                while ($limit < 365) {
                    if ($event->recurrence === 'daily')        $tempDate->addDay();
                    elseif ($event->recurrence === 'weekly')   $tempDate->addWeek();
                    elseif ($event->recurrence === 'monthly')  $tempDate->addMonth();
                    elseif ($event->recurrence === 'yearly')   $tempDate->addYear();

                    if ($tempDate->gt($untilDate)) break;

                    $dateKey = $tempDate->format('Y-m-d');
                    $eventsByDate[$dateKey][] = [
                        'id'               => $event->id,
                        'title'            => $event->title,
                        'color'            => $event->color,
                        'type'             => $event->type,
                        'time'             => $event->time,
                        'description'      => $event->description,
                        'creator'          => $event->user?->name ?? 'System',
                        'date'             => $dateKey,
                        'original_date'    => $event->date,
                        'recurrence_until' => $event->recurrence_until,
                        'calendar_type'    => $event->calendar_type,
                        'is_mine'          => $event->user_id === $user->id,
                    ];
                    $limit++;
                }
            }
        }

        if ($request->ajax()) {
            return view('calendar', compact('upcomingTasks', 'tasksByDate', 'eventsByDate'));
        }

        return view('calendar', compact('upcomingTasks', 'tasksByDate', 'eventsByDate'));
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'date'             => 'required|date',
            'color'            => 'nullable|string',
            'type'             => 'nullable|string',
            'time'             => 'nullable|string',
            'description'      => 'nullable|string',
            'recurrence'       => 'nullable|string|in:none,daily,weekly,monthly,yearly',
            'recurrence_until' => 'nullable|date|after_or_equal:date',
            'calendar_type'    => 'nullable|string|in:personal,team,general',
        ]);

        CalendarEvent::create([
            'title'            => $request->title,
            'date'             => $request->date,
            'color'            => $request->color ?? 'blue',
            'type'             => $request->type ?? 'meeting',
            'time'             => $request->time,
            'description'      => $request->description,
            'recurrence'       => $request->recurrence ?? 'none',
            'recurrence_until' => $request->recurrence_until,
            'calendar_type'    => $request->calendar_type ?? 'personal',
            'user_id'          => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteEvent(Request $request)
    {
        $request->validate(['id' => 'required']);
        $event = CalendarEvent::find($request->id);

        if ($event) {
            // Only the creator can delete their event
            if ($event->user_id !== auth()->id() && !auth()->user()->isSuperAdmin()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            $event->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Event not found'], 404);
    }

    /**
     * PH holidays from Nager.Date
     */
    public function getHolidays($year)
    {
        $specialNonWorking = [
            'chinese new year','black saturday','all saints','all souls',
            'last day of the year','feast of the immaculate conception',
            'additional special','special non-working','people power','ninoy aquino',
        ];

        $specialWorking = ['special working','special public holiday'];

        try {
            $url = "https://date.nager.at/api/v3/PublicHolidays/{$year}/PH";
            $ch  = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 10,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER     => ['Accept: application/json'],
                CURLOPT_USERAGENT      => 'Mozilla/5.0',
            ]);
            $response = curl_exec($ch);
            $error    = curl_error($ch);
            curl_close($ch);

            if ($error || !$response) return response()->json([]);

            $holidays = json_decode($response, true) ?? [];

            foreach ($holidays as &$holiday) {
                $combined = strtolower(($holiday['name'] ?? '') . ' ' . ($holiday['localName'] ?? ''));

                $isSpecialWork = false;
                foreach ($specialWorking as $k) {
                    if (str_contains($combined, $k)) { $isSpecialWork = true; break; }
                }

                $isSpecialNonWork = false;
                if (!$isSpecialWork) {
                    foreach ($specialNonWorking as $k) {
                        if (str_contains($combined, $k)) { $isSpecialNonWork = true; break; }
                    }
                }

                $holiday['holidayType'] = $isSpecialWork ? 'ph-special-work'
                    : ($isSpecialNonWork ? 'ph-special-nonwork' : 'ph-regular');
                $holiday['country'] = 'PH';
            }
            unset($holiday);

            return response()->json($holidays);
        } catch (\Exception $e) {
            \Log::error('PH Holiday fetch exception: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * US holidays
     */
    public function getUSHolidays($year)
    {
        try {
            $url = "https://date.nager.at/api/v3/PublicHolidays/{$year}/US";
            $ch  = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 10,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER     => ['Accept: application/json'],
                CURLOPT_USERAGENT      => 'Mozilla/5.0',
            ]);
            $response = curl_exec($ch);
            $error    = curl_error($ch);
            curl_close($ch);

            if ($error || !$response) return response()->json([]);

            $holidays = json_decode($response, true) ?? [];
            foreach ($holidays as &$holiday) {
                $holiday['holidayType'] = 'us-holiday';
                $holiday['country']     = 'US';
            }
            unset($holiday);

            return response()->json($holidays);
        } catch (\Exception $e) {
            \Log::error('US Holiday fetch exception: ' . $e->getMessage());
            return response()->json([]);
        }
    }
}