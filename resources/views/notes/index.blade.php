<x-app-layout>
    @section('title', 'Notes')

    <x-slot name="header">
        <div class="tk-topnav">
            <x-shift-pill />
            <div class="tk-topnav-right">

                {{-- Notifications --}}
                <div class="tk-dropdown-wrap">
                    <button class="tk-nav-icon-btn" id="notif-btn" title="Notifications">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </button>
                    <div id="notif-dropdown" class="tk-dropdown" style="width:340px;">
                        <div class="tk-dropdown-header">
                            <span class="tk-dropdown-title">Notifications</span>
                            <span id="notif-count" class="tk-badge-pill"></span>
                        </div>
                        <div class="tk-dropdown-body" id="notif-list">
                            <div style="padding:1.2rem;text-align:center;font-size:13.5px;color:var(--soft);">Loading…</div>
                        </div>
                    </div>
                </div>

                <div class="tk-nav-divider"></div>

                {{-- User profile --}}
                <div class="tk-dropdown-wrap" style="flex-shrink:0;">
                    <div class="tk-nav-profile" id="profile-btn">
                        <div class="tk-nav-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <div class="tk-nav-userinfo">
                            <span class="tk-nav-name">{{ Auth::user()->name }}</span>
                            <span class="tk-nav-email">{{ Auth::user()->email }}</span>
                        </div>
                        <svg id="profile-chevron" class="tk-nav-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </div>
                    <div id="profile-dropdown" class="tk-dropdown tk-profile-dropdown">
                        <div class="tk-dropdown-header">
                            <div class="tk-profile-avatar-lg">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-size:14px;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->name }}</div>
                                <div class="tk-profile-meta" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div style="padding:.3rem 0;">
                            <a href="{{ route('settings.index') }}" class="tk-profile-item">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                My Profile & Settings
                            </a>
                            <div class="tk-profile-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="tk-profile-item tk-profile-item--danger">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </x-slot>

    @push('styles') @vite(['resources/css/dashboard.css', 'resources/css/tasks.css']) @endpush

    <div style="font-family:'Epilogue',sans-serif; padding:2.5rem; max-width:1200px; margin:0 auto;">

        {{-- Page heading --}}
        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:2rem;">
            <div>
                <h1 style="font-size:26px; font-weight:800; color:#0f172a; margin:0 0 .25rem; font-family:'Geist',sans-serif;">Notes</h1>
                <p style="font-size:13.5px; color:#94a3b8; margin:0;">Create and manage your personal notes · Pin important ones to the top</p>
            </div>
            <button onclick="toggleArchiveView()" id="archiveToggleBtn" style="
                background:#fff; color:#64748b; border:1.5px solid #e2e8f0;
                border-radius:8px; padding:.45rem 1rem; font-family:inherit;
                font-size:13px; font-weight:600; cursor:pointer;
                display:inline-flex; align-items:center; gap:.4rem; flex-shrink:0; margin-top:.25rem;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                <span id="archiveToggleLabel">Show Archived</span>
            </button>
        </div>

        {{-- New Note Button --}}
        <div style="margin-bottom:2rem;">
            <button onclick="openNoteModal()" style="
                background:#0f172a; color:#fff; border:none; border-radius:10px;
                padding:.65rem 1.5rem; font-family:inherit; font-size:13.5px;
                font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:.5rem;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Note
            </button>
        </div>

        {{-- ── ACTIVE NOTES VIEW ── --}}
        <div id="activeNotesView">

            @if($pinned->count())
            <div style="margin-bottom:2rem;">
                <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#94a3b8; margin-bottom:.85rem;">
                    📌 Pinned
                </div>
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(240px, 1fr)); gap:1.25rem;">
                    @foreach($pinned as $note)
                        @include('notes.partials.note-card', ['note' => $note])
                    @endforeach
                </div>
            </div>
            @endif

            @if($others->count())
            <div>
                @if($pinned->count())
                <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#94a3b8; margin-bottom:.85rem;">
                    Others
                </div>
                @endif
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(240px, 1fr)); gap:1.25rem;">
                    @foreach($others as $note)
                        @include('notes.partials.note-card', ['note' => $note])
                    @endforeach
                </div>
            </div>
            @endif

            @if($pinned->isEmpty() && $others->isEmpty())
            <div style="text-align:center; padding:4rem 0; color:#94a3b8;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 1rem; display:block; opacity:.4;">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                <div style="font-size:15px; font-weight:600;">No notes yet</div>
                <div style="font-size:13px; margin-top:.3rem;">Click "New Note" to create your first one.</div>
            </div>
            @endif
        </div>

        {{-- ── ARCHIVED NOTES VIEW ── --}}
        <div id="archivedNotesView" style="display:none;">
            <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#94a3b8; margin-bottom:.85rem;">
                🗃️ Archived
            </div>
            @if($archived->count())
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(240px, 1fr)); gap:1.25rem;">
                @foreach($archived as $note)
                    @include('notes.partials.note-card', ['note' => $note])
                @endforeach
            </div>
            @else
            <div style="text-align:center; padding:4rem 0; color:#94a3b8;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 1rem; display:block; opacity:.4;">
                    <polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/>
                </svg>
                <div style="font-size:15px; font-weight:600;">No archived notes</div>
            </div>
            @endif
        </div>

    </div>

    {{-- ── NOTE MODAL ── --}}
    <div id="noteModal" style="
        display:none; position:fixed; inset:0; z-index:1000;
        background:rgba(15,23,42,.45); backdrop-filter:blur(3px);
        align-items:center; justify-content:center;">

        <div style="
            background:#fff; border-radius:14px; width:100%; max-width:560px;
            margin:1rem; box-shadow:0 20px 60px rgba(0,0,0,.18);
            font-family:'Epilogue',sans-serif; display:flex; flex-direction:column;
            max-height:90vh; overflow:hidden;">

            {{-- Hero image (Google Keep style, full-width at top) --}}
            <div id="modalHeroWrap" style="display:none; position:relative; width:100%; flex-shrink:0;">
                <img id="modalHeroImg" src="" alt=""
                     onclick="openLightbox(this.src, event)"
                     style="width:100%; max-height:260px; object-fit:cover; display:block; border-radius:14px 14px 0 0; cursor:zoom-in;">
                <button onclick="removeHeroAttachment(event)" title="Remove image"
                        style="position:absolute; bottom:10px; right:10px; width:34px; height:34px;
                               background:rgba(15,23,42,.55); border:none; border-radius:50%;
                               color:#fff; cursor:pointer; display:flex; align-items:center;
                               justify-content:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                    </svg>
                </button>
            </div>

            {{-- Title --}}
            <div style="padding:1.25rem 1.5rem .5rem;">
                <input id="noteTitle" type="text" placeholder="Title"
                       style="width:100%; border:none; outline:none; font-family:inherit;
                              font-size:17px; font-weight:700; color:#0f172a;">
            </div>

            <div style="height:1px; background:#f1f5f9; margin:0 1.5rem;"></div>

            {{-- Body --}}
            <textarea id="noteBody" placeholder="Take a note…"
                      style="border:none; outline:none; resize:none; font-family:inherit;
                             font-size:14px; color:#334155; line-height:1.7;
                             padding:1rem 1.5rem; min-height:160px; max-height:300px;"></textarea>

            {{-- Extra attachments (2nd+) preview area --}}
            <div id="attachmentPreviewArea" style="padding:0 1.5rem; display:flex; gap:.5rem; flex-wrap:wrap; min-height:0;"></div>

            {{-- Upload progress --}}
            <div id="uploadProgress" style="display:none; padding:.5rem 1.5rem;">
                <div style="height:3px; background:#e2e8f0; border-radius:2px; overflow:hidden;">
                    <div id="uploadProgressBar" style="height:100%; background:#0f172a; width:0%; transition:width .3s;"></div>
                </div>
                <div style="font-size:11px; color:#94a3b8; margin-top:.3rem;" id="uploadProgressLabel">Uploading…</div>
            </div>

            {{-- Footer --}}
            <div style="padding:.85rem 1.5rem; display:flex; align-items:center; justify-content:space-between; border-top:1px solid #f1f5f9; margin-top:auto;">

                <div style="display:flex; align-items:center; gap:.5rem;">
                    {{-- Delete note --}}
                    <button id="noteDeleteBtn" onclick="deleteNote()" style="
                        display:none; background:none; border:none; cursor:pointer;
                        color:#ef4444; font-family:inherit; font-size:13px; font-weight:600;
                        align-items:center; gap:.35rem; padding:0;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                        Delete
                    </button>
                    <span id="noteDeletePlaceholder"></span>

                    {{-- Attach image button --}}
                    <label id="attachBtn" title="Attach image" style="
                        display:none; width:32px; height:32px; border-radius:50%;
                        background:#f1f5f9; border:none; cursor:pointer;
                        align-items:center; justify-content:center; color:#64748b;
                        transition:background .15s; margin-left:.25rem;">
                        <input type="file" id="attachFileInput" accept="image/jpeg,image/png,image/gif"
                               style="display:none;" onchange="handleFileSelect(event)">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </label>
                </div>

                <div style="display:flex; gap:.6rem;">
                    <button onclick="closeNoteModal()" style="
                        background:#f1f5f9; color:#64748b; border:none; border-radius:8px;
                        padding:.5rem 1.1rem; font-family:inherit; font-size:13px;
                        font-weight:600; cursor:pointer;">Close</button>
                    <button onclick="saveNote()" style="
                        background:#0f172a; color:#fff; border:none; border-radius:8px;
                        padding:.5rem 1.25rem; font-family:inherit; font-size:13px;
                        font-weight:600; cursor:pointer;">Save</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── IMAGE LIGHTBOX ── --}}
    <div id="lightbox" style="
        display:none; position:fixed; inset:0; z-index:2000;
        background:rgba(0,0,0,.85); align-items:center; justify-content:center;"
         onclick="closeLightbox()">
        <img id="lightboxImg" src="" alt="" style="max-width:90vw; max-height:90vh; border-radius:8px; box-shadow:0 20px 60px rgba(0,0,0,.5);">
        <button onclick="closeLightbox()" style="position:absolute; top:1.5rem; right:1.5rem; background:rgba(255,255,255,.15); border:none; border-radius:50%; width:38px; height:38px; color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>

    <script>
    const NOTE_CSRF = document.querySelector('meta[name="csrf-token"]').content;
    let _noteId       = null;
    let _attachments  = [];   // [{id, filename, url, size}]
    let _showingArchive = false;

    // ── Archive toggle ──
    function toggleArchiveView() {
        _showingArchive = !_showingArchive;
        document.getElementById('activeNotesView').style.display   = _showingArchive ? 'none'  : 'block';
        document.getElementById('archivedNotesView').style.display = _showingArchive ? 'block' : 'none';
        document.getElementById('archiveToggleLabel').textContent  = _showingArchive ? 'Show Notes' : 'Show Archived';
    }

    // ── Pin ──
    async function togglePin(noteId, event) {
        event.stopPropagation();
        const res = await fetch(`/notes/${noteId}/pin`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': NOTE_CSRF, 'Accept': 'application/json' },
        });
        if (res.ok) window.location.reload();
    }

    // ── Archive ──
    async function toggleArchive(noteId, event) {
        event.stopPropagation();
        const res = await fetch(`/notes/${noteId}/archive`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': NOTE_CSRF, 'Accept': 'application/json' },
        });
        if (res.ok) window.location.reload();
    }

    // ── Open modal ──
    function openNoteModal(id = null, title = '', body = '', attachments = []) {
        _noteId      = id;
        _attachments = attachments ? [...attachments] : [];

        document.getElementById('noteTitle').value = title || '';
        document.getElementById('noteBody').value  = body  || '';

        const deleteBtn     = document.getElementById('noteDeleteBtn');
        const attachBtn     = document.getElementById('attachBtn');
        const placeholder   = document.getElementById('noteDeletePlaceholder');

        if (id) {
            deleteBtn.style.display   = 'flex';
            attachBtn.style.display   = 'flex';
            placeholder.style.display = 'none';
        } else {
            deleteBtn.style.display   = 'none';
            attachBtn.style.display   = 'none';
            placeholder.style.display = 'inline';
        }

        renderHeroImage();
        renderAttachmentPreviews();
        document.getElementById('noteModal').style.display = 'flex';
        setTimeout(() => document.getElementById('noteTitle').focus(), 50);
    }

    // ── Show/hide hero image ──
    function renderHeroImage() {
        const heroWrap = document.getElementById('modalHeroWrap');
        const heroImg  = document.getElementById('modalHeroImg');
        const modalBox = heroWrap.closest('[style*="border-radius:14px"]');

        if (_attachments.length > 0) {
            heroImg.src = _attachments[0].url;
            heroWrap.style.display = 'block';
            // Remove top border-radius so image fills to the top edge
            modalBox.style.borderRadius = '14px';
        } else {
            heroWrap.style.display = 'none';
            heroImg.src = '';
        }
    }

    // ── Delete hero (first) attachment ──
    async function removeHeroAttachment(event) {
        event.stopPropagation();
        if (!_attachments.length) return;
        if (!confirm('Remove this image?')) return;

        const att = _attachments[0];
        const res = await fetch(`/notes/${_noteId}/attachments/${att.id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': NOTE_CSRF, 'Accept': 'application/json' },
        });

        if (res.ok) {
            _attachments = _attachments.filter(a => a.id !== att.id);
            renderHeroImage();
            renderAttachmentPreviews();
        }
    }

    // ── Render extra attachment thumbnails (2nd+) inside modal ──
    function renderAttachmentPreviews() {
        const area = document.getElementById('attachmentPreviewArea');
        const extras = _attachments.slice(1); // first is shown as hero
        if (!extras.length) { area.innerHTML = ''; area.style.paddingBottom = '0'; return; }

        area.style.paddingBottom = '.75rem';
        area.innerHTML = extras.map(att => `
            <div style="position:relative; width:80px; height:80px; border-radius:8px; overflow:hidden;
                        flex-shrink:0; border:1.5px solid #e2e8f0; background:#f8fafc;">
                <img src="${att.url}"
                     alt="${att.filename}"
                     onclick="openLightbox('${att.url}', event)"
                     style="width:100%; height:100%; object-fit:cover; display:block; cursor:zoom-in;">
                <button onclick="removeAttachment(${att.id}, event)"
                        style="position:absolute; top:3px; right:3px; width:20px; height:20px;
                               background:rgba(15,23,42,.65); border:none; border-radius:50%;
                               color:#fff; cursor:pointer; display:flex; align-items:center;
                               justify-content:center; padding:0; line-height:0;">
                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>
        `).join('');
    }

    // ── Handle file select ──
    async function handleFileSelect(event) {
        const file = event.target.files[0];
        if (!file || !_noteId) return;

        const maxSize = 20 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('File exceeds 20MB limit.');
            return;
        }

        const allowed = ['image/jpeg', 'image/png', 'image/gif'];
        if (!allowed.includes(file.type)) {
            alert('Only JPG, PNG, and GIF images are allowed.');
            return;
        }

        // Show progress
        const progressWrap = document.getElementById('uploadProgress');
        const progressBar  = document.getElementById('uploadProgressBar');
        const progressLbl  = document.getElementById('uploadProgressLabel');
        progressWrap.style.display = 'block';
        progressBar.style.width    = '0%';
        progressLbl.textContent    = `Uploading ${file.name}…`;

        const formData = new FormData();
        formData.append('file', file);

        try {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', `/notes/${_noteId}/attachments`);
            xhr.setRequestHeader('X-CSRF-TOKEN', NOTE_CSRF);
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.onprogress = (e) => {
                if (e.lengthComputable) {
                    progressBar.style.width = Math.round((e.loaded / e.total) * 100) + '%';
                }
            };

            xhr.onload = () => {
                progressWrap.style.display = 'none';
                event.target.value = '';

                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    _attachments.push(data.attachment);
                    renderHeroImage();
                    renderAttachmentPreviews();
                } else {
                    alert('Upload failed. Please try again.');
                }
            };

            xhr.onerror = () => {
                progressWrap.style.display = 'none';
                alert('Upload failed. Please try again.');
            };

            xhr.send(formData);

        } catch (e) {
            progressWrap.style.display = 'none';
            alert('Upload failed.');
        }
    }

    // ── Remove attachment (2nd+) ──
    async function removeAttachment(attachmentId, event) {
        event.stopPropagation();
        if (!confirm('Remove this image?')) return;

        const res = await fetch(`/notes/${_noteId}/attachments/${attachmentId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': NOTE_CSRF, 'Accept': 'application/json' },
        });

        if (res.ok) {
            _attachments = _attachments.filter(a => a.id !== attachmentId);
            renderHeroImage();
            renderAttachmentPreviews();
        }
    }

    // ── Lightbox ──
    function openLightbox(url, event) {
        event.stopPropagation();
        document.getElementById('lightboxImg').src = url;
        document.getElementById('lightbox').style.display = 'flex';
    }

    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
    }

    // ── Close modal ──
    function closeNoteModal() {
        document.getElementById('noteModal').style.display = 'none';
        document.getElementById('modalHeroWrap').style.display = 'none';
        document.getElementById('modalHeroImg').src = '';
        _noteId = null;
        _attachments = [];
        document.getElementById('attachmentPreviewArea').innerHTML = '';
    }

    // ── Save note ──
    async function saveNote() {
        const title = document.getElementById('noteTitle').value.trim();
        const body  = document.getElementById('noteBody').value.trim();

        if (!title && !body) { closeNoteModal(); return; }

        const url    = _noteId ? `/notes/${_noteId}` : '/notes';
        const method = _noteId ? 'PUT' : 'POST';

        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': NOTE_CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ title, body }),
        });

        if (res.ok) {
            closeNoteModal();
            window.location.reload();
        }
    }

    // ── Delete note ──
    async function deleteNote() {
        if (!_noteId || !confirm('Delete this note?')) return;

        const res = await fetch(`/notes/${_noteId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': NOTE_CSRF, 'Accept': 'application/json' },
        });

        if (res.ok) { closeNoteModal(); window.location.reload(); }
    }

    document.getElementById('noteModal').addEventListener('click', function(e) {
        if (e.target === this) closeNoteModal();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeLightbox();
            closeNoteModal();
        }
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            saveNote();
        }
    });
    </script>

    @push('scripts') @vite('resources/js/tasks.js') @endpush

</x-app-layout>