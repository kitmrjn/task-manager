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
});