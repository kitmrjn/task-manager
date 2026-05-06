@php
    $user     = Auth::user();
    $campaign = $user->campaign ?? null;
    $showPill = $campaign && $campaign->hasSchedule() && $user->role !== 'super_admin';
    $noSched  = $campaign && !$campaign->hasSchedule() && $user->role !== 'super_admin';
@endphp

@if($showPill || $noSched)
<div class="tk-shift-pill-wrap" id="shiftPillWrap">

    {{-- Trigger pill --}}
    <button class="tk-shift-pill" id="shiftPillBtn" onclick="toggleShiftPopover()" type="button">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        @if($showPill)
            <span class="tk-shift-pill-text">
                {{ $campaign->shift_label }}
                @if($campaign->timezone)
                    <span class="tk-shift-pill-tz">· {{ $campaign->timezone }}</span>
                @endif
                · <strong>{{ $campaign->name }}</strong>
            </span>
        @else
            <span class="tk-shift-pill-text tk-shift-pill-empty">No schedule set · <strong>{{ $campaign->name }}</strong></span>
        @endif
        <svg class="tk-shift-chevron" id="shiftChevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
    </button>

    {{-- Popover --}}
    <div class="tk-shift-popover" id="shiftPopover">
        <div class="tk-shift-pop-header">
            <div class="tk-shift-pop-title">{{ $campaign->name }}</div>
            <div class="tk-shift-pop-sub">Campaign Schedule</div>
        </div>

        @if($showPill)
        <div class="tk-shift-pop-body">
            {{-- Shift Hours --}}
            <div class="tk-shift-pop-row">
                <div class="tk-shift-pop-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    <div class="tk-shift-pop-label">Shift Hours</div>
                    <div class="tk-shift-pop-value">{{ $campaign->shift_label }}</div>
                </div>
            </div>

            {{-- Operating Days --}}
            <div class="tk-shift-pop-row">
                <div class="tk-shift-pop-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div style="flex:1;">
                    <div class="tk-shift-pop-label">Operating Days</div>
                    <div class="tk-shift-pop-days">
                        @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                            <span class="tk-shift-day {{ in_array($day, $campaign->operating_days ?? []) ? 'active' : '' }}">
                                {{ $day }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="tk-shift-pop-body">
            <div style="text-align:center; padding:.75rem 0; color:#94a3b8; font-size:13px; font-weight:500;">
                No schedule has been set for this campaign yet.
            </div>
        </div>
        @endif
    </div>
</div>
@endif

@pushOnce('styles')
<style>
/* Paste ALL your .tk-shift-pill CSS classes here from the dashboard page */
.tk-shift-pill-wrap { position: relative; }
.tk-shift-pill { display: flex; align-items: center; gap: .55rem; background: #f1f5f9; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: .45rem 1rem; cursor: pointer; font-family: inherit; transition: background .15s, border-color .15s, box-shadow .15s; white-space: nowrap; }
.tk-shift-pill:hover { background: #e8edf5; border-color: #cbd5e1; box-shadow: 0 2px 8px rgba(27,43,94,.08); }
.tk-shift-pill svg { color: #64748b; flex-shrink: 0; }
.tk-shift-pill-text { font-size: 13px; font-weight: 600; color: #334155; }
.tk-shift-pill-text strong { color: #1b2b5e; font-weight: 700; }
.tk-shift-pill-tz { color: #94a3b8; font-weight: 500; }
.tk-shift-pill-empty { color: #94a3b8; font-style: italic; }
.tk-shift-pill-empty strong { color: #64748b; }
.tk-shift-chevron { color: #94a3b8; flex-shrink: 0; transition: transform .22s cubic-bezier(.16,1,.3,1); }
.tk-shift-chevron.open { transform: rotate(180deg); }
.tk-shift-popover { position: absolute; left: 0; top: calc(100% + 10px); background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; box-shadow: 0 10px 40px rgba(27,43,94,.14); width: 300px; z-index: 999; overflow: hidden; opacity: 0; transform: translateY(-8px) scale(.97); pointer-events: none; transition: opacity .2s cubic-bezier(.16,1,.3,1), transform .2s cubic-bezier(.16,1,.3,1); transform-origin: top left; }
.tk-shift-popover.open { opacity: 1; transform: translateY(0) scale(1); pointer-events: auto; }
.tk-shift-pop-header { padding: 1rem 1.25rem .85rem; border-bottom: 1px solid #f1f5f9; background: linear-gradient(135deg, #1b2b5e, #2d52c4); }
.tk-shift-pop-title { font-size: 14px; font-weight: 800; color: #fff; }
.tk-shift-pop-sub { font-size: 11px; font-weight: 600; color: rgba(255,255,255,.6); margin-top: 2px; text-transform: uppercase; letter-spacing: .08em; }
.tk-shift-pop-body { padding: .85rem 1.25rem; display: flex; flex-direction: column; gap: .85rem; }
.tk-shift-pop-row { display: flex; align-items: flex-start; gap: .75rem; }
.tk-shift-pop-icon { width: 30px; height: 30px; border-radius: 8px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #64748b; }
.tk-shift-pop-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; margin-bottom: 2px; }
.tk-shift-pop-value { font-size: 13.5px; font-weight: 700; color: #1b2b5e; }
.tk-shift-pop-days { display: flex; flex-wrap: wrap; gap: .3rem; margin-top: .25rem; }
.tk-shift-day { font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 5px; background: #f1f5f9; color: #94a3b8; border: 1px solid #e2e8f0; }
.tk-shift-day.active { background: #1b2b5e; color: #fff; border-color: #1b2b5e; }
</style>
@endPushOnce

@pushOnce('scripts')
<script>
// Paste your toggleShiftPopover JS function here
function toggleShiftPopover() {
    const pop  = document.getElementById('shiftPopover');
    const chev = document.getElementById('shiftChevron');
    const isOpen = pop.classList.contains('open');

    // Close other dropdowns first
    document.querySelectorAll('.tk-dropdown').forEach(d => d.classList.remove('open'));

    pop.classList.toggle('open', !isOpen);
    chev.classList.toggle('open', !isOpen);
}

// Close popover on outside click
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('shiftPillWrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('shiftPopover')?.classList.remove('open');
        document.getElementById('shiftChevron')?.classList.remove('open');
    }
});
</script>
@endPushOnce