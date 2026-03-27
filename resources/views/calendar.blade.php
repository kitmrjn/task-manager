<x-app-layout>
    @section('title', 'Calendar')
<x-slot name="header">
    <div class="tk-topnav">

        <div class="tk-topnav-right">

            {{-- Notifications --}}
            <div class="tk-dropdown-wrap">
                <button class="tk-nav-icon-btn" id="notif-btn" title="Notifications">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                </button>
                <div id="notif-dropdown" class="tk-dropdown">
                    <div class="tk-dropdown-header">
                        <span class="tk-dropdown-title">Notifications</span>
                        <span class="tk-badge-pill" id="notif-count"></span>
                    </div>
                    <div class="tk-dropdown-body" id="notif-list">
                        <div style="padding:1.2rem;text-align:center;font-size:13.5px;color:var(--soft);">Loading…</div>
                    </div>
                </div>
            </div>

            <div class="tk-nav-divider"></div>

            {{-- Profile --}}
            <div class="tk-dropdown-wrap">
                <div class="tk-nav-profile" id="profile-btn" role="button" tabindex="0">
                    <div class="tk-nav-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <div class="tk-nav-userinfo">
                        <span class="tk-nav-name">{{ Auth::user()->name }}</span>
                        <span class="tk-nav-email">{{ Auth::user()->email }}</span>
                    </div>
                    <svg id="profile-chevron" class="tk-nav-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="transition:transform .22s;"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div id="profile-dropdown" class="tk-dropdown tk-profile-dropdown">
                    <div class="tk-dropdown-header tk-profile-header">
                        <div class="tk-profile-avatar-lg">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <div>
                            <div style="font-size:14px;font-weight:700;color:var(--text);">{{ Auth::user()->name }}</div>
                            <div class="tk-profile-meta">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <div class="tk-dropdown-body">
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('settings.index') }}" class="tk-profile-item">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            My Profile & Settings
                        </a>
                        @endif
                        <div class="tk-profile-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="tk-profile-item tk-profile-item--danger">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-slot>

@push('styles')
    @vite('resources/css/calendar.css')
@endpush

@push('scripts')
    <script>
        window.CAL_TASKS  = {!! json_encode($tasksByDate  ?? []) !!};
        window.CAL_EVENTS = {!! json_encode($eventsByDate ?? []) !!};
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/js/calendar.js')
@endpush

<div class="cal-page">

    <div class="cal-page-header">
        <h1 class="cal-page-title">Calendar</h1>
        <p class="cal-page-sub">Stay on top of your schedule and deadlines.</p>
    </div>

    <div class="cal-wrap">

        {{-- ── CALENDAR GRID ──────────────────────────────── --}}
        <div class="cal-card">
            <div class="cal-nav">
                <button class="cal-btn" onclick="calPrev()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
                <div class="cal-nav-controls">
                    <div class="cal-view-switcher">
                        <button class="view-btn active" data-view="month" onclick="changeView('month', this)">Month</button>
                        <button class="view-btn" data-view="day"   onclick="changeView('day',   this)">Day</button>
                        <button class="view-btn" data-view="list"  onclick="changeView('list',  this)">List</button>
                        <button class="view-btn" data-view="year"  onclick="changeView('year',  this)">Year</button>
                    </div>
                    <span class="cal-month" id="calMonthLabel"></span>
                    <button class="cal-today-btn" onclick="cur=new Date();renderCal()">Today</button>
                </div>
                <button class="cal-btn" onclick="calNext()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>

            <div class="cal-grid cal-dow-row" id="calDowRow">
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)
                    <div class="cal-dow">{{ $d }}</div>
                @endforeach
            </div>

            <div class="cal-grid" id="calCells"></div>
        </div>

        {{-- ── UPCOMING EVENTS SIDEBAR ────────────────────── --}}
        <div class="upcoming-card">
            <div class="card-header">
                <div class="card-title">Upcoming Events</div>
            </div>
            <div class="upcoming-list">
@php
    $upcomingAll = collect();

    foreach (($upcomingTasks ?? []) as $task) {
        $due  = \Carbon\Carbon::parse($task->due_date);
        $diff = now()->startOfDay()->diffInDays($due->copy()->startOfDay(), false);
        $isTaskOverdue = ($diff < 0 && !$task->is_completed);

        $upcomingAll->push([
            'title'    => $task->title,
            'diff'     => $diff,
            'dotColor' => $isTaskOverdue ? 'var(--red)' : ($diff <= 2 ? 'var(--amber)' : 'var(--blue)'),
            'subLabel' => $isTaskOverdue
                ? 'OVERDUE'
                : ($diff == 0 ? 'Today' : ($diff == 1 ? 'Tomorrow' : $due->format('M j'))),
        ]);
    }

    $dotMap = [
        'blue'   => 'var(--blue)',
        'green'  => 'var(--green)',
        'red'    => 'var(--red)',
        'amber'  => 'var(--amber)',
        'purple' => 'var(--purple)',
    ];
    $seenEventIds = [];

    foreach (($eventsByDate ?? []) as $dateStr => $evs) {
        foreach ($evs as $ev) {
            if (in_array($ev['id'], $seenEventIds)) continue;
            $due  = \Carbon\Carbon::parse($ev['original_date'] ?? $dateStr);
            $diff = now()->startOfDay()->diffInDays($due->copy()->startOfDay(), false);
            $isOngoing = !empty($ev['recurrence_until']) && \Carbon\Carbon::parse($ev['recurrence_until'])->startOfDay()->gte(now()->startOfDay());

            if ($diff >= -1 || $isOngoing) {
                $seenEventIds[] = $ev['id'];
                $dot     = $dotMap[$ev['color'] ?? 'blue'] ?? 'var(--blue)';
                $timeLbl = !empty($ev['time'])
                    ? \Carbon\Carbon::createFromFormat('H:i', substr($ev['time'], 0, 5))->format('g:i A')
                    : 'All Day';

                if (!empty($ev['recurrence_until'])) {
                    $endDate = \Carbon\Carbon::parse($ev['recurrence_until']);
                    $dateStrFormatted = $due->format('M j') . ' - ' . $endDate->format('M j');
                } else {
                    $dateStrFormatted = $due->format('M j');
                }

                $upcomingAll->push([
                    'title'    => $ev['title'],
                    'diff'     => $diff,
                    'dotColor' => $dot,
                    'subLabel' => $dateStrFormatted . ' · ' . $timeLbl,
                ]);
            }
        }
    }

    $upcomingAll = $upcomingAll->sortBy('diff')->values();
@endphp

                @forelse($upcomingAll as $item)
                    <div class="upcoming-item">
                        <div class="up-dot" style="background:{{ $item['dotColor'] }};"></div>
                        <div class="up-body">
                            <div class="up-title">{{ $item['title'] }}</div>
                            <div class="up-sub">{{ $item['subLabel'] }}</div>
                        </div>
                    </div>
                @empty
                    <div style="padding:2rem 1.4rem;text-align:center;color:var(--soft);font-size:14px;font-weight:500;">
                        No upcoming events 🎉
                    </div>
                @endforelse
            </div>
        </div>

    </div>{{-- /.cal-wrap --}}
</div>{{-- /.cal-page --}}

{{-- ================================================================
     ADD EVENT MODAL
================================================================ --}}
<div id="addEventModal" class="cal-modal-overlay">
<div class="cal-modal">
    <div class="cal-modal-head">
        <div class="cal-modal-title">Add Event</div>
        <button class="cal-modal-close" onclick="closeEventModal()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>

    <div class="cal-modal-body">

        {{-- Title --}}
        <div>
            <div class="cal-field-label">Title</div>
            <input type="text" id="ev-title" class="cal-field-input" placeholder="e.g. Team Meeting">
        </div>

        {{-- Row 1: Date + Time --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <div class="cal-field-label">Date</div>
                <input type="date" id="ev-date" class="cal-field-input" onchange="updateRecurrenceOptions()">
            </div>
            <div>
                <div class="cal-field-label">Time <span class="optional">(optional)</span></div>
                <input type="time" id="ev-time" class="cal-field-input">
            </div>
        </div>

        {{-- Row 2: Repeat + Until --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <div class="cal-field-label">Repeat</div>
                <select id="ev-recurrence" class="cal-field-input" onchange="toggleRecurrenceEnd()">
                    <option value="none">Does not repeat</option>
                    <option value="daily">Daily</option>
                    <option value="weekly" id="opt-weekly">Weekly on ...</option>
                    <option value="monthly" id="opt-monthly">Monthly on the ...</option>
                    <option value="yearly" id="opt-yearly">Annually on ...</option>
                    <option value="weekday">Every weekday (Mon–Fri)</option>
                </select>
            </div>
            <div id="recurrence-end-wrap" style="display:none;">
                <div class="cal-field-label">Until</div>
                <input type="date" id="ev-until" class="cal-field-input">
            </div>
        </div>

        {{-- Type --}}
        <div>
            <div class="cal-field-label">Type</div>
            <div class="cal-type-grid">
                <button type="button" id="ev-type-btn-meeting" class="cal-type-btn active" onclick="selectEventType('meeting')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Meeting
                </button>
                <button type="button" id="ev-type-btn-note" class="cal-type-btn" onclick="selectEventType('note')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Note
                </button>
                <button type="button" id="ev-type-btn-reminder" class="cal-type-btn" onclick="selectEventType('reminder')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Reminder
                </button>
            </div>
        </div>

        {{-- Color --}}
        <div>
            <div class="cal-field-label">Color</div>
            <div class="cal-color-row">
                @foreach(['blue'=>'#2d52c4','green'=>'#1a8a5a','red'=>'#c0354a','amber'=>'#c47c0e','purple'=>'#7c3aed'] as $k => $c)
                <div id="ev-color-{{ $k }}"
                     class="cal-color-dot {{ $k === 'blue' ? 'selected' : '' }}"
                     onclick="selectEventColor('{{ $k }}')"
                     style="background:{{ $c }};">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Description --}}
        <div>
            <div class="cal-field-label">Description <span class="optional">(optional)</span></div>
            <textarea id="ev-desc" class="cal-field-textarea" placeholder="Add notes…"></textarea>
        </div>

    </div>{{-- /.cal-modal-body --}}

    <div class="cal-modal-footer">
        <button class="cal-btn-ghost" onclick="closeEventModal()">Cancel</button>
        <button class="cal-btn-primary" onclick="saveEvent()">Save Event</button>
    </div>
</div>
</div>

{{-- ================================================================
     VIEW EVENT MODAL
================================================================ --}}
<div id="viewEventModal" class="cal-modal-overlay" style="z-index:600;">
<div class="cal-modal cal-modal-sm">
    <div class="cal-modal-head">
        <div class="cal-modal-title" id="view-ev-title">Event</div>
        <button class="cal-modal-close" onclick="closeViewModal()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="cal-modal-body" id="view-ev-body" style="gap:1.1rem;"></div>
    <div class="cal-modal-footer" style="justify-content:space-between;">
        <button id="view-ev-delete" class="cal-btn-danger" onclick="deleteEvent()" style="display:none;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
            Delete
        </button>
        <button class="cal-btn-primary" onclick="closeViewModal()" style="margin-left:auto;">Close</button>
    </div>
</div>
</div>

</x-app-layout>