{{-- ================================================================
     TASK DETAIL MODAL
================================================================ --}}
<div class="tk-modal-overlay" id="detailModal">
<div class="tk-detail">

    <div class="tk-detail-head">
        <div class="tk-detail-head-top">
            <div class="tk-detail-title" id="dt-title">Loading…</div>
            <button class="tk-detail-close" onclick="closeDetail()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="tk-detail-tags" id="dt-tags"></div>
    </div>

    {{-- Two-column body --}}
    <div style="display:flex;flex:1;overflow:hidden;min-height:0;">

        {{-- Left panel --}}
        <div style="flex:1;overflow-y:auto;padding:1.4rem 1.6rem;display:flex;flex-direction:column;gap:1.3rem;border-right:1px solid var(--border);">

            <form id="detailForm" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="title" id="dt-title-input">

                <div class="tk-fields" style="margin-bottom:.2rem">
                <div style="display:none;">
                    <div class="tk-field-label">Column</div>
                    <select name="board_column_id" class="tk-field-select" id="dt-status">
                        @foreach($board->columns ?? [] as $col)
                            <option value="{{ $col->id }}">{{ $col->title }}</option>
                        @endforeach
                    </select>
                </div>
                                    <div>
                        <div class="tk-field-label">Assignee</div>
                        <select name="assigned_to" class="tk-field-select" id="dt-assignee">
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <div class="tk-field-label">Priority</div>
                        <select name="priority" class="tk-field-select" id="dt-priority">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <div>
                        <div class="tk-field-label" style="display:flex;align-items:center;justify-content:space-between;">
                            Start Date
                            <button type="button" id="start-date-toggle" onclick="toggleStartDate()"
                                    style="font-size:10.5px;font-weight:700;color:var(--blue);background:var(--blue-lt);border:none;border-radius:5px;padding:2px 8px;cursor:pointer;">
                                + Add
                            </button>
                        </div>
                        <div id="start-date-field" style="display:none;margin-top:.4rem;">
                            <div style="display:flex;align-items:center;gap:.4rem;">
                                <input type="date" name="start_date" class="tk-field-input" id="dt-startdate" style="flex:1;">
                                <button type="button" onclick="clearStartDate()"
                                        style="width:30px;height:30px;border-radius:6px;border:1.5px solid var(--border);background:var(--white);color:var(--soft);cursor:pointer;font-size:15px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    ×
                                </button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="tk-field-label">Due Date</div>
                        <input type="date" name="due_date" class="tk-field-input" id="dt-duedate">
                    </div>
                </div>

                <div style="margin-top:.8rem">
                    <div class="tk-field-label" style="margin-bottom:.5rem;">Description</div>
                    <textarea name="description" class="tk-field-textarea" id="dt-desc" placeholder="Add a description…"></textarea>
                </div>

<div style="margin-top:1rem;">
    <div class="tk-field-label" style="margin-bottom:.65rem;">Collaborators</div>

    {{-- 1. SELECTED PILLS (Moved to the top) --}}
    <div class="tk-selected-collabs" id="dt-selected-collabs" style="margin-bottom: 0.65rem; display: flex; flex-wrap: wrap; gap: 6px;"></div>

    {{-- 2. SEARCH BAR (Now below the selected members) --}}
    <div class="tk-collab-search-wrap" id="collabSearchWrap">
        <div class="tk-collab-search-input-row">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="color:#9ca3af;flex-shrink:0;">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" id="collabSearch" class="tk-collab-search-input" placeholder="Search or add members…" autocomplete="off" onkeydown="handleCollabEnter(event)">
        </div>

        <div id="collabDropdown" class="tk-collab-dropdown" style="display:none;">
            @foreach($users as $user)
            <div class="tk-collab-option"
                 data-user-id="{{ $user->id }}"
                 data-user-name="{{ strtolower($user->name) }}"
                 data-user-display="{{ $user->name }}">
                <div class="tk-collab-option-av">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <span>{{ $user->name }}</span>
                <svg class="tk-collab-check" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Hidden input remains at the bottom --}}
    <input type="hidden" name="collaborators" id="dt-collabs-input">
</div>
            </form>

            {{-- Progress --}}
            <div id="dt-prog-section" style="display:none">
                <div class="tk-section-head">
                    <div class="tk-section-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        Progress
                    </div>
                    <span style="font-size:12.5px;color:var(--soft);font-weight:600;">auto-tracked from checklist</span>
                </div>
                <div class="tk-progress-block">
                    <div class="tk-progress-pct" id="dt-pct">0%</div>
                    <div class="tk-progress-right">
                        <div class="tk-progress-track"><div class="tk-progress-fill" id="dt-prog-fill" style="width:0%"></div></div>
                        <div class="tk-progress-sub" id="dt-prog-sub">0 of 0 checklist items done</div>
                    </div>
                </div>
            </div>

            {{-- Checklist --}}
            <div>
                <div class="tk-section-head">
                    <div class="tk-section-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Checklist
                    </div>
                    <span class="tk-section-badge" id="dt-check-badge">0/0</span>
                </div>
                <div class="tk-checklist" id="dt-checklist"></div>
                <div class="tk-check-add" style="margin-top:.5rem">
                    <input type="text" id="checkInput" placeholder="Add checklist item… (Enter to add)"
                        onkeydown="if(event.key==='Enter'){event.preventDefault();addCheckItem();}">
                    <button onclick="addCheckItem()">Add</button>
                </div>
            </div>

            {{-- Attachments --}}
            <div>
                <div class="tk-section-head">
                    <div class="tk-section-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
                        Attachments
                    </div>
                    <label class="tk-attach-btn" for="attachInput">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add
                        <input type="file" id="attachInput" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip" style="display:none;" onchange="uploadAttachments(this)">
                    </label>
                </div>
                <div class="tk-dropzone" id="attachDropzone"
                     ondragover="event.preventDefault();this.classList.add('drag-over')"
                     ondragleave="this.classList.remove('drag-over')"
                     ondrop="handleAttachDrop(event)">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:#9ca3af;"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
                    <span>Drop files here or <label for="attachInput" style="color:var(--blue);cursor:pointer;font-weight:700;">browse</label></span>
                </div>
                <div id="attachProgress" style="display:none;margin-top:.6rem;">
                    <div style="height:4px;background:var(--border);border-radius:99px;overflow:hidden;">
                        <div id="attachProgressBar" style="height:100%;background:var(--blue);border-radius:99px;width:0%;transition:width .3s;"></div>
                    </div>
                    <div style="font-size:12px;color:var(--soft);margin-top:.3rem;font-weight:600;">Uploading…</div>
                </div>
                <div id="dt-attachments" style="margin-top:.5rem;display:flex;flex-direction:column;gap:.5rem;"></div>
            </div>

            {{-- Recent Activity --}}
            <div style="border-top:1px solid var(--border);padding-top:1rem;">
                <div class="tk-section-head">
                    <div class="tk-section-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Recent Activity
                    </div>
                    <button type="button" onclick="openFullHistory()"
                        style="font-size:13px;color:var(--blue);background:none;border:none;cursor:pointer;font-weight:700;">
                        View All →
                    </button>
                </div>
                <div id="dt-history-preview"></div>
            </div>

        </div>{{-- /.left panel --}}

        {{-- Right panel — Comments --}}
        <div style="width:330px;flex-shrink:0;display:flex;flex-direction:column;overflow:hidden;">
            <div style="padding:1rem 1.2rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
                <div style="display:flex;align-items:center;gap:.5rem;font-size:15px;font-weight:700;color:var(--text);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                    Comments
                </div>
                <span class="tk-section-badge" id="dt-comment-count">0</span>
            </div>
            <div id="dt-comments" style="flex:1;overflow-y:auto;padding:.9rem 1.2rem;display:flex;flex-direction:column;gap:.8rem;"></div>
            <div style="padding:.8rem 1.2rem;border-top:1px solid var(--border);flex-shrink:0;background:var(--white);">
                <div style="display:flex;gap:.5rem;align-items:flex-start;">
                    <div class="tk-comment-input-av" style="width:32px;height:32px;font-size:12px;flex-shrink:0;margin-top:2px;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div style="flex:1;display:flex;flex-direction:column;gap:.45rem;">
                        <textarea class="tk-comment-input" id="commentInput" rows="2"
                            placeholder="Write a comment…"
                            onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();postComment();}"></textarea>
                        <button class="tk-comment-post" onclick="postComment()" style="align-self:flex-end;">Post</button>
                    </div>
                </div>
            </div>
        </div>{{-- /.right panel --}}

    </div>{{-- /.two-column body --}}

    {{-- Footer --}}
    <div class="tk-detail-footer">
        @if(auth()->user()->can_access('can_delete_tasks'))
        <button class="tk-btn-delete" id="dt-delete-btn">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
            Delete
        </button>
        @endif
        <div style="display:flex;gap:.55rem;align-items:center;">
            <button id="dt-complete-btn" onclick="toggleComplete()"
                    style="display:flex;align-items:center;gap:.4rem;padding:.55rem 1.1rem;border-radius:8px;
                           font-size:13.5px;font-weight:700;cursor:pointer;font-family:'Geist',sans-serif;
                           transition:all .15s;border:1.5px solid #bbf7d0;background:var(--green-lt);color:var(--green);">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                Mark Complete
            </button>
            <button class="tk-btn-cancel" onclick="closeDetail()">Cancel</button>
            <button class="tk-btn-save" onclick="saveDetail()">Save Changes</button>
        </div>
    </div>
    <form id="dt-delete-form" method="POST" style="display:none;">@csrf @method('DELETE')</form>

</div>{{-- /.tk-detail --}}
</div>{{-- /.tk-modal-overlay #detailModal --}}