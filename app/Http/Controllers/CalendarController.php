<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $tasksByDate = [];
        $eventsByDate = [];

        // 1. Fetch Tasks
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

        // 2. Fetch Upcoming tasks for sidebar
        $upcomingTasks = Task::with(['assignee'])
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now()->startOfDay())
            ->where('due_date', '<=', now()->addDays(30))
            ->orderBy('due_date')
            ->take(10)
            ->get();

        // 3. Fetch Calendar Events (with FIXED Monthly Recurrence Logic)
        $rawEvents = CalendarEvent::with('user')->get();
        
        foreach ($rawEvents as $event) {
            // Add the initial event date
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
            ];

            // Calculate recurring dates if applicable
            if ($event->recurrence && $event->recurrence !== 'none' && $event->recurrence_until) {
                $startDate = Carbon::parse($event->date);
                $untilDate = Carbon::parse($event->recurrence_until);
                
                $tempDate = $startDate->copy();
                $limit = 0; 

                while ($limit < 365) {
                    if ($event->recurrence === 'daily') {
                        $tempDate->addDay();
                    } elseif ($event->recurrence === 'weekly') {
                        $tempDate->addWeek();
                    } elseif ($event->recurrence === 'monthly') {
                        $tempDate->addMonth();
                    } elseif ($event->recurrence === 'yearly') {
                        $tempDate->addYear();
                    }

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
            'user_id'          => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteEvent(Request $request)
    {
        $request->validate(['id' => 'required']);
        $event = CalendarEvent::find($request->id);
        
        if ($event) {
            $event->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Event not found'], 404);
    }

 /**
     * PH holidays from Nager.Date — ALL return type "Public".
     * We classify them by name since the API doesn't distinguish
     * Regular vs Special Non-Working vs Special Working.
     *
     * holidayType values used by the frontend:
     *   'ph-regular'         → coral/rose
     *   'ph-special-nonwork' → deep orange
     *   'ph-special-work'    → slate teal
     */
    public function getHolidays($year)
    {
        // ── Known Special Non-Working holidays (by English name keywords) ──
        $specialNonWorking = [
            'chinese new year',
            'black saturday',
            'all saints',
            'all souls',          // Nov 2 sometimes declared
            'last day of the year',
            'feast of the immaculate conception',
            'additional special',
            'special non-working',
            'people power',
            'ninoy aquino',       // Aug 21 - was formerly regular, now special non-working
        ];

        // ── Known Special Working holidays (rare, usually proclaimed ad-hoc) ──
        $specialWorking = [
            'special working',
            'special public holiday', // sometimes used
        ];

        try {
            $url = "https://date.nager.at/api/v3/PublicHolidays/{$year}/PH";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

            $response = curl_exec($ch);
            $error    = curl_error($ch);
            curl_close($ch);

            if ($error || !$response) {
                \Log::error('PH Holiday cURL error: ' . $error);
                return response()->json([]);
            }

            $holidays = json_decode($response, true) ?? [];

            foreach ($holidays as &$holiday) {
                $nameLower = strtolower($holiday['name'] ?? '');
                $localLower = strtolower($holiday['localName'] ?? '');
                $combined  = $nameLower . ' ' . $localLower;

                // Check special working first (least common)
                $isSpecialWork = false;
                foreach ($specialWorking as $keyword) {
                    if (str_contains($combined, $keyword)) {
                        $isSpecialWork = true;
                        break;
                    }
                }

                // Check special non-working
                $isSpecialNonWork = false;
                if (!$isSpecialWork) {
                    foreach ($specialNonWorking as $keyword) {
                        if (str_contains($combined, $keyword)) {
                            $isSpecialNonWork = true;
                            break;
                        }
                    }
                }

                if ($isSpecialWork) {
                    $holiday['holidayType'] = 'ph-special-work';
                } elseif ($isSpecialNonWork) {
                    $holiday['holidayType'] = 'ph-special-nonwork';
                } else {
                    // Default: Regular Holiday (New Year, Labor Day, Independence Day, etc.)
                    $holiday['holidayType'] = 'ph-regular';
                }

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
     * US holidays — all tagged as 'us-holiday' (neon yellow).
     */
    public function getUSHolidays($year)
    {
        try {
            $url = "https://date.nager.at/api/v3/PublicHolidays/{$year}/US";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

            $response = curl_exec($ch);
            $error    = curl_error($ch);
            curl_close($ch);

            if ($error || !$response) {
                \Log::error('US Holiday cURL error: ' . $error);
                return response()->json([]);
            }

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