/**
 * tasks.js  →  resources/js/tasks.js
 *
 * Handles:
 *   - Sortable drag-and-drop between columns
 *   - Card click: ripple + pop animation → open detail modal
 *   - Column CRUD: move, edit, delete, filter
 *   - Task detail modal: load, save, delete, toggle complete
 *   - Checklist: add, toggle, delete items
 *   - Comments: render, post, live poll every 5s
 *   - Activity history: preview + full modal
 *   - Start date toggle in detail form
 *   - Color swatch selector (no radio buttons)
 *   - Notifications dropdown
 *   - Search/filter cards
 */

// SortableJS must be loaded before this file (via CDN or npm)
// <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

document.addEventListener('DOMContentLoaded', () => {

    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    let currentTaskHistory     = [];
    let currentTaskId          = null;
    let commentPollingInterval = null;

    /* ================================================================
       SORTABLE — drag cards between columns
    ================================================================ */
    document.querySelectorAll('.sortable-column').forEach(col => {
        new Sortable(col, {
            group: 'shared',
            animation: 200,
            onEnd(evt) {
                fetch(`/tasks/${evt.item.dataset.taskId}/move`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ board_column_id: evt.to.dataset.columnId })
                }).then(() => updateCounts());
            }
        });
    });

    /* ================================================================
       CARD CLICK — ripple + pop animation, then open detail
    ================================================================ */
    window.handleCardClick = function(event, taskId) {
        const card = event.currentTarget;

        // Ripple at exact cursor position
        const rect   = card.getBoundingClientRect();
        const size   = Math.max(rect.width, rect.height);
        const ripple = document.createElement('span');
        ripple.className   = 'tk-card-ripple';
        ripple.style.cssText = `width:${size}px;height:${size}px;left:${event.clientX - rect.left - size / 2}px;top:${event.clientY - rect.top - size / 2}px;`;
        card.appendChild(ripple);
        ripple.addEventListener('animationend', () => ripple.remove());

        // Pop squeeze animation
        card.classList.add('card-pop');
        card.addEventListener('animationend', () => card.classList.remove('card-pop'), { once: true });

        // Slight delay so animation plays before modal opens
        setTimeout(() => openDetail(taskId), 120);
    };

    /* ================================================================
       COLOR SWATCH SELECTOR
       Works for both "add" and "edit" column modals.
       Replaces the old radio-button approach.
    ================================================================ */
    window.selectColor = function(context, key) {
        const container = document.getElementById(`${context}-swatches`);
        if (!container) return;
        container.querySelectorAll('.tk-color-swatch').forEach(s => s.classList.remove('selected'));
        const swatch = container.querySelector(`[data-color="${key}"]`);
        if (swatch) swatch.classList.add('selected');
        const hidden = document.getElementById(`${context}-color-value`);
        if (hidden) hidden.value = key;
    };

    /* ================================================================
       COLUMN HELPERS
    ================================================================ */
    window.toggleColMenu = function(event, colId) {
        event.stopPropagation();
        document.querySelectorAll('.tk-col-menu').forEach(m => {
            if (m.id !== `menu-${colId}`) m.classList.add('hidden');
        });
        document.getElementById(`menu-${colId}`).classList.toggle('hidden');
    };

    document.addEventListener('click', () => {
        document.querySelectorAll('.tk-col-menu').forEach(m => m.classList.add('hidden'));
    });

    window.moveColumn = function(colId, direction) {
        const el = document.getElementById(`col-wrapper-${colId}`);
        if (!el) return;
        const sibling = direction === 'left' ? el.previousElementSibling : el.nextElementSibling;
        if (sibling && sibling.classList.contains('tk-col')) {
            direction === 'left'
                ? el.parentNode.insertBefore(el, sibling)
                : el.parentNode.insertBefore(sibling, el);
        } else return;

        fetch(`/columns/${colId}/move`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ direction })
        }).then(r => r.json()).then(d => {
            if (!d.success) console.error('Column move failed');
        });
    };

    function updateCounts() {
        document.querySelectorAll('.sortable-column').forEach(col => {
            const wrap = col.closest('[id^=col-wrapper-]');
            if (wrap) wrap.querySelector('.column-count').textContent = col.querySelectorAll('.tk-card').length;
        });
    }

    window.openEditColumn = function(colId, title, color, desc) {
        const form = document.getElementById('editColumnForm');
        form.action = `/columns/${colId}`;
        document.getElementById('edit-col-title').value       = title || '';
        document.getElementById('edit-col-description').value = desc  || '';
        document.querySelectorAll('#edit-swatches .tk-color-swatch').forEach(s => s.classList.remove('selected'));
        selectColor('edit', color || 'gray');
        document.getElementById('editColumnModal').style.display = 'flex';
        document.getElementById(`menu-${colId}`).classList.add('hidden');
    };

    window.filterCards = function(colId, btn) {
        document.querySelectorAll('.tk-filter-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('[id^=col-wrapper-]').forEach(col => {
            col.style.display = (colId === 'all' || col.dataset.colId == colId) ? '' : 'none';
        });
    };

    window.openCreate = function(colId) {
        document.getElementById('create-col-id').value = colId;
        document.getElementById('createTaskModal').style.display = 'flex';
    };

    /* ================================================================
       DETAIL MODAL — open, close, save
    ================================================================ */
    window.openDetail = async function(taskId) {
        stopCommentPolling();
        currentTaskId = taskId;
        document.getElementById('detailModal').classList.add('open');

        try {
            const res  = await fetch(`/tasks/${taskId}/detail`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
            });
            const task = await res.json();

            currentTaskHistory = task.activities || [];
            renderHistoryPreview();

            document.getElementById('dt-title').textContent  = task.title;
            document.getElementById('dt-title-input').value  = task.title;
            document.getElementById('detailForm').action      = `/tasks/${taskId}`;
            document.getElementById('dt-desc').value          = task.description || '';
            document.getElementById('dt-duedate').value       = task.due_date    ? task.due_date.substr(0, 10) : '';
            document.getElementById('dt-priority').value      = task.priority    || 'medium';
            document.getElementById('dt-assignee').value      = task.assigned_to || '';
            document.getElementById('dt-status').value        = task.board_column_id || '';

            // Complete button state
            const completeBtn = document.getElementById('dt-complete-btn');
            if (task.is_completed) {
                completeBtn.innerHTML = `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Completed ✓`;
                completeBtn.style.cssText += 'background:var(--green);color:#fff;border-color:var(--green);';
            } else {
                completeBtn.innerHTML = `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Mark Complete`;
                completeBtn.style.cssText += 'background:var(--green-lt);color:var(--green);border-color:#bbf7d0;';
            }

            // Tags row
            document.getElementById('dt-tags').innerHTML = `
                ${task.priority ? `<span class="tk-priority ${task.priority}">${task.priority.toUpperCase()}</span>` : ''}
                ${task.column   ? `<span class="tk-card-tag" style="background:#eff6ff;color:#2563eb"><span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block"></span>${task.column.title}</span>` : ''}`;

            // Collaborators
            const activeIds = (task.members || task.collaborators || []).map(u => Number(u.id));
            document.querySelectorAll('.tk-collab-pill').forEach(pill => {
                pill.classList.toggle('active', activeIds.includes(parseInt(pill.dataset.userId)));
            });
            updateCollabInput();

            // Delete form
            document.getElementById('dt-delete-form').action = `/tasks/${taskId}`;
            document.getElementById('dt-delete-btn').onclick = () => {
                if (confirm('Delete this task?')) document.getElementById('dt-delete-form').submit();
            };

            // Start date
            if (task.start_date) {
                document.getElementById('dt-startdate').value             = task.start_date.substr(0, 10);
                document.getElementById('start-date-field').style.display = '';
                const btn = document.getElementById('start-date-toggle');
                btn.textContent = '− Remove'; btn.style.color = 'var(--red)'; btn.style.background = 'var(--red-lt)';
            } else {
                document.getElementById('dt-startdate').value             = '';
                document.getElementById('start-date-field').style.display = 'none';
                const btn = document.getElementById('start-date-toggle');
                btn.textContent = '+ Add'; btn.style.color = 'var(--blue)'; btn.style.background = 'var(--blue-lt)';
            }

            renderChecklist(task.checklist_items || []);
            renderComments(task.activities || []);
            startCommentPolling();

        } catch (err) { console.error('openDetail error:', err); }
    };

    window.closeDetail = function() {
        document.getElementById('detailModal').classList.remove('open');
        currentTaskId = null;
        stopCommentPolling();
    };

    window.saveDetail = function() {
        const form    = document.getElementById('detailForm');
        const saveBtn = document.querySelector('.tk-btn-save');
        const orig    = saveBtn.textContent;
        saveBtn.textContent = 'Saving…'; saveBtn.disabled = true;

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: new FormData(form)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                saveBtn.textContent = '✓ Saved!'; saveBtn.style.background = '#16a34a';
                setTimeout(() => window.location.reload(), 800);
            } else { throw new Error('Save failed'); }
        })
        .catch(() => {
            saveBtn.textContent = 'Error!'; saveBtn.style.background = '#dc2626';
            setTimeout(() => { saveBtn.textContent = orig; saveBtn.style.background = ''; saveBtn.disabled = false; }, 2000);
        });
    };

    /* ================================================================
       COLLABORATORS
    ================================================================ */
    window.toggleCollabSelection = function(el) { el.classList.toggle('active'); updateCollabInput(); };

    function updateCollabInput() {
        const selected = Array.from(document.querySelectorAll('.tk-collab-pill.active')).map(p => p.dataset.userId);
        document.getElementById('dt-collabs-input').value = JSON.stringify(selected);
    }

    /* ================================================================
       CHECKLIST
    ================================================================ */
    function renderChecklist(items) {
        const done  = items.filter(i => i.is_completed).length;
        const total = items.length;
        const pct   = total > 0 ? Math.round((done / total) * 100) : 0;

        document.getElementById('dt-check-badge').textContent          = `${done}/${total}`;
        document.getElementById('dt-prog-section').style.display       = total > 0 ? '' : 'none';

        if (total > 0) {
            document.getElementById('dt-pct').textContent       = pct + '%';
            document.getElementById('dt-prog-fill').style.width = pct + '%';
            document.getElementById('dt-prog-sub').textContent  = `${done} of ${total} checklist item${total > 1 ? 's' : ''} done`;
        }

        document.getElementById('dt-checklist').innerHTML = items.map(item => `
            <div class="tk-check-item" id="ci-${item.id}">
                <input type="checkbox" ${item.is_completed ? 'checked' : ''} onchange="toggleCheck(${item.id})">
                <span class="tk-check-text ${item.is_completed ? 'done' : ''}">${item.title}</span>
                <button class="tk-check-del" onclick="deleteCheck(${item.id})">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>`).join('') || '<div style="font-size:13.5px;color:var(--soft);padding:.4rem 0;font-weight:500;">No checklist items yet.</div>';
    }

    window.toggleCheck = function(id) {
        fetch(`/checklist-items/${id}/toggle`, { method: 'PATCH', headers: { 'X-CSRF-TOKEN': CSRF } })
            .then(() => openDetail(currentTaskId));
    };

    window.deleteCheck = function(id) {
        if (!confirm('Remove?')) return;
        fetch(`/checklist-items/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF } })
            .then(() => openDetail(currentTaskId));
    };

    window.addCheckItem = function() {
        const input = document.getElementById('checkInput');
        const title = input.value.trim();
        if (!title) return;
        fetch(`/tasks/${currentTaskId}/checklist`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ title })
        }).then(() => { input.value = ''; openDetail(currentTaskId); });
    };

    /* ================================================================
       COMMENTS
    ================================================================ */
    function renderComments(activities) {
        const container = document.getElementById('dt-comments');
        if (!container) return;

        const comments = (activities || []).filter(a => a.action === 'comment' || a.action === 'comment_added');
        document.getElementById('dt-comment-count').textContent = comments.length;

        if (comments.length === 0) {
            container.innerHTML = '<div style="font-size:14px;color:var(--soft);font-weight:500;padding:.5rem 0;">No comments yet.</div>';
            return;
        }

        container.innerHTML = comments.map(a => {
            const name     = a.user?.name || 'Someone';
            const initials = name.split(' ').map(n => n[0]).join('').toUpperCase().substr(0, 2);
            return `
                <div class="tk-comment">
                    <div class="tk-comment-av">${initials}</div>
                    <div class="tk-comment-body">
                        <div class="tk-comment-meta">
                            <span class="tk-comment-name">${name}</span>
                            <span class="tk-comment-time">${timeAgo(a.created_at)}</span>
                        </div>
                        <div class="tk-comment-text">${a.description || ''}</div>
                    </div>
                </div>`;
        }).join('');

        container.scrollTop = container.scrollHeight;
    }

    window.postComment = function() {
        const input = document.getElementById('commentInput');
        const text  = input.value.trim();
        if (!text || !currentTaskId) return;

        const btn = document.querySelector('.tk-comment-post');
        btn.disabled = true; btn.textContent = 'Posting…';

        fetch(`/tasks/${currentTaskId}/comments`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ comment: text })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { input.value = ''; btn.textContent = 'Post'; btn.disabled = false; openDetail(currentTaskId); }
        })
        .catch(() => { btn.textContent = 'Post'; btn.disabled = false; });
    };

    function startCommentPolling() {
        commentPollingInterval = setInterval(async () => {
            if (!currentTaskId) return;
            try {
                const res  = await fetch(`/tasks/${currentTaskId}/detail`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
                const task = await res.json();
                renderComments(task.activities || []);
            } catch (e) {}
        }, 5000);
    }

    function stopCommentPolling() {
        if (commentPollingInterval) { clearInterval(commentPollingInterval); commentPollingInterval = null; }
    }

    /* ================================================================
       ACTIVITY HISTORY
    ================================================================ */
    function renderHistoryPreview() {
        const container = document.getElementById('dt-history-preview');
        if (!container) return;
        const preview = currentTaskHistory.slice(0, 3);
        if (!preview.length) { container.innerHTML = '<div style="font-size:13.5px;color:var(--soft);">No activity yet.</div>'; return; }
        container.innerHTML = preview.map(item => `
            <div style="position:relative;padding-left:24px;margin-bottom:16px;">
                <div style="position:absolute;left:7px;top:0;bottom:-16px;width:2px;background:var(--border);"></div>
                <div style="position:absolute;left:4px;top:6px;width:8px;height:8px;border-radius:50%;background:#94a3b8;border:2px solid white;"></div>
                <div style="font-size:13.5px;"><span style="font-weight:700;color:var(--text);">${item.user?.name || 'Someone'}</span><span style="color:var(--muted);"> ${item.description}</span></div>
                <div style="font-size:12px;color:var(--soft);margin-top:2px;">${timeAgo(item.created_at)}</div>
            </div>`).join('');
    }

    window.openFullHistory = function() {
        document.getElementById('historyModal').style.display = 'flex';
        document.getElementById('full-history-content').innerHTML = currentTaskHistory.map(item => `
            <div style="margin-bottom:20px;padding-left:16px;border-left:2px solid var(--border);">
                <div style="font-size:13.5px;"><span style="font-weight:700;color:var(--text);">${item.user?.name || 'Someone'}</span><span style="color:var(--muted);"> ${item.description}</span></div>
                <div style="font-size:12px;color:var(--soft);margin-top:3px;">${new Date(item.created_at).toLocaleString()}</div>
            </div>`).join('');
    };

    /* ================================================================
       START DATE TOGGLE
    ================================================================ */
    window.toggleStartDate = function() {
        const field  = document.getElementById('start-date-field');
        const btn    = document.getElementById('start-date-toggle');
        const hidden = field.style.display === 'none';
        field.style.display  = hidden ? '' : 'none';
        btn.textContent      = hidden ? '− Remove' : '+ Add';
        btn.style.color      = hidden ? 'var(--red)'    : 'var(--blue)';
        btn.style.background = hidden ? 'var(--red-lt)' : 'var(--blue-lt)';
        if (!hidden) document.getElementById('dt-startdate').value = '';
    };

    window.clearStartDate = function() {
        document.getElementById('dt-startdate').value             = '';
        document.getElementById('start-date-field').style.display = 'none';
        const btn = document.getElementById('start-date-toggle');
        btn.textContent = '+ Add'; btn.style.color = 'var(--blue)'; btn.style.background = 'var(--blue-lt)';
    };

    /* ================================================================
       TOGGLE COMPLETE
    ================================================================ */
    window.toggleComplete = function() {
        if (!currentTaskId) return;
        fetch(`/tasks/${currentTaskId}/toggle-complete`, { method: 'PATCH', headers: { 'X-CSRF-TOKEN': CSRF } })
            .then(r => r.json())
            .then(data => { if (data.success) openDetail(currentTaskId); });
    };

    /* ================================================================
       NOTIFICATIONS
    ================================================================ */
    let notifOpen = false;
    const ICON_MAP = { comment: '💬', created: '✅', priority_change: '🔥', lead_change: '👤', column_change: '📋', completed: '🎉', checklist_added: '☑️' };

    window.toggleNotifications = async function() {
        const dropdown = document.getElementById('notif-dropdown');
        notifOpen = !notifOpen;
        dropdown.style.display = notifOpen ? 'block' : 'none';

        if (!notifOpen) return;

        try {
            const res  = await fetch('/notifications', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
            const data = await res.json();
            const list = document.getElementById('notif-list');
            document.getElementById('notif-count').textContent = data.length + ' new';

            if (!data.length) {
                list.innerHTML = '<div style="padding:1.5rem;text-align:center;font-size:14px;color:#6b7280;">You\'re all caught up! 🎉</div>';
                return;
            }

            list.innerHTML = data.map(n => `
                <div style="display:flex;gap:.75rem;padding:.9rem 1.1rem;border-bottom:1px solid #f0f2f6;cursor:pointer;"
                     onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                    <div style="width:38px;height:38px;border-radius:50%;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0;">${ICON_MAP[n.action] || '🔔'}</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13.5px;font-weight:500;color:#0d1117;line-height:1.4;">
                            <strong>${n.user || 'Someone'}</strong> ${n.description}
                            ${n.task ? `<span style="color:#6b7280;"> on <em>${n.task}</em></span>` : ''}
                        </div>
                        <div style="font-size:12px;color:#9ba3ae;margin-top:3px;">${n.time}</div>
                    </div>
                </div>`).join('');
        } catch (e) {
            document.getElementById('notif-list').innerHTML = '<div style="padding:1rem;text-align:center;font-size:13.5px;color:#dc2626;">Failed to load notifications.</div>';
        }
    };

    document.addEventListener('click', e => {
        if (!document.getElementById('notif-btn')?.contains(e.target)) {
            document.getElementById('notif-dropdown').style.display = 'none';
            notifOpen = false;
        }
    });

    /* ================================================================
       MODAL CLOSE BEHAVIOURS
    ================================================================ */
    document.getElementById('detailModal').addEventListener('click', e => {
        if (e.target === document.getElementById('detailModal')) closeDetail();
    });
    document.getElementById('createTaskModal').addEventListener('click', e => {
        if (e.target === document.getElementById('createTaskModal')) document.getElementById('createTaskModal').style.display = 'none';
    });
    document.getElementById('addColumnModal').addEventListener('click', e => {
        if (e.target === document.getElementById('addColumnModal')) document.getElementById('addColumnModal').style.display = 'none';
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeDetail();
            document.getElementById('createTaskModal').style.display = 'none';
            document.getElementById('addColumnModal').style.display  = 'none';
        }
    });

    /* ================================================================
       SEARCH / FILTER CARDS
    ================================================================ */
    document.querySelector('.tk-topnav-search input').addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();

        document.querySelectorAll('.tk-card').forEach(card => {
            if (!q) { card.style.display = ''; return; }
            const title = card.querySelector('.tk-card-title')?.textContent.toLowerCase() || '';
            const desc  = card.querySelector('.tk-card-desc')?.textContent.toLowerCase()  || '';
            card.style.display = (title.includes(q) || desc.includes(q)) ? '' : 'none';
        });

        updateCounts();

        document.querySelectorAll('.sortable-column').forEach(col => {
            const visible = col.querySelectorAll('.tk-card:not([style*="display: none"])').length;
            let noRes     = col.querySelector('.tk-no-results');
            if (q && visible === 0) {
                if (!noRes) {
                    noRes = document.createElement('div');
                    noRes.className     = 'tk-no-results';
                    noRes.style.cssText = 'font-size:13px;color:var(--soft);text-align:center;padding:.75rem;font-weight:500;';
                    noRes.textContent   = 'No tasks match';
                    col.appendChild(noRes);
                }
            } else if (noRes) { noRes.remove(); }
        });
    });

    /* ================================================================
       HELPERS
    ================================================================ */
    function timeAgo(ts) {
        if (!ts) return '';
        const d = Math.floor((Date.now() - new Date(ts)) / 1000);
        if (d < 60)    return d + 's ago';
        if (d < 3600)  return Math.floor(d / 60) + 'm ago';
        if (d < 86400) return Math.floor(d / 3600) + 'h ago';
        return Math.floor(d / 86400) + 'd ago';
    }

}); // end DOMContentLoaded

/* ============================================================
   TOPNAV DROPDOWNS — profile + notifications
============================================================ */
(function initTopnavDropdowns() {
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

    function bindDropdown(btnId, dropId, chevId = null) {
        const btn  = document.getElementById(btnId);
        const drop = document.getElementById(dropId);
        if (!btn || !drop) return;

        btn.addEventListener('click', e => {
            e.stopPropagation();
            const isOpen = drop.classList.contains('open');

            // close all
            document.querySelectorAll('.tk-dropdown').forEach(d => d.classList.remove('open'));
            document.querySelectorAll('.tk-nav-profile').forEach(u => u.classList.remove('active'));
            document.querySelectorAll('.tk-nav-chevron').forEach(c => c.style.transform = '');

            if (!isOpen) {
                drop.classList.add('open');
                if (chevId) {
                    const chev = document.getElementById(chevId);
                    if (chev) chev.style.transform = 'rotate(180deg)';
                }
                if (btnId === 'profile-btn') {
                    btn.closest('.tk-nav-profile')?.classList.add('active');
                }
                if (btnId === 'notif-btn') loadNotifications(drop);
            }
        });
    }

    // Close on outside click
    document.addEventListener('click', () => {
        document.querySelectorAll('.tk-dropdown').forEach(d => d.classList.remove('open'));
        document.querySelectorAll('.tk-nav-profile').forEach(u => u.classList.remove('active'));
        document.querySelectorAll('.tk-nav-chevron').forEach(c => c.style.transform = '');
    });

    // Bind both
    bindDropdown('notif-btn',   'notif-dropdown');
    bindDropdown('profile-btn', 'profile-dropdown', 'profile-chevron');

    async function loadNotifications(drop) {
        const list  = drop.querySelector('#notif-list');
        const count = drop.querySelector('#notif-count');
        if (!list) return;
        list.innerHTML = '<div style="padding:1.2rem;text-align:center;font-size:13px;color:var(--soft);">Loading…</div>';
        try {
            const res  = await fetch('/notifications', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
            const data = await res.json();
            if (count) count.textContent = data.length ? data.length + ' new' : '';
            if (!data.length) {
                list.innerHTML = `<div style="padding:1.5rem;text-align:center;font-size:13.5px;color:var(--soft);">You're all caught up! 🎉</div>`;
                return;
            }
            const iconMap = { comment:'💬', created:'✅', priority_change:'🔥', lead_change:'👤', column_change:'📋', completed:'🎉', checklist_added:'☑️' };
            list.innerHTML = data.map(n => `
                <div class="tk-notif-item">
                    <div class="tk-notif-icon">${iconMap[n.action] || '🔔'}</div>
                    <div class="tk-notif-content">
                        <div class="tk-notif-text"><strong>${n.user || 'Someone'}</strong> ${n.description}${n.task ? ` on <em>${n.task}</em>` : ''}</div>
                        <div class="tk-notif-time">${n.time}</div>
                    </div>
                </div>`).join('');
        } catch(e) {
            list.innerHTML = '<div style="padding:1rem;text-align:center;font-size:13px;color:var(--red);">Failed to load.</div>';
        }
    }
})();