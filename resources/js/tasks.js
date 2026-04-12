/**
 * tasks.js  →  resources/js/tasks.js
 */

document.addEventListener('DOMContentLoaded', () => {

    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    let currentTaskHistory     = [];
    let currentTaskId          = null;
    let commentPollingInterval = null;
    let currentCanEdit         = true;

    /* ================================================================
       SORTABLE
    ================================================================ */
    function initSortable() {
        document.querySelectorAll('.sortable-column').forEach(col => {
            const existing = Sortable.get(col);
            if (existing) existing.destroy();
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
    }

    function startBoardSync() {
        setInterval(async () => {
            if (document.querySelector('.sortable-ghost') ||
                document.getElementById('detailModal').classList.contains('open')) return;
            try {
                const res      = await fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html     = await res.text();
                const parser   = new DOMParser();
                const newDoc   = parser.parseFromString(html, 'text/html');
                const newBoard = newDoc.getElementById('boardContainer');
                const curBoard = document.getElementById('boardContainer');
                if (newBoard && curBoard && newBoard.innerHTML.trim() !== curBoard.innerHTML.trim()) {
                    curBoard.innerHTML = newBoard.innerHTML;
                    initSortable();
                    updateCounts();
                }
            } catch (e) { console.error('Sync failed:', e); }
        }, 5000);
    }

    if (document.getElementById('boardContainer')) {
    initSortable();
    startBoardSync();
    }

    /* ================================================================
       CARD CLICK
    ================================================================ */
    window.handleCardClick = function(event, taskId) {
        const card   = event.currentTarget;
        const rect   = card.getBoundingClientRect();
        const size   = Math.max(rect.width, rect.height);
        const ripple = document.createElement('span');
        ripple.className     = 'tk-card-ripple';
        ripple.style.cssText = `width:${size}px;height:${size}px;left:${event.clientX - rect.left - size/2}px;top:${event.clientY - rect.top - size/2}px;`;
        card.appendChild(ripple);
        ripple.addEventListener('animationend', () => ripple.remove());
        card.classList.add('card-pop');
        card.addEventListener('animationend', () => card.classList.remove('card-pop'), { once: true });
        setTimeout(() => openDetail(taskId), 120);
    };

    /* ================================================================
       COLOR SWATCH
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
            direction === 'left' ? el.parentNode.insertBefore(el, sibling) : el.parentNode.insertBefore(sibling, el);
        } else return;
        fetch(`/columns/${colId}/move`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ direction })
        }).then(r => r.json()).then(d => { if (!d.success) console.error('Column move failed'); });
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

    window.confirmDeleteColumn = function(columnId, columnTitle) {
        Swal.fire({
            title: `Delete "${columnTitle}"?`,
            text: "All tasks inside this column will also be deleted! This cannot be undone.",
            icon: 'warning',
            width: '400px',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Yes, delete column',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            borderRadius: '16px',
            background: '#ffffff',
            customClass: {
                popup: 'tk-rounded-modal',
                confirmButton: 'tk-swal-btn',
                cancelButton: 'tk-swal-btn'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById(`del-col-${columnId}`);
                if (form) form.submit();
            }
        });
    };

    /* ================================================================
       COLLABORATOR SEARCH & SELECT
    ================================================================ */
    (function initCollabSearch() {
        const searchInput = document.getElementById('collabSearch');
        const dropdown    = document.getElementById('collabDropdown');
        const wrap        = document.getElementById('collabSearchWrap');
        if (!searchInput || !dropdown || !wrap) return;

        searchInput.addEventListener('focus', e => { e.stopPropagation(); openCollabDropdown(); });
        searchInput.addEventListener('click', e => { e.stopPropagation(); openCollabDropdown(); });
        searchInput.addEventListener('input', () => {
            const q = searchInput.value.toLowerCase().trim();
            dropdown.querySelectorAll('.tk-collab-option').forEach(opt => {
                opt.style.display = (!q || opt.dataset.userName.includes(q)) ? '' : 'none';
            });
            openCollabDropdown();
        });
        wrap.addEventListener('click', e => e.stopPropagation());
        dropdown.querySelectorAll('.tk-collab-option').forEach(opt => {
            opt.addEventListener('click', e => {
                e.stopPropagation();
                toggleCollab(opt.dataset.userId, opt.dataset.userDisplay, opt);
                searchInput.value = '';
                dropdown.querySelectorAll('.tk-collab-option').forEach(o => o.style.display = '');
                searchInput.focus();
                document.getElementById('collabSearch').value = '';
                closeCollabDropdown();
            });
        });
        document.addEventListener('click', () => closeCollabDropdown());
    })();

    function openCollabDropdown() {
        const d = document.getElementById('collabDropdown');
        if (d) d.style.display = 'block';
    }
    function closeCollabDropdown() {
        const d = document.getElementById('collabDropdown');
        if (d) d.style.display = 'none';
    }

    function toggleCollab(userId, userName, optionEl) {
        const container = document.getElementById('dt-selected-collabs');
        const existing  = container.querySelector(`[data-user-id="${userId}"]`);
        if (existing) {
            existing.remove();
            if (optionEl) optionEl.classList.remove('selected');
        } else {
            const pill = document.createElement('div');
            pill.className      = 'tk-collab-pill';
            pill.dataset.userId = userId;
            pill.innerHTML = `
                <div class="tk-avatar-mini">${userName.charAt(0).toUpperCase()}</div>
                <span>${userName}</span>
                <span class="tk-remove-x">×</span>`;
            pill.addEventListener('click', e => {
                e.stopPropagation();
                pill.remove();
                const opt = document.querySelector(`#collabDropdown [data-user-id="${userId}"]`);
                if (opt) opt.classList.remove('selected');
                updateCollabInput();
            });
            container.appendChild(pill);
            if (optionEl) optionEl.classList.add('selected');
        }
        updateCollabInput();
    }

    window.selectCollab = function(userId, userName) {
        const container = document.getElementById('dt-selected-collabs');
        if (!container) return;
        if (container.querySelector(`[data-user-id="${userId}"]`)) return;
        const opt = document.querySelector(`#collabDropdown [data-user-id="${userId}"]`);
        toggleCollab(String(userId), userName, opt);
    };

    function updateCollabInput() {
        const selected = Array.from(document.querySelectorAll('#dt-selected-collabs .tk-collab-pill')).map(p => p.dataset.userId);
        const input = document.getElementById('dt-collabs-input');
        if (input) input.value = JSON.stringify(selected);
    }

    window.handleCollabEnter = function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const dropdown    = document.getElementById('collabDropdown');
            const searchInput = document.getElementById('collabSearch');
            const firstVisibleOption = Array.from(dropdown.querySelectorAll('.tk-collab-option'))
                .find(opt => opt.style.display !== 'none');
            if (firstVisibleOption) {
                const userId   = firstVisibleOption.dataset.userId;
                const userName = firstVisibleOption.dataset.userDisplay;
                window.selectCollab(userId, userName);
                searchInput.value = '';
                closeCollabDropdown();
                dropdown.querySelectorAll('.tk-collab-option').forEach(o => o.style.display = '');
            }
        }
    };

    /* ================================================================
       ATTACHMENTS
    ================================================================ */
    function renderAttachments(attachments) {
        const container = document.getElementById('dt-attachments');
        if (!container) return;

        if (!attachments || attachments.length === 0) {
            container.innerHTML = '';
            return;
        }

        container.innerHTML = attachments.map(a => {
            const icon = getFileIcon(a.mime_type);
            return `<div class="tk-attach-item" id="attach-${a.id}">
                ${a.is_image
                    ? `<div class="tk-attach-thumb" style="background-image:url('${a.url}')" onclick="openLightbox('${a.url}','${a.original_name}')"></div>`
                    : `<div class="tk-attach-icon">${icon}</div>`
                }
                <div class="tk-attach-info">
                    <div class="tk-attach-name" title="${a.original_name}">${a.original_name}</div>
                    <div class="tk-attach-meta">${a.size} · ${a.uploader || 'You'}</div>
                </div>
                <div class="tk-attach-actions">
                    <a href="${a.url}" download="${a.original_name}" class="tk-attach-action-btn" title="Download">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    </a>
                    <button class="tk-attach-action-btn tk-attach-del" onclick="deleteAttachment(${a.id})" title="Delete">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                    </button>
                </div>
            </div>`;
        }).join('');
    }

    window.uploadAttachments = async function(input) {
        if (!input.files.length || !currentTaskId) return;
        const formData = new FormData();
        Array.from(input.files).forEach(f => formData.append('files[]', f));
        const progressWrap = document.getElementById('attachProgress');
        const progressBar  = document.getElementById('attachProgressBar');
        progressWrap.style.display = '';
        progressBar.style.width    = '30%';
        try {
            const res  = await fetch(`/tasks/${currentTaskId}/attachments`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF },
                body: formData,
            });
            progressBar.style.width = '90%';
            const data = await res.json();
            progressBar.style.width = '100%';
            setTimeout(() => { progressWrap.style.display = 'none'; progressBar.style.width = '0%'; }, 500);
            if (data.success) openDetail(currentTaskId);
        } catch (e) {
            console.error('Upload failed:', e);
            progressWrap.style.display = 'none';
        }
        input.value = '';
    };

    window.handleAttachDrop = function(event) {
        event.preventDefault();
        document.getElementById('attachDropzone').classList.remove('drag-over');
        const dt = event.dataTransfer;
        if (!dt.files.length || !currentTaskId) return;
        const formData = new FormData();
        Array.from(dt.files).forEach(f => formData.append('files[]', f));
        const progressWrap = document.getElementById('attachProgress');
        const progressBar  = document.getElementById('attachProgressBar');
        progressWrap.style.display = '';
        progressBar.style.width    = '40%';
        fetch(`/tasks/${currentTaskId}/attachments`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF },
            body: formData,
        })
        .then(r => r.json())
        .then(data => {
            progressBar.style.width = '100%';
            setTimeout(() => { progressWrap.style.display = 'none'; progressBar.style.width = '0%'; }, 500);
            if (data.success) openDetail(currentTaskId);
        })
        .catch(() => { progressWrap.style.display = 'none'; });
    };

    /* ================================================================
       DELETE ATTACHMENT
    ================================================================ */
    window.deleteAttachment = function(attachmentId) {
        Swal.fire({
            title: 'Remove attachment?',
            text: "This file will be permanently deleted.",
            icon: 'warning',
            width: '380px',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Yes, remove it',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            borderRadius: '16px',
            background: '#ffffff',
            customClass: {
                popup: 'tk-rounded-modal',
                confirmButton: 'tk-swal-btn',
                cancelButton: 'tk-swal-btn'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/attachments/${attachmentId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF },
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        openDetail(currentTaskId);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Attachment removed',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true
                        });
                    }
                });
            }
        });
    };

    window.openLightbox = function(url, name) {
        let lb = document.getElementById('tk-lightbox');
        if (!lb) {
            lb = document.createElement('div');
            lb.id = 'tk-lightbox';
            lb.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.88);z-index:9999;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.75rem;cursor:zoom-out;';
            lb.innerHTML = `
                <div style="display:flex;align-items:center;justify-content:space-between;width:100%;max-width:900px;padding:0 1.5rem;">
                    <span id="lb-name" style="font-size:14px;font-weight:600;color:rgba(255,255,255,.7);"></span>
                    <button onclick="document.getElementById('tk-lightbox').remove()" style="background:rgba(255,255,255,.15);border:none;border-radius:8px;width:36px;height:36px;color:#fff;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;">×</button>
                </div>
                <img id="lb-img" style="max-width:90vw;max-height:82vh;border-radius:10px;box-shadow:0 20px 60px rgba(0,0,0,.5);" src="">`;
            lb.addEventListener('click', e => { if (e.target === lb) lb.remove(); });
            document.body.appendChild(lb);
        }
        document.getElementById('lb-img').src          = url;
        document.getElementById('lb-name').textContent = name;
        lb.style.display = 'flex';
    };

    function getFileIcon(mime) {
        if (mime.includes('pdf'))                               return '📄';
        if (mime.includes('word') || mime.includes('document')) return '📝';
        if (mime.includes('sheet') || mime.includes('excel'))   return '📊';
        if (mime.includes('zip')  || mime.includes('rar'))      return '🗜️';
        if (mime.includes('text'))                              return '📃';
        return '📎';
    }

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

            document.getElementById('dt-title').textContent = task.title;
            document.getElementById('dt-title-input').value = task.title;
            document.getElementById('detailForm').action    = `/tasks/${taskId}`;
            document.getElementById('dt-desc').value        = task.description || '';
            document.getElementById('dt-duedate').value     = task.due_date    ? task.due_date.substr(0,10) : '';
            document.getElementById('dt-priority').value    = task.priority    || 'medium';
            document.getElementById('dt-assignee').value    = task.assigned_to ? String(task.assigned_to) : '';
            document.getElementById('dt-status').value      = task.board_column_id || '';

            const completeBtn = document.getElementById('dt-complete-btn');
            if (task.is_completed) {
                completeBtn.innerHTML = `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Completed ✓`;
                completeBtn.style.cssText += 'background:var(--green);color:#fff;border-color:var(--green);';
            } else {
                completeBtn.innerHTML = `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Mark Complete`;
                completeBtn.style.cssText += 'background:var(--green-lt);color:var(--green);border-color:#bbf7d0;';
            }

            document.getElementById('dt-tags').innerHTML = `
                ${task.priority ? `<span class="tk-priority ${task.priority}">${task.priority.toUpperCase()}</span>` : ''}
                ${task.column   ? `<span class="tk-card-tag" style="background:#eff6ff;color:#2563eb"><span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block"></span>${task.column.title}</span>` : ''}`;

            // Clear and repopulate collaborators
            const selectedContainer = document.getElementById('dt-selected-collabs');
            if (selectedContainer) selectedContainer.innerHTML = '';
            document.querySelectorAll('#collabDropdown .tk-collab-option').forEach(o => o.classList.remove('selected'));
            (task.collaborators || []).forEach(m => window.selectCollab(m.id, m.name));

            const deleteBtn = document.getElementById('dt-delete-btn');
            document.getElementById('dt-delete-form').action = `/tasks/${taskId}`;
            if (deleteBtn) deleteBtn.onclick = () => {
                Swal.fire({
                    title: 'Delete this task?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    background: '#ffffff',
                    color: '#1e293b',
                    borderRadius: '16px',
                    customClass: {
                        popup: 'tk-rounded-modal',
                        confirmButton: 'tk-swal-btn',
                        cancelButton: 'tk-swal-btn'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('dt-delete-form').submit();
                    }
                });
            };

            if (task.start_date) {
                document.getElementById('dt-startdate').value             = task.start_date.substr(0,10);
                document.getElementById('start-date-field').style.display = '';
                const btn = document.getElementById('start-date-toggle');
                btn.textContent = '− Remove'; btn.style.color = 'var(--red)'; btn.style.background = 'var(--red-lt)';
            } else {
                document.getElementById('dt-startdate').value             = '';
                document.getElementById('start-date-field').style.display = 'none';
                const btn = document.getElementById('start-date-toggle');
                btn.textContent = '+ Add'; btn.style.color = 'var(--blue)'; btn.style.background = 'var(--blue-lt)';
            }

            // Render dynamic content first
            renderChecklist(task.checklist_items || []);
            renderComments(task.activities || []);
            renderAttachments(task.attachments || []);
            startCommentPolling();

            // ← Apply edit mode LAST, after all elements are in the DOM
            currentCanEdit = task.can_edit !== false;
            applyEditMode(currentCanEdit);

        } catch (err) { console.error('openDetail error:', err); }
    };

    window.closeDetail = function() {
        document.getElementById('detailModal').classList.remove('open');
        closeCollabDropdown();
        currentTaskId  = null;
        currentCanEdit = true;
        stopCommentPolling();
    };

    window.saveDetail = function() {
        if (!currentCanEdit) return; // guard — blocked even if button somehow appears

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
       CHECKLIST
    ================================================================ */
    function renderChecklist(items) {
        const done  = items.filter(i => i.is_completed).length;
        const total = items.length;
        const pct   = total > 0 ? Math.round((done / total) * 100) : 0;
        document.getElementById('dt-check-badge').textContent    = `${done}/${total}`;
        document.getElementById('dt-prog-section').style.display = total > 0 ? '' : 'none';
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
       MODAL CLOSE BEHAVIOURS
    ================================================================ */
    const detailModal = document.getElementById('detailModal');
    if (detailModal) detailModal.addEventListener('click', e => {
        if (e.target === detailModal) closeDetail();
    });

    const createTaskModal = document.getElementById('createTaskModal');
    if (createTaskModal) createTaskModal.addEventListener('click', e => {
        if (e.target === createTaskModal) createTaskModal.style.display = 'none';
    });

    const addColumnModal = document.getElementById('addColumnModal');
    if (addColumnModal) addColumnModal.addEventListener('click', e => {
        if (e.target === addColumnModal) addColumnModal.style.display = 'none';
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
    const searchInput = document.querySelector('.tk-topnav-search input');
    if (searchInput) searchInput.addEventListener('input', function () {
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
            let noRes = col.querySelector('.tk-no-results');
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
   APPLY EDIT MODE
============================================================ */
function applyEditMode(canEdit) {
    const form = document.getElementById('detailForm');
    if (!form) return;

    // Disable/enable all form inputs
    form.querySelectorAll('input, select, textarea').forEach(el => {
        el.disabled = !canEdit;
    });

    // Footer buttons
    const saveBtn     = document.querySelector('.tk-btn-save');
    const deleteBtn   = document.getElementById('dt-delete-btn');
    const completeBtn = document.getElementById('dt-complete-btn');
    const attachBtn   = document.querySelector('.tk-attach-btn');
    const dropzone    = document.getElementById('attachDropzone');
    const collabSearch = document.getElementById('collabSearchWrap');
    const startToggle  = document.getElementById('start-date-toggle');

    [saveBtn, deleteBtn, completeBtn, attachBtn, dropzone, collabSearch, startToggle].forEach(el => {
        if (!el) return;
        if (!canEdit) el.style.setProperty('display', 'none', 'important');
        else el.style.removeProperty('display');
    });

    // Hide dynamic elements (checklist deletes, attachment deletes, collab remove X)
    document.querySelectorAll('.tk-check-del, .tk-attach-del, .tk-remove-x').forEach(el => {
        el.style.display = canEdit ? '' : 'none';
    });

    // Hide checklist add row
    const checkAdd = document.querySelector('.tk-check-add');
    if (checkAdd) checkAdd.style.display = canEdit ? '' : 'none';

    // Read-only banner
    let banner = document.getElementById('readonly-banner');
    if (!canEdit) {
        if (!banner) {
            banner = document.createElement('div');
            banner.id = 'readonly-banner';
            banner.style.cssText = 'background:#fef9c3;border:1px solid #fde68a;color:#92400e;font-size:13px;font-weight:600;padding:.8rem 1rem;border-radius:12px;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem;flex-shrink:0;';
            banner.innerHTML = '🔒 View Only — you do not have permission to modify this task.';
            // Insert at top of left panel
            const leftPanel = document.querySelector('.tk-detail > div > div:first-child');
            if (leftPanel) leftPanel.prepend(banner);
        }
        banner.style.setProperty('display', 'flex', 'important');
    } else {
        if (banner) banner.style.display = 'none';
    }
}

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
            document.querySelectorAll('.tk-dropdown').forEach(d => d.classList.remove('open'));
            document.querySelectorAll('.tk-nav-profile').forEach(u => u.classList.remove('active'));
            document.querySelectorAll('.tk-nav-chevron').forEach(c => c.style.transform = '');
            if (!isOpen) {
                drop.classList.add('open');
                if (chevId) {
                    const chev = document.getElementById(chevId);
                    if (chev) chev.style.transform = 'rotate(180deg)';
                }
                if (btnId === 'profile-btn') btn.closest('.tk-nav-profile')?.classList.add('active');
                if (btnId === 'notif-btn') loadNotifications(drop);
            }
        });
    }

    document.addEventListener('click', () => {
        document.querySelectorAll('.tk-dropdown').forEach(d => d.classList.remove('open'));
        document.querySelectorAll('.tk-nav-profile').forEach(u => u.classList.remove('active'));
        document.querySelectorAll('.tk-nav-chevron').forEach(c => c.style.transform = '');
    });

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