/**
 * dashboard.js  →  resources/js/dashboard.js
 *
 * Handles:
 *   - Animated number counters on stat cards
 *   - Stat card staggered entrance animation (IntersectionObserver)
 *   - Progress bar width animation on scroll into view
 *   - Notification & profile dropdown toggle
 */

document.addEventListener('DOMContentLoaded', () => {

    /* ================================================================
       1. ANIMATED NUMBER COUNTERS
          Reads data-count="" on each .stat-value and counts up to it.
    ================================================================ */
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count, 10);
        if (!target) return;

        let current = 0;
        const step  = Math.max(1, Math.ceil(target / 55));

        const timer = setInterval(() => {
            current = Math.min(current + step, target);
            el.textContent = current;
            if (current >= target) clearInterval(timer);
        }, 18);
    });

    /* ================================================================
       2. STAT CARD ENTRANCE (staggered via IntersectionObserver)
          Adds .revealed to each card as it scrolls into view.
          CSS animation-delay on :nth-child handles the stagger.
    ================================================================ */
    const cardObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                cardObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.stat-card').forEach(card => cardObserver.observe(card));

    /* ================================================================
       3. PROGRESS BAR
          Animates width when the bar scrolls into view.
    ================================================================ */
    const fill = document.querySelector('.prog-fill');
    if (fill) {
        const progressObserver = new IntersectionObserver(([entry]) => {
            if (entry.isIntersecting) {
                fill.style.width = fill.dataset.width + '%';
                progressObserver.disconnect();
            }
        }, { threshold: 0.3 });

        progressObserver.observe(fill);
    }

    /* ================================================================
       4. DROPDOWN ENGINE
          Works for both the notifications and profile dropdowns.
          - Click trigger  → opens its dropdown, closes all others
          - Click outside  → closes everything
          - Profile chevron rotates 180° when open
    ================================================================ */
    function bindDropdown(btnId, dropId, chevId = null) {
        const btn  = document.getElementById(btnId);
        const drop = document.getElementById(dropId);
        const chev = chevId ? document.getElementById(chevId) : null;
        if (!btn || !drop) return;

        btn.addEventListener('click', e => {
            e.stopPropagation();
            const isOpen = drop.classList.contains('open');
            closeAll();
            if (!isOpen) {
                drop.classList.add('open');
                btn.classList.add('active');
                if (chev) chev.classList.add('open');
            }
        });
    }

    function closeAll() {
        document.querySelectorAll('.tk-dropdown').forEach(d => d.classList.remove('open'));
        document.querySelectorAll('.tk-topnav-user').forEach(u => u.classList.remove('active'));
        document.querySelectorAll('.tk-chevron').forEach(c => c.classList.remove('open'));
    }

    // Clicking inside a dropdown should NOT close it
    document.querySelectorAll('.tk-dropdown').forEach(d => {
        d.addEventListener('click', e => e.stopPropagation());
    });

    // Clicking anywhere outside closes all dropdowns
    document.addEventListener('click', closeAll);

    // Bind both dropdowns
    bindDropdown('notif-btn',   'notif-dropdown',   null);
    bindDropdown('profile-btn', 'profile-dropdown', 'profile-chevron');

    // ── Real notifications ────────────────────────────────────────
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

async function loadNotifications() {
    const list  = document.querySelector('#notif-dropdown .tk-dropdown-body');
    const count = document.querySelector('#notif-dropdown .tk-badge-pill');
    if (!list) return;
    list.innerHTML = '<div style="padding:1.2rem;text-align:center;font-size:13px;color:var(--c-soft);">Loading…</div>';
    try {
        const res  = await fetch('/notifications', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        });
        const data = await res.json();
        if (count) count.textContent = data.length ? data.length + ' new' : '';

        if (!data.length) {
            list.innerHTML = '<div style="padding:1.5rem;text-align:center;font-size:13.5px;color:var(--c-soft);">You\'re all caught up! 🎉</div>';
            return;
        }

        const iconMap = { comment:'💬', created:'✅', priority_change:'🔥', lead_change:'👤', column_change:'📋', completed:'🎉', checklist_added:'☑️' };
        list.innerHTML = data.map(n => `
            <div class="tk-notif-item">
                <div class="tk-notif-icon ni-blue">${iconMap[n.action] || '🔔'}</div>
                <div class="tk-notif-content">
                    <div class="tk-notif-text"><strong>${n.user || 'Someone'}</strong> ${n.description}${n.task ? ` on <em>${n.task}</em>` : ''}</div>
                    <div class="tk-notif-time">${n.time}</div>
                </div>
            </div>`).join('');
    } catch(e) {
        list.innerHTML = '<div style="padding:1rem;text-align:center;color:var(--c-red);">Failed to load.</div>';
    }
}

// Load notifications when bell is clicked
const notifBtn = document.getElementById('notif-btn');
if (notifBtn) {
    notifBtn.addEventListener('click', loadNotifications);
}

// ── Search My Tasks table ─────────────────────────────────────
const searchInput = document.querySelector('.tk-topnav-search input');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('.task-row').forEach(row => {
            const name = row.querySelector('.task-name')?.textContent.toLowerCase() || '';
            row.style.display = (!q || name.includes(q)) ? '' : 'none';
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    // Find all dropdown wrappers on the page
    const dropdownWraps = document.querySelectorAll('.tk-dropdown-wrap');

    dropdownWraps.forEach(wrap => {
        const btn = wrap.querySelector('button'); // The icon or profile avatar
        const dropdown = wrap.querySelector('.tk-dropdown'); // The actual menu

        if (btn && dropdown) {
            btn.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent this click from instantly closing it
                
                const isOpen = dropdown.classList.contains('open');

                // 1. Close ALL other dropdowns first (so they don't overlap)
                document.querySelectorAll('.tk-dropdown').forEach(d => d.classList.remove('open'));
                
                // 2. Close the Shift Pill popover if it's open
                document.getElementById('shiftPopover')?.classList.remove('open');
                document.getElementById('shiftChevron')?.classList.remove('open');

                // 3. Toggle the one you just clicked
                if (!isOpen) {
                    dropdown.classList.add('open');
                }
            });
        }
    });

    // Close all dropdowns when clicking anywhere else on the screen
    document.addEventListener('click', (e) => {
        // If the click wasn't inside a dropdown wrap or the shift pill wrap
        if (!e.target.closest('.tk-dropdown-wrap') && !e.target.closest('#shiftPillWrap')) {
            document.querySelectorAll('.tk-dropdown').forEach(d => d.classList.remove('open'));
            document.getElementById('shiftPopover')?.classList.remove('open');
            document.getElementById('shiftChevron')?.classList.remove('open');
        }
    });
});
});