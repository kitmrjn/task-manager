<x-app-layout>
<x-slot name="header">
    {{-- Top nav: search left, icons + user right --}}
    <div class="tk-topnav">
        <div class="tk-topnav-search">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" placeholder="Search tasks, projects…" />
            <span class="tk-topnav-kbd">⌘K</span>
        </div>
        <div class="tk-topnav-right">
            <button class="tk-topnav-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </button>
            <button class="tk-topnav-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                <span class="tk-topnav-dot"></span>
            </button>
            <div class="tk-topnav-user">
                <div class="tk-topnav-avatar">{{ strtoupper(substr(Auth::user()->name,0,2)) }}</div>
                <div class="tk-topnav-userinfo">
                    <span class="tk-topnav-username">{{ Auth::user()->name }}</span>
                    <span class="tk-topnav-email">{{ Auth::user()->email }}</span>
                </div>
            </div>
        </div>
    </div>
</x-slot>

<style>
@import url('https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&display=swap');

:root {
    --bg:       #f0f2f6;
    --white:    #ffffff;
    --border:   #e4e7ec;
    --border-2: #d0d5dd;
    --text:     #0d1117;
    --muted:    #4b5563;
    --soft:     #6b7280;
    --blue:     #2563eb;
    --blue-lt:  #eff6ff;
    --green:    #16a34a;
    --green-lt: #f0fdf4;
    --amber:    #d97706;
    --amber-lt: #fffbeb;
    --red:      #dc2626;
    --red-lt:   #fef2f2;
    --radius:   10px;
    --radius-sm:8px;
    --shadow:   0 1px 3px rgba(16,24,40,0.08), 0 1px 2px rgba(16,24,40,0.04);
    --shadow-md:0 4px 10px rgba(16,24,40,0.10);
    --shadow-lg:0 12px 32px rgba(16,24,40,0.14);
}

* { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--bg); font-family: 'Geist', sans-serif; color: var(--text); }

/* ── Override default header to be clean topnav ── */
.tf-main-header { background: var(--white) !important; border-bottom: 1px solid var(--border) !important; padding: 0 !important; }
.tf-main-header-inner { max-width: 100% !important; padding: 0 !important; }

/* ── Top nav bar ── */
.tk-topnav {
    display: flex; align-items: center; justify-content: space-between;
    padding: .6rem 1.5rem; gap: 1rem;
    height: 54px;
}
.tk-topnav-search {
    display: flex; align-items: center; gap: .5rem;
    background: #f5f6fa; border: 1.5px solid var(--border);
    border-radius: 8px; padding: .45rem .85rem;
    width: 280px; flex-shrink: 0;
}
.tk-topnav-search svg { color: var(--soft); flex-shrink: 0; }
.tk-topnav-search input {
    border: none; background: transparent; outline: none;
    font-family: 'Geist', sans-serif; font-size: 13px; color: var(--text);
    flex: 1; min-width: 0;
}
.tk-topnav-search input::placeholder { color: var(--soft); }
.tk-topnav-kbd {
    font-size: 10px; font-weight: 600; color: var(--soft);
    background: var(--white); border: 1px solid var(--border);
    border-radius: 4px; padding: 1px 5px; flex-shrink: 0;
}
.tk-topnav-right { display: flex; align-items: center; gap: .75rem; margin-left: auto; }
.tk-topnav-icon {
    width: 36px; height: 36px; border-radius: 8px;
    border: 1.5px solid var(--border); background: var(--white);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--muted); position: relative;
    transition: background .15s;
}
.tk-topnav-icon:hover { background: var(--bg); }
.tk-topnav-dot {
    position: absolute; top: 6px; right: 6px;
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--red); border: 1.5px solid var(--white);
}
.tk-topnav-user {
    display: flex; align-items: center; gap: .6rem;
    cursor: pointer; padding: .3rem .5rem;
    border-radius: 8px; transition: background .15s;
}
.tk-topnav-user:hover { background: var(--bg); }
.tk-topnav-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: var(--blue); color: #fff;
    font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.tk-topnav-username { display: block; font-size: 13px; font-weight: 600; color: var(--text); line-height: 1.2; }
.tk-topnav-email    { display: block; font-size: 11px; color: var(--soft); }

/* ── Page body ── */
.tk-page-body { padding: 1.75rem 1.75rem 0; }

/* ── Page header row (Tasks title + New Task btn) ── */
.tk-page-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    gap: 1rem; margin-bottom: 1.1rem; flex-wrap: wrap;
}
.tk-page-title { font-size: 28px; font-weight: 800; color: var(--text); letter-spacing: -.02em; }
.tk-page-sub   { font-size: 13px; color: var(--soft); margin-top: 3px; font-weight: 400; }

.tk-new-btn {
    display: flex; align-items: center; gap: .4rem;
    padding: .6rem 1.3rem; background: var(--blue); color: #fff;
    border: none; border-radius: 9px; font-family: 'Geist', sans-serif;
    font-size: 14px; font-weight: 600; cursor: pointer;
    box-shadow: 0 1px 3px rgba(37,99,235,.35);
    transition: background .15s; white-space: nowrap; flex-shrink: 0;
}
.tk-new-btn:hover { background: #1d4ed8; }

/* ── Filter tabs ── */
.tk-filter-tabs {
    display: flex; background: #eaecf0; border-radius: 9px;
    padding: 3px; gap: 2px; border: 1px solid var(--border);
    margin-bottom: 1.5rem; width: fit-content;
}
.tk-filter-tab {
    padding: .35rem 1.1rem; border: none; background: transparent;
    border-radius: 7px; font-family: 'Geist', sans-serif; font-size: 13px;
    font-weight: 500; color: var(--muted); cursor: pointer;
    transition: background .15s, color .15s;
}
.tk-filter-tab.active { background: var(--white); color: var(--text); font-weight: 600; box-shadow: var(--shadow); }
.tk-filter-tab:hover:not(.active) { color: var(--text); background: rgba(255,255,255,.55); }

/* ── Board ── */
.tk-board {
    display: flex; gap: 1.25rem; overflow-x: auto;
    align-items: flex-start; padding-bottom: 2rem;
}
.tk-board::-webkit-scrollbar { height: 5px; }
.tk-board::-webkit-scrollbar-thumb { background: var(--border-2); border-radius: 99px; }

/* ── Column ── */
.tk-col {
    width: 290px; min-width: 290px; flex-shrink: 0;
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius); box-shadow: var(--shadow);
    display: flex; flex-direction: column;
    max-height: calc(100vh - 230px); overflow: hidden;
}
.tk-col-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: .85rem 1rem; flex-shrink: 0;
    border-radius: var(--radius) var(--radius) 0 0;
}
.tk-col-title-row { display: flex; align-items: center; gap: .55rem; }
.tk-col-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
.tk-col-name  { font-size: 14px; font-weight: 700; color: var(--text); }
.tk-col-count {
    background: var(--bg); color: var(--muted);
    font-size: 11.5px; font-weight: 700;
    padding: 1px 8px; border-radius: 99px; border: 1px solid var(--border);
}
.tk-col-actions { display: flex; align-items: center; gap: .2rem; }
.tk-col-action {
    width: 26px; height: 26px; border: none; background: transparent;
    color: var(--soft); cursor: pointer; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s, color .15s; font-size: 17px; line-height: 1;
}
.tk-col-action:hover { background: var(--bg); color: var(--text); }

.tk-cards {
    flex: 1; overflow-y: auto; padding: .75rem;
    display: flex; flex-direction: column; gap: .65rem;
    min-height: 80px;
    scrollbar-width: thin; scrollbar-color: var(--border) transparent;
}
.tk-cards::-webkit-scrollbar { width: 4px; }
.tk-cards::-webkit-scrollbar-thumb { background: var(--border); border-radius: 99px; }

.tk-add-task-btn {
    padding: .65rem 1rem; border: none; background: transparent;
    color: var(--soft); font-family: 'Geist', sans-serif; font-size: 13px;
    font-weight: 500; cursor: pointer; text-align: center; width: 100%;
    border-top: 1px solid var(--border); flex-shrink: 0;
    transition: color .15s, background .15s;
}
.tk-add-task-btn:hover { color: var(--blue); background: var(--blue-lt); }

/* ── Task Card ── */
.tk-card {
    background: var(--white); border: 1.5px solid var(--border);
    border-radius: var(--radius-sm); padding: 1rem;
    cursor: pointer; transition: border-color .15s, box-shadow .15s, transform .15s;
    animation: cardIn .3s ease both;
}
.tk-card:hover { border-color: #93c5fd; box-shadow: var(--shadow-md); transform: translateY(-2px); }
.tk-card.is-completed { opacity: .65; }
.tk-card.is-completed .tk-card-title { text-decoration: line-through; color: var(--soft); }
@keyframes cardIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:none} }

.tk-card-tag {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: 11px; font-weight: 600; padding: 2px 8px;
    border-radius: 99px; margin-bottom: .55rem;
}
.tk-card-title { font-size: 14.5px; font-weight: 700; color: var(--text); line-height: 1.4; margin-bottom: .35rem; letter-spacing: -.01em; }
.tk-card-desc  { font-size: 12.5px; color: var(--muted); line-height: 1.55; margin-bottom: .65rem;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

.tk-card-prog { margin-bottom: .6rem; }
.tk-card-prog-row { display: flex; justify-content: space-between; margin-bottom: .3rem; }
.tk-card-prog-label { font-size: 11px; color: var(--soft); }
.tk-card-prog-val   { font-size: 11px; font-weight: 700; color: var(--muted); }
.tk-prog-track { height: 5px; background: var(--bg); border-radius: 99px; overflow: hidden; }
.tk-prog-fill  { height: 100%; border-radius: 99px; transition: width .5s ease; }
.tk-prog-fill.blue  { background: var(--blue); }
.tk-prog-fill.green { background: var(--green); }

.tk-card-footer { display: flex; align-items: center; justify-content: space-between; margin-top: .6rem; }
.tk-card-assignee {
    width: 26px; height: 26px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 700; color: #fff; flex-shrink: 0;
}
.tk-card-meta { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }
.tk-card-date { font-size: 11.5px; color: var(--muted); font-weight: 500; display: flex; align-items: center; gap: .3rem; }
.tk-card-date svg { width: 12px; height: 12px; }

.tk-priority { font-size: 10.5px; font-weight: 700; padding: 2px 8px; border-radius: 5px; text-transform: uppercase; letter-spacing: .02em; }
.tk-priority.high   { background: var(--red-lt);   color: var(--red); }
.tk-priority.medium { background: var(--amber-lt); color: var(--amber); }
.tk-priority.low    { background: var(--green-lt); color: var(--green); }

.tk-complete-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: 10.5px; font-weight: 600; padding: 2px 8px;
    border-radius: 99px; background: var(--green-lt); color: var(--green);
    border: 1px solid #bbf7d0; margin-bottom: .5rem;
}

/* Add column ghost */
.tk-col-add {
    width: 260px; min-width: 260px; flex-shrink: 0;
    border: 2px dashed var(--border-2); border-radius: var(--radius);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: .5rem; padding: 2.5rem 1rem; color: var(--soft); cursor: pointer;
    transition: border-color .15s, color .15s;
}
.tk-col-add:hover { border-color: var(--blue); color: var(--blue); }
.tk-col-add-icon  { font-size: 22px; }
.tk-col-add-label { font-size: 13px; font-weight: 600; }

/* ── MODAL BASE ── */
.tk-modal-overlay {
    position: fixed; inset: 0; background: rgba(16,24,40,.5);
    backdrop-filter: blur(4px); z-index: 500;
    display: none; align-items: center; justify-content: center; padding: 1rem;
}
.tk-modal-overlay.open { display: flex; }

/* ── TASK DETAIL MODAL ── */
.tk-detail {
    background: var(--white); border-radius: 16px;
    width: 100%; max-width: 580px; max-height: 92vh;
    overflow-y: auto; box-shadow: var(--shadow-lg);
    animation: modalIn .25s ease both;
    scrollbar-width: thin; scrollbar-color: var(--border) transparent;
}
.tk-detail::-webkit-scrollbar { width: 4px; }
.tk-detail::-webkit-scrollbar-thumb { background: var(--border); border-radius: 99px; }
@keyframes modalIn { from{opacity:0;transform:translateY(16px) scale(.98)} to{opacity:1;transform:none} }

.tk-detail-head {
    padding: 1.4rem 1.5rem 1.1rem; border-bottom: 1px solid var(--border);
    position: sticky; top: 0; background: var(--white); z-index: 10;
}
.tk-detail-head-top { display: flex; align-items: flex-start; justify-content: space-between; gap: .75rem; margin-bottom: .65rem; }
.tk-detail-title { font-size: 19px; font-weight: 800; color: var(--text); line-height: 1.3; flex: 1; letter-spacing: -.02em; }
.tk-detail-close {
    width: 32px; height: 32px; border: 1.5px solid var(--border); border-radius: 8px;
    background: var(--white); cursor: pointer; display: flex; align-items: center;
    justify-content: center; color: var(--muted); flex-shrink: 0; transition: background .15s;
}
.tk-detail-close:hover { background: var(--bg); }
.tk-detail-tags { display: flex; gap: .4rem; flex-wrap: wrap; }
.tk-detail-body { padding: 1.3rem 1.5rem; display: flex; flex-direction: column; gap: 1.35rem; }

.tk-fields { display: grid; grid-template-columns: 1fr 1fr; gap: .9rem; }
.tk-field-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .09em; color: var(--soft); margin-bottom: .4rem; }
.tk-field-select, .tk-field-input {
    width: 100%; padding: .6rem .85rem;
    border: 1.5px solid var(--border); border-radius: 8px;
    font-family: 'Geist', sans-serif; font-size: 13.5px; color: var(--text);
    background: var(--white); outline: none;
    transition: border-color .15s, box-shadow .15s;
    appearance: none; -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2398a2b3' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right .75rem center;
}
.tk-field-input { background-image: none; }
.tk-field-select:focus, .tk-field-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.tk-field-textarea {
    width: 100%; padding: .7rem .85rem;
    border: 1.5px solid var(--border); border-radius: 8px;
    font-family: 'Geist', sans-serif; font-size: 13.5px; color: var(--text);
    resize: vertical; min-height: 95px; outline: none;
    transition: border-color .15s, box-shadow .15s;
}
.tk-field-textarea:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }

.tk-section-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: .75rem; }
.tk-section-title { display: flex; align-items: center; gap: .5rem; font-size: 14.5px; font-weight: 700; color: var(--text); }
.tk-section-title svg { width: 16px; height: 16px; color: var(--muted); }
.tk-section-badge { font-size: 12px; font-weight: 700; background: var(--bg); color: var(--muted); padding: 2px 9px; border-radius: 99px; border: 1px solid var(--border); }

.tk-progress-block { background: var(--bg); border-radius: 10px; padding: 1.1rem 1.2rem; display: flex; align-items: center; gap: 1rem; }
.tk-progress-pct   { font-size: 24px; font-weight: 800; color: var(--blue); min-width: 52px; letter-spacing: -.02em; }
.tk-progress-right { flex: 1; }
.tk-progress-sub   { font-size: 12.5px; color: var(--muted); margin-top: .3rem; font-weight: 500; }
.tk-progress-track { height: 8px; background: #dbeafe; border-radius: 99px; overflow: hidden; margin-bottom: .3rem; }
.tk-progress-fill  { height: 100%; background: var(--blue); border-radius: 99px; transition: width .6s ease; }

.tk-checklist { display: flex; flex-direction: column; gap: .45rem; }
.tk-check-item { display: flex; align-items: center; gap: .7rem; padding: .65rem .85rem; border: 1.5px solid var(--border); border-radius: 8px; transition: border-color .15s, background .15s; }
.tk-check-item:hover { border-color: #93c5fd; background: var(--blue-lt); }
.tk-check-item input[type=checkbox] { width: 16px; height: 16px; cursor: pointer; accent-color: var(--blue); flex-shrink: 0; }
.tk-check-text { flex: 1; font-size: 13.5px; color: var(--text); font-weight: 500; }
.tk-check-text.done { text-decoration: line-through; color: var(--soft); }
.tk-check-del { width: 22px; height: 22px; border: none; background: transparent; color: var(--soft); cursor: pointer; border-radius: 4px; display: flex; align-items: center; justify-content: center; transition: color .15s, background .15s; flex-shrink: 0; }
.tk-check-del:hover { color: var(--red); background: var(--red-lt); }
.tk-check-add { display: flex; gap: .5rem; margin-top: .4rem; }
.tk-check-add input { flex: 1; padding: .6rem .85rem; border: 1.5px dashed var(--border-2); border-radius: 8px; font-family: 'Geist', sans-serif; font-size: 13.5px; color: var(--text); outline: none; background: var(--bg); transition: border-color .15s, background .15s; }
.tk-check-add input:focus { border-color: var(--blue); background: var(--white); border-style: solid; }
.tk-check-add button { padding: .6rem 1.1rem; background: var(--blue); color: #fff; border: none; border-radius: 8px; font-family: 'Geist', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; transition: background .15s; }
.tk-check-add button:hover { background: #1d4ed8; }

.tk-subtask-list { display: flex; flex-direction: column; gap: .45rem; }
.tk-subtask-item { display: flex; align-items: center; gap: .7rem; padding: .6rem .85rem; border: 1.5px solid var(--border); border-radius: 8px; background: var(--bg); transition: border-color .15s; }
.tk-subtask-item:hover { border-color: #93c5fd; }
.tk-subtask-item input[type=checkbox] { width: 15px; height: 15px; accent-color: var(--blue); cursor: pointer; flex-shrink: 0; }
.tk-subtask-text { flex: 1; font-size: 13px; color: var(--text); font-weight: 500; }
.tk-subtask-text.done { text-decoration: line-through; color: var(--soft); }

.tk-comments { display: flex; flex-direction: column; gap: .85rem; }
.tk-comment  { display: flex; gap: .85rem; }
.tk-comment-av { width: 32px; height: 32px; border-radius: 50%; background: var(--blue); color: #fff; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.tk-comment-body { flex: 1; }
.tk-comment-meta { display: flex; align-items: center; gap: .45rem; margin-bottom: .35rem; }
.tk-comment-name { font-size: 13px; font-weight: 700; color: var(--text); }
.tk-comment-time { font-size: 11.5px; color: var(--soft); }
.tk-comment-text { font-size: 13px; color: var(--muted); line-height: 1.55; font-weight: 500; background: var(--bg); padding: .65rem .9rem; border-radius: 8px; border: 1px solid var(--border); }
.tk-comment-input-row { display: flex; gap: .55rem; align-items: flex-start; margin-top: .3rem; }
.tk-comment-input-av { width: 32px; height: 32px; border-radius: 50%; background: var(--blue); color: #fff; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px; }
.tk-comment-input { flex: 1; padding: .65rem .9rem; border: 1.5px solid var(--border); border-radius: 8px; font-family: 'Geist', sans-serif; font-size: 13.5px; color: var(--text); outline: none; resize: none; transition: border-color .15s, box-shadow .15s; }
.tk-comment-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.tk-comment-post { padding: .6rem 1.1rem; background: var(--blue); color: #fff; border: none; border-radius: 8px; font-family: 'Geist', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; transition: background .15s; }
.tk-comment-post:hover { background: #1d4ed8; }

.tk-detail-footer { padding: 1.1rem 1.5rem; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; position: sticky; bottom: 0; background: var(--white); }
.tk-btn-delete { font-size: 12.5px; font-weight: 600; color: var(--red); background: transparent; border: 1.5px solid var(--border); border-radius: 7px; padding: .5rem 1rem; cursor: pointer; display: flex; align-items: center; gap: .4rem; transition: background .15s, border-color .15s; }
.tk-btn-delete:hover { background: var(--red-lt); border-color: var(--red); }
.tk-btn-save { font-size: 13px; font-weight: 700; color: #fff; background: var(--blue); border: none; border-radius: 7px; padding: .5rem 1.2rem; cursor: pointer; transition: background .15s; }
.tk-btn-save:hover { background: #1d4ed8; }

.tk-create-modal { background: var(--white); border-radius: 16px; width: 100%; max-width: 500px; box-shadow: var(--shadow-lg); animation: modalIn .25s ease both; }
.tk-create-head { padding: 1.3rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.tk-create-title { font-size: 17px; font-weight: 800; color: var(--text); letter-spacing: -.02em; }
.tk-create-body  { padding: 1.3rem 1.5rem; display: flex; flex-direction: column; gap: 1rem; }
.tk-create-footer { padding: 1.1rem 1.5rem; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: .65rem; }
.tk-btn-cancel { padding: .55rem 1.1rem; border: 1.5px solid var(--border); border-radius: 7px; background: var(--white); font-family: 'Geist', sans-serif; font-size: 13.5px; font-weight: 500; color: var(--muted); cursor: pointer; }
.tk-btn-cancel:hover { background: var(--bg); }

/* ── Soft tinted column backgrounds ── */
.tk-col.col-gray   { background: #f8f9fb; }
.tk-col.col-blue   { background: #f4f7ff; }
.tk-col.col-green  { background: #f3faf5; }
.tk-col.col-yellow { background: #fdf8ee; }
.tk-col.col-red    { background: #fdf4f4; }

.tk-col.col-gray   .tk-cards { background: #f8f9fb; }
.tk-col.col-blue   .tk-cards { background: #f4f7ff; }
.tk-col.col-green  .tk-cards { background: #f3faf5; }
.tk-col.col-yellow .tk-cards { background: #fdf8ee; }
.tk-col.col-red    .tk-cards { background: #fdf4f4; }

.tk-col.col-gray   .tk-col-head,
.tk-col.col-blue   .tk-col-head,
.tk-col.col-green  .tk-col-head,
.tk-col.col-yellow .tk-col-head,
.tk-col.col-red    .tk-col-head { background: inherit; }

.tk-col .tk-card { background: #ffffff; }

.av-blue{background:#2563eb}.av-teal{background:#0d9488}.av-amber{background:#d97706}
.av-red{background:#dc2626}.av-purple{background:#7c3aed}.av-green{background:#16a34a}
.av-pink{background:#db2777}.av-indigo{background:#4f46e5}
</style>

{{-- ── PAGE BODY ── --}}
<div class="tk-page-body">

    {{-- Page title row --}}
    <div class="tk-page-header">
        <div>
            <h1 class="tk-page-title">Tasks</h1>
            <p class="tk-page-sub">Drag cards between columns · Click a card to open detail</p>
        </div>
        <button class="tk-new-btn" onclick="document.getElementById('createTaskModal').style.display='flex'">
            + New Task
        </button>
    </div>

    {{-- Filter tabs --}}
    <div class="tk-filter-tabs" id="filterTabs">
        <button class="tk-filter-tab active" onclick="filterCards('all', this)">All</button>
        @foreach($board->columns ?? [] as $col)
            <button class="tk-filter-tab" onclick="filterCards('{{ $col->id }}', this)">{{ $col->title }}</button>
        @endforeach
    </div>

    {{-- Board --}}
    <div class="tk-board" id="boardContainer">

        @if($board)
            @foreach($board->columns as $column)
           @php
                $colDotColor = match($column->color ?? 'gray') {
                    'blue'   => '#3b82f6',
                    'green'  => '#22c55e',
                    'yellow' => '#f59e0b',
                    'red'    => '#ef4444',
                    default  => '#94a3b8',
                };
                $colClass = 'col-' . ($column->color ?? 'gray');
            @endphp

            <div class="tk-col {{ $colClass }}" id="col-wrapper-{{ $column->id }}" data-col-id="{{ $column->id }}">

                {{-- Column header (plain white, dot color indicator like inspo) --}}
                <div class="tk-col-head">
                    <div class="tk-col-title-row">
                        <div class="tk-col-dot" style="background:{{ $colDotColor }}"></div>
                        <span class="tk-col-name">{{ $column->title }}</span>
                        <span class="tk-col-count column-count">{{ $column->tasks->count() }}</span>
                    </div>
                    <div class="tk-col-actions">
                        <button class="tk-col-action" title="Add task" onclick="openCreate({{ $column->id }})">+</button>
                        <button class="tk-col-action" title="Delete column"
                            onclick="if(confirm('Delete this list?')) document.getElementById('del-col-{{ $column->id }}').submit()">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6M9 6V4h6v2"/></svg>
                        </button>
                        <form id="del-col-{{ $column->id }}" action="{{ route('columns.destroy', $column->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                    </div>
                </div>

                {{-- Cards --}}
                <div class="tk-cards sortable-column" id="column-{{ $column->id }}" data-column-id="{{ $column->id }}">
                    @foreach($column->tasks as $task)
                    @php
                        $total     = $task->checklistItems->count();
                        $done      = $task->checklistItems->where('is_completed', true)->count();
                        $pct       = $total > 0 ? round(($done / $total) * 100) : 0;
                        $progColor = $pct == 100 ? 'green' : 'blue';
                        $avColors  = ['av-blue','av-teal','av-amber','av-red','av-purple','av-green','av-pink','av-indigo'];
                        $avClass   = $avColors[($task->assigned_to ?? 0) % 8];
                    @endphp

                    <div class="tk-card {{ $task->is_completed ? 'is-completed' : '' }}"
                         data-task-id="{{ $task->id }}"
                         data-col-id="{{ $column->id }}"
                         onclick="openDetail({{ $task->id }})">

                        @if($task->tag ?? null)
                        <div class="tk-card-tag" style="background:#eff6ff;color:#2563eb">
                            <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block"></span>
                            {{ $task->tag }}
                        </div>
                        @endif

                        <div class="tk-card-title">{{ $task->title }}</div>

                        @if($task->description)
                        <div class="tk-card-desc">{{ $task->description }}</div>
                        @endif

                        @if($total > 0)
                        <div class="tk-card-prog">
                            <div class="tk-card-prog-row">
                                <span class="tk-card-prog-label">Progress</span>
                                <span class="tk-card-prog-val">{{ $done }}/{{ $total }}</span>
                            </div>
                            <div class="tk-prog-track">
                                <div class="tk-prog-fill {{ $progColor }}" style="width:{{ $pct }}%"></div>
                            </div>
                        </div>
                        @endif

                        @if($task->is_completed)
                        <div class="tk-complete-badge">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                            Completed
                        </div>
                        @endif

                        <div class="tk-card-footer">
                            <div class="tk-card-meta">
                                @if($task->due_date)
                                <span class="tk-card-date">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                                </span>
                                @endif
                                <span class="tk-priority {{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
                            </div>
                            @if($task->assignee)
                            <div class="tk-card-assignee {{ $avClass }}" title="{{ $task->assignee->name }}">
                                {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <button class="tk-add-task-btn" onclick="openCreate({{ $column->id }})">+ Add Task</button>
            </div>
            @endforeach
        @endif

        <div class="tk-col-add" onclick="document.getElementById('addColumnModal').style.display='flex'">
            <div class="tk-col-add-icon">+</div>
            <div class="tk-col-add-label">Add Column</div>
        </div>

    </div>{{-- /.tk-board --}}
</div>{{-- /.tk-page-body --}}

{{-- TASK DETAIL MODAL --}}
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
    <div class="tk-detail-body">
        <form id="detailForm" method="POST">
            @csrf @method('PUT')
            <div class="tk-fields" style="margin-bottom:.2rem">
                <div><div class="tk-field-label">Status</div>
                    <select name="board_column_id" class="tk-field-select" id="dt-status">
                        @foreach($board->columns ?? [] as $col)<option value="{{ $col->id }}">{{ $col->title }}</option>@endforeach
                    </select></div>
                <div><div class="tk-field-label">Priority</div>
                    <select name="priority" class="tk-field-select" id="dt-priority">
                        <option value="low">Low</option><option value="medium">Medium</option><option value="high">High</option>
                    </select></div>
                <div><div class="tk-field-label">Assignee</div>
                    <select name="assigned_to" class="tk-field-select" id="dt-assignee">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach
                    </select></div>
                <div><div class="tk-field-label">Due Date</div>
                    <input type="date" name="due_date" class="tk-field-input" id="dt-duedate"></div>
            </div>
            <div style="margin-top:.75rem">
                <div class="tk-field-label" style="margin-bottom:.45rem;display:flex;align-items:center;gap:.35rem">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Description
                </div>
                <textarea name="description" class="tk-field-textarea" id="dt-desc" placeholder="Add a description…"></textarea>
            </div>
        </form>

        <div id="dt-prog-section" style="display:none">
            <div class="tk-section-head">
                <div class="tk-section-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>Progress</div>
                <span style="font-size:12px;color:var(--soft);font-weight:500">auto-tracked from checklist</span>
            </div>
            <div class="tk-progress-block">
                <div class="tk-progress-pct" id="dt-pct">0%</div>
                <div class="tk-progress-right">
                    <div class="tk-progress-track"><div class="tk-progress-fill" id="dt-prog-fill" style="width:0%"></div></div>
                    <div class="tk-progress-sub" id="dt-prog-sub">0 of 0 checklist items done</div>
                </div>
            </div>
        </div>

        <div>
            <div class="tk-section-head">
                <div class="tk-section-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>Checklist</div>
                <span class="tk-section-badge" id="dt-check-badge">0/0</span>
            </div>
            <div class="tk-checklist" id="dt-checklist"></div>
            <div class="tk-check-add" style="margin-top:.5rem">
                <input type="text" id="checkInput" placeholder="Add checklist item…">
                <button onclick="addCheckItem()">Add</button>
            </div>
        </div>

        <div>
            <div class="tk-section-head">
                <div class="tk-section-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>Subtasks</div>
                <span class="tk-section-badge" id="dt-subtask-badge">0</span>
            </div>
            <div class="tk-subtask-list" id="dt-subtasks"></div>
            <div class="tk-check-add" style="margin-top:.5rem">
                <input type="text" id="subtaskInput" placeholder="Add a subtask…">
                <button onclick="addSubtask()">Add</button>
            </div>
        </div>

        <div>
            <div class="tk-section-head">
                <div class="tk-section-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>Comments</div>
                <span class="tk-section-badge" id="dt-comment-count">0</span>
            </div>
            <div class="tk-comments" id="dt-comments"></div>
            <div class="tk-comment-input-row" style="margin-top:.5rem">
                <div class="tk-comment-input-av">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <textarea class="tk-comment-input" id="commentInput" rows="2" placeholder="Write a comment… (Enter to post)"
                    onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();postComment();}"></textarea>
                <button class="tk-comment-post" onclick="postComment()">Post</button>
            </div>
        </div>
    </div>

    <div class="tk-detail-footer">
        <button class="tk-btn-delete" id="dt-delete-btn">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>Delete
        </button>
        <div style="display:flex;gap:.55rem">
            <button class="tk-btn-cancel" onclick="closeDetail()">Cancel</button>
            <button class="tk-btn-save" onclick="saveDetail()">Save Changes</button>
        </div>
    </div>
    <form id="dt-delete-form" method="POST" class="hidden">@csrf @method('DELETE')</form>
</div>
</div>

{{-- CREATE TASK MODAL --}}
<div class="tk-modal-overlay" id="createTaskModal">
<div class="tk-create-modal">
    <div class="tk-create-head">
        <div class="tk-create-title">New Task</div>
        <button class="tk-detail-close" onclick="document.getElementById('createTaskModal').style.display='none'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>
    <form action="/tasks" method="POST">
        @csrf
        <input type="hidden" name="board_column_id" id="create-col-id">
        <div class="tk-create-body">
            <div><div class="tk-field-label">Title</div><input type="text" name="title" required class="tk-field-input" placeholder="Task title…" style="width:100%"></div>
            <div><div class="tk-field-label">Description</div><textarea name="description" class="tk-field-textarea" placeholder="Optional description…"></textarea></div>
            <div class="tk-fields">
                <div><div class="tk-field-label">Assign To</div>
                    <select name="assigned_to" class="tk-field-select">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach
                    </select></div>
                <div><div class="tk-field-label">Priority</div>
                    <select name="priority" class="tk-field-select">
                        <option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option>
                    </select></div>
                <div><div class="tk-field-label">Due Date</div><input type="date" name="due_date" class="tk-field-input"></div>
            </div>
        </div>
        <div class="tk-create-footer">
            <button type="button" class="tk-btn-cancel" onclick="document.getElementById('createTaskModal').style.display='none'">Cancel</button>
            <button type="submit" class="tk-btn-save">Create Task</button>
        </div>
    </form>
</div>
</div>

{{-- ADD COLUMN MODAL --}}
<div class="tk-modal-overlay" id="addColumnModal">
<div class="tk-create-modal">
    <div class="tk-create-head">
        <div class="tk-create-title">Add Column</div>
        <button class="tk-detail-close" onclick="document.getElementById('addColumnModal').style.display='none'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>
    <form action="{{ route('columns.store') }}" method="POST">
        @csrf
        <input type="hidden" name="board_id" value="{{ $board->id ?? '' }}">
        <div class="tk-create-body">
            <div><div class="tk-field-label">Title</div><input type="text" name="title" required class="tk-field-input" placeholder="Column name…" style="width:100%"></div>
            <div><div class="tk-field-label">Color</div>
                <div style="display:flex;gap:.6rem;flex-wrap:wrap;margin-top:.25rem">
                    @foreach(['gray'=>'#94a3b8','blue'=>'#3b82f6','green'=>'#22c55e','yellow'=>'#eab308','red'=>'#ef4444'] as $k=>$c)
                    <label style="cursor:pointer"><input type="radio" name="color" value="{{ $k }}" class="hidden" {{ $k=='gray'?'checked':'' }}>
                        <div style="width:34px;height:34px;border-radius:8px;background:{{ $c }}" onclick="this.closest('label').querySelector('input').checked=true"></div>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="tk-create-footer">
            <button type="button" class="tk-btn-cancel" onclick="document.getElementById('addColumnModal').style.display='none'">Cancel</button>
            <button type="submit" class="tk-btn-save">Add Column</button>
        </div>
    </form>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let currentTaskId = null, currentSubtasks = [];

document.querySelectorAll('.sortable-column').forEach(col => {
    new Sortable(col, {
        group:'shared', animation:200,
        onEnd(evt) {
            fetch(`/tasks/${evt.item.dataset.taskId}/move`, {
                method:'PATCH', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
                body:JSON.stringify({board_column_id: evt.to.dataset.columnId})
            }).then(()=>updateCounts());
        }
    });
});

function updateCounts() {
    document.querySelectorAll('.sortable-column').forEach(col => {
        const wrap = col.closest('[id^=col-wrapper-]');
        if(wrap) wrap.querySelector('.column-count').textContent = col.querySelectorAll('.tk-card').length;
    });
}

function filterCards(colId, btn) {
    document.querySelectorAll('.tk-filter-tab').forEach(t=>t.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('[id^=col-wrapper-]').forEach(col => {
        col.style.display = (colId==='all' || col.dataset.colId==colId) ? '' : 'none';
    });
}

function openCreate(colId) {
    document.getElementById('create-col-id').value = colId;
    document.getElementById('createTaskModal').style.display = 'flex';
}

async function openDetail(taskId) {
    currentTaskId = taskId; currentSubtasks = [];
    document.getElementById('detailModal').classList.add('open');
    const res  = await fetch(`/tasks/${taskId}/detail`, {headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF}});
    const task = await res.json();
    document.getElementById('dt-title').textContent = task.title;
    document.getElementById('dt-tags').innerHTML = `
        ${task.priority ? `<span class="tk-priority ${task.priority}">${cap(task.priority)}</span>` : ''}
        ${task.column   ? `<span class="tk-card-tag" style="background:#eff6ff;color:#2563eb"><span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block"></span>${task.column.title}</span>` : ''}`;
    document.getElementById('detailForm').action  = `/tasks/${taskId}`;
    document.getElementById('dt-desc').value      = task.description || '';
    document.getElementById('dt-duedate').value   = task.due_date ? task.due_date.substr(0,10) : '';
    document.getElementById('dt-priority').value  = task.priority || 'medium';
    if(task.assigned_to)     document.getElementById('dt-assignee').value = task.assigned_to;
    if(task.board_column_id) document.getElementById('dt-status').value   = task.board_column_id;
    document.getElementById('dt-delete-form').action = `/tasks/${taskId}`;
    document.getElementById('dt-delete-btn').onclick = () => { if(confirm('Delete this task?')) document.getElementById('dt-delete-form').submit(); };
    renderChecklist(task.checklist_items || []);
    currentSubtasks = task.subtasks || [];
    renderSubtasks();
    renderComments(task.activities || []);
}

function closeDetail() { document.getElementById('detailModal').classList.remove('open'); currentTaskId = null; }
function cap(s){ return s ? s.charAt(0).toUpperCase()+s.slice(1) : ''; }

function renderChecklist(items) {
    const done=items.filter(i=>i.is_completed).length, total=items.length, pct=total>0?Math.round((done/total)*100):0;
    document.getElementById('dt-check-badge').textContent=`${done}/${total}`;
    if(total>0){
        document.getElementById('dt-prog-section').style.display='';
        document.getElementById('dt-pct').textContent=pct+'%';
        document.getElementById('dt-prog-fill').style.width=pct+'%';
        document.getElementById('dt-prog-sub').textContent=`${done} of ${total} checklist items done`;
    } else { document.getElementById('dt-prog-section').style.display='none'; }
    document.getElementById('dt-checklist').innerHTML=items.map(item=>`
        <div class="tk-check-item" id="ci-${item.id}">
            <input type="checkbox" ${item.is_completed?'checked':''} onchange="toggleCheck(${item.id})">
            <span class="tk-check-text ${item.is_completed?'done':''}">${item.title}</span>
            <button class="tk-check-del" onclick="deleteCheck(${item.id})"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
        </div>`).join('');
}

function toggleCheck(id){ fetch(`/checklist-items/${id}/toggle`,{method:'PATCH',headers:{'X-CSRF-TOKEN':CSRF}}).then(()=>openDetail(currentTaskId)); }
function deleteCheck(id){ if(!confirm('Remove?')) return; fetch(`/checklist-items/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':CSRF}}).then(()=>openDetail(currentTaskId)); }
function addCheckItem(){
    const input=document.getElementById('checkInput'), title=input.value.trim(); if(!title) return;
    fetch(`/tasks/${currentTaskId}/checklist`,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify({title})})
        .then(()=>{input.value='';openDetail(currentTaskId);});
}

function renderSubtasks(){
    document.getElementById('dt-subtask-badge').textContent=currentSubtasks.length;
    document.getElementById('dt-subtasks').innerHTML=currentSubtasks.map((s,i)=>`
        <div class="tk-subtask-item">
            <input type="checkbox" ${s.done?'checked':''} onchange="toggleSubtask(${i},this)">
            <span class="tk-subtask-text ${s.done?'done':''}">${s.title}</span>
            <button class="tk-check-del" onclick="removeSubtask(${i})"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
        </div>`).join('')||'<div style="font-size:13px;color:var(--soft);padding:.4rem 0;font-weight:500">No subtasks yet.</div>';
}
function addSubtask(){ const input=document.getElementById('subtaskInput'),title=input.value.trim(); if(!title) return; currentSubtasks.push({title,done:false}); input.value=''; renderSubtasks(); }
function toggleSubtask(i,el){ currentSubtasks[i].done=el.checked; renderSubtasks(); }
function removeSubtask(i){ currentSubtasks.splice(i,1); renderSubtasks(); }

function renderComments(activities){
    document.getElementById('dt-comment-count').textContent=activities.length;
    document.getElementById('dt-comments').innerHTML=activities.map(a=>`
        <div class="tk-comment">
            <div class="tk-comment-av" style="background:${a.user?.avatar_color||'#2563eb'}">${(a.user?.name||'?').charAt(0).toUpperCase()}</div>
            <div class="tk-comment-body">
                <div class="tk-comment-meta"><span class="tk-comment-name">${a.user?.name||'Unknown'}</span><span class="tk-comment-time">${timeAgo(a.created_at)}</span></div>
                <div class="tk-comment-text">${a.description||''}</div>
            </div>
        </div>`).join('')||'<div style="font-size:13px;color:var(--soft);font-weight:500">No comments yet.</div>';
}
function postComment(){ const input=document.getElementById('commentInput'); if(!input.value.trim()) return; input.value=''; openDetail(currentTaskId); }
function timeAgo(ts){ if(!ts) return ''; const d=Math.floor((Date.now()-new Date(ts))/1000); if(d<60) return d+'s ago'; if(d<3600) return Math.floor(d/60)+'m ago'; if(d<86400) return Math.floor(d/3600)+'h ago'; return Math.floor(d/86400)+'d ago'; }
function saveDetail(){ document.getElementById('detailForm').submit(); }

document.getElementById('detailModal').addEventListener('click',function(e){if(e.target===this)closeDetail();});
document.getElementById('createTaskModal').addEventListener('click',function(e){if(e.target===this)this.style.display='none';});
document.getElementById('addColumnModal').addEventListener('click',function(e){if(e.target===this)this.style.display='none';});
document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeDetail();document.getElementById('createTaskModal').style.display='none';document.getElementById('addColumnModal').style.display='none';}});
</script>

</x-app-layout>