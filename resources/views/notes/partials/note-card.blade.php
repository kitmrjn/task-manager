<div class="note-card"
     onclick="openNoteModal({{ $note->id }}, {{ json_encode($note->title) }}, {{ json_encode($note->body) }}, {{ json_encode($note->attachments->map(fn($a) => ['id'=>$a->id,'filename'=>$a->filename,'url'=>$a->url])) }})"
     style="
        background:#fff; border:1.5px solid {{ $note->is_pinned ? '#0f172a' : '#e2e8f0' }};
        border-radius:12px; cursor:pointer;
        transition:box-shadow .15s, transform .15s;
        min-height:140px; display:flex; flex-direction:column; justify-content:space-between;
        position:relative; overflow:hidden;"
     onmouseover="this.style.boxShadow='0 6px 20px rgba(15,23,42,.1)'; this.style.transform='translateY(-2px)'; this.querySelector('.note-actions').style.opacity='1';"
     onmouseout="this.style.boxShadow='none'; this.style.transform='none'; this.querySelector('.note-actions').style.opacity='0';">

    {{-- ── Hero image (Google Keep style, full-width at top) ───────────────── --}}
    @if($note->attachments->count())
        <div style="width:100%; overflow:hidden; flex-shrink:0;">
            <img src="{{ $note->attachments->first()->url }}"
                 alt="{{ $note->attachments->first()->filename }}"
                 style="width:100%; height:200px; object-fit:cover; display:block;">
        </div>
    @endif

    {{-- Hover action buttons --}}
    <div class="note-actions" style="
        position:absolute; top:.6rem; right:.6rem;
        display:flex; gap:.3rem;
        opacity:{{ $note->is_pinned ? '1' : '0' }};
        transition:opacity .15s;">

        {{-- Pin --}}
        <button onclick="togglePin({{ $note->id }}, event)"
                title="{{ $note->is_pinned ? 'Unpin' : 'Pin' }}"
                style="width:28px; height:28px; border-radius:50%; border:none; cursor:pointer;
                       display:flex; align-items:center; justify-content:center;
                       background:{{ $note->is_pinned ? '#0f172a' : 'rgba(255,255,255,.9)' }};
                       color:{{ $note->is_pinned ? '#fff' : '#64748b' }};
                       box-shadow:0 1px 4px rgba(0,0,0,.12); transition:background .15s, color .15s;">
            <svg width="13" height="13" viewBox="0 0 24 24"
                 fill="{{ $note->is_pinned ? 'currentColor' : 'none' }}"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="12" y1="17" x2="12" y2="22"/>
                <path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24V17z"/>
            </svg>
        </button>

        {{-- Archive --}}
        <button onclick="toggleArchive({{ $note->id }}, event)"
                title="{{ $note->is_archived ? 'Unarchive' : 'Archive' }}"
                style="width:28px; height:28px; border-radius:50%; border:none; cursor:pointer;
                       display:flex; align-items:center; justify-content:center;
                       background:rgba(255,255,255,.9); color:#64748b;
                       box-shadow:0 1px 4px rgba(0,0,0,.12); transition:background .15s;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <polyline points="21 8 21 21 3 21 3 8"/>
                <rect x="1" y="3" width="22" height="5"/>
                <line x1="10" y1="12" x2="14" y2="12"/>
            </svg>
        </button>
    </div>

    {{-- Note content --}}
    <div style="padding:1.25rem 1.25rem 0; padding-right:2rem;">
        @if($note->title)
            <div style="font-size:14px; font-weight:700; color:#0f172a; margin-bottom:.4rem;
                        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                {{ $note->title }}
            </div>
        @endif
        <div style="font-size:13px; color:#64748b; line-height:1.55;
                    display:-webkit-box; -webkit-line-clamp:3;
                    -webkit-box-orient:vertical; overflow:hidden;">
            {{ $note->body ?? 'Empty note' }}
        </div>
    </div>

    {{-- Extra images (2nd+) as small thumbnails --}}
    @if($note->attachments->count() > 1)
    <div style="margin:.85rem 1.25rem 0; display:flex; gap:.4rem; flex-wrap:wrap;">
        @foreach($note->attachments->skip(1)->take(3) as $i => $att)
            @if($i === 2 && $note->attachments->count() > 3)
                <div style="width:56px; height:56px; border-radius:7px; overflow:hidden; position:relative; flex-shrink:0;">
                    <img src="{{ $att->url }}" alt=""
                         style="width:100%; height:100%; object-fit:cover; display:block;">
                    <div style="position:absolute; inset:0; background:rgba(15,23,42,.6);
                                display:flex; align-items:center; justify-content:center;
                                font-size:12px; font-weight:700; color:#fff;">
                        +{{ $note->attachments->count() - 3 }}
                    </div>
                </div>
            @else
                <div style="width:56px; height:56px; border-radius:7px; overflow:hidden; flex-shrink:0;">
                    <img src="{{ $att->url }}" alt="{{ $att->filename }}"
                         style="width:100%; height:100%; object-fit:cover; display:block;">
                </div>
            @endif
        @endforeach
    </div>
    @endif

    {{-- Footer --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-top:.85rem; padding:0 1.25rem 1.25rem;">
        @if($note->attachments->count())
            <span style="font-size:11px; color:#64748b; display:flex; align-items:center; gap:.3rem;">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                {{ $note->attachments->count() }} image{{ $note->attachments->count() > 1 ? 's' : '' }}
            </span>
        @else
            <span></span>
        @endif
        <span style="font-size:11px; color:#64748b;">
            {{ $note->updated_at->diffForHumans() }}
        </span>
    </div>

</div>