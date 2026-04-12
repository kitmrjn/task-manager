<x-app-layout>
    @section('title', 'Task')
<x-slot name="header">
    <div class="tk-topnav">

        <div class="tk-topnav-right">

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
                        {{-- FIXED: Settings available to all roles --}}
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

@push('styles')
    @vite('resources/css/tasks.css')
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    @vite('resources/js/tasks.js')
@endpush

<div class="tk-page-body">
    <div class="tk-page-header">
        <div>
            <h1 class="tk-page-title">Tasks</h1>
            <p class="tk-page-sub">Drag cards between columns · Click a card to open detail</p>
        </div>
        <div style="display:flex;gap:.6rem;flex-shrink:0;">
            @if(auth()->user()->can_access('can_create_tasks'))
            <button class="tk-add-col-btn" onclick="document.getElementById('addColumnModal').style.display='flex'">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add Column
            </button>
            @endif
        </div>
    </div>

    <div class="tk-filter-tabs" id="filterTabs">
        <button class="tk-filter-tab active" onclick="filterCards('all', this)">All</button>
        @foreach($board->columns ?? [] as $col)
            <button class="tk-filter-tab" onclick="filterCards('{{ $col->id }}', this)">{{ $col->title }}</button>
        @endforeach
    </div>

    <div class="tk-board" id="boardContainer">
        @if($board)
            @foreach($board->columns as $column)
            @php
                $colDotColor = match($column->color ?? 'gray') {
                    'blue'   => '#3b82f6', 'green'  => '#22c55e', 'yellow' => '#f59e0b',
                    'red'    => '#ef4444', 'orange' => '#f97316', 'purple' => '#a855f7',
                    'pink'   => '#ec4899', 'teal'   => '#14b8a6', 'indigo' => '#6366f1',
                    default  => '#94a3b8',
                };
                $colClass = 'col-' . ($column->color ?? 'gray');
            @endphp
            <div class="tk-col {{ $colClass }}" id="col-wrapper-{{ $column->id }}" data-col-id="{{ $column->id }}">
                <div class="tk-col-head">
                    <div class="tk-col-title-row" style="flex:1;min-width:0;">
                        <div class="tk-col-dot" style="background:{{ $colDotColor }};flex-shrink:0;"></div>
                        <div style="min-width:0;">
                            <span class="tk-col-name">{{ $column->title }}</span>
                            @if($column->description)
                                <div style="font-size:11.5px;color:var(--soft);margin-top:2px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;">
                                    {{ $column->description }}
                                </div>
                            @endif
                        </div>
                        <span class="tk-col-count column-count" style="flex-shrink:0;">{{ $column->tasks->count() }}</span>
                    </div>
                    <div class="tk-col-actions" style="position:relative;">
                        <button class="tk-col-action" title="Move Left" onclick="moveColumn('{{ $column->id }}', 'left')">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                        </button>
                        <button class="tk-col-action" title="Move Right" onclick="moveColumn('{{ $column->id }}', 'right')">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                        <button class="tk-col-action" onclick="toggleColMenu(event, {{ $column->id }})">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                        </button>
                        
                        <div id="menu-{{ $column->id }}" class="tk-col-menu hidden">
                            <button onclick="openEditColumn({{ $column->id }}, '{{ addslashes($column->title) }}', '{{ $column->color ?? 'gray' }}', '{{ addslashes($column->description ?? '') }}')">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                Edit Column
                            </button>
                        @if(auth()->user()->can_access('can_delete_tasks'))
                        <button class="delete" type="button" onclick="confirmDeleteColumn({{ $column->id }}, '{{ addslashes($column->title) }}')">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                            Delete Column
                        </button>
                        @endif
                        </div>
                        <form id="del-col-{{ $column->id }}" action="{{ route('columns.destroy', $column->id) }}" method="POST" class="hidden">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                </div>

                <div class="tk-cards sortable-column" id="column-{{ $column->id }}" data-column-id="{{ $column->id }}">
                    @foreach($column->tasks as $task)
                    @php
                        $total      = $task->checklistItems->count();
                        $done       = $task->checklistItems->where('is_completed', true)->count();
                        $pct        = $total > 0 ? round(($done / $total) * 100) : 0;
                        $progColor  = $pct == 100 ? 'green' : 'blue';
                        $avColors   = ['av-blue','av-teal','av-amber','av-red','av-purple','av-green','av-pink','av-indigo'];
                        $avClass    = $avColors[($task->assigned_to ?? 0) % 8];
                        $coverImage = $task->attachments->first(fn($a) => str_starts_with($a->mime_type, 'image/'));
                    @endphp

                    <div class="tk-card {{ $task->is_completed ? 'is-completed' : '' }}"
                         data-task-id="{{ $task->id }}"
                         data-col-id="{{ $column->id }}"
                         onclick="handleCardClick(event, {{ $task->id }})">

                        {{-- Cover image --}}
                        @if($coverImage)
                        <div class="tk-card-cover" style="background-image:url('{{ $coverImage->url() }}')"></div>
                        @endif

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
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                        @if($task->start_date)
                                            {{ \Carbon\Carbon::parse($task->start_date)->format('M d') }} -
                                        @endif
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                                    </span>
                                @endif

                                @if($task->attachments->count() > 0)
                                <span class="tk-card-attach-count" title="{{ $task->attachments->count() }} attachments">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.51a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                                    </svg>
                                    {{ $task->attachments->count() }}
                                </span>
                                @endif

                                <span class="tk-priority {{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
                            </div>

                            <div style="display:flex;align-items:center;justify-content:space-between;width:100%;margin-top:.45rem;">
                                <div style="display:flex;align-items:center;gap:5px;">
                                    @if($task->assignee)
                                        <div class="tk-card-assignee {{ $avClass }}" title="{{ $task->assignee->name }}" style="border:2px solid #fff;">
                                            {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                                        </div>
                                        <span style="font-size:11px;color:var(--soft);font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:80px;">
                                            {{ $task->assignee->name }}
                                        </span>
                                    @else
                                        <span style="font-size:11px;color:var(--soft);font-style:italic;font-weight:500;">Unassigned</span>
                                    @endif
                                </div>

                                @if($task->members->count() > 0)
                                <div style="display:flex;align-items:center;">
                                    @php $mColors = ['av-teal','av-purple','av-green','av-pink','av-amber','av-indigo']; @endphp
                                    @foreach($task->members->take(3) as $member)
                                        <div class="tk-card-assignee {{ $mColors[$loop->index % 6] }}"
                                             title="{{ $member->name }}"
                                             style="margin-left:{{ $loop->first ? '0' : '-8px' }};border:2px solid #fff;position:relative;z-index:{{ 10 - $loop->index }};flex-shrink:0;">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                    @endforeach
                                    @if($task->members->count() > 3)
                                        <div class="tk-card-assignee" style="margin-left:-8px;border:2px solid #fff;background:#94a3b8;font-size:9px;position:relative;z-index:5;flex-shrink:0;">
                                            +{{ $task->members->count() - 3 }}
                                        </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>{{-- end .tk-card-footer --}}

                    </div>{{-- end .tk-card --}}
                    @endforeach
                </div>
                @if(auth()->user()->can_access('can_create_tasks'))
                <button class="tk-add-task-btn" onclick="openCreate({{ $column->id }})">+ Add Task</button>
                @endif
            </div>
            @endforeach
        @endif
    </div>
</div>

@include('tasks.partials.detail-modal')

{{-- ================================================================
     CREATE TASK MODAL
================================================================ --}}
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
            <div>
                <div class="tk-field-label">Title</div>
                <input type="text" name="title" required class="tk-field-input" placeholder="Task title…" style="width:100%">
            </div>
            <div>
                <div class="tk-field-label">Description</div>
                <textarea name="description" class="tk-field-textarea" placeholder="Optional description…"></textarea>
            </div>
            <div class="tk-fields">
                <div>
                    <div class="tk-field-label">Assign To</div>
                    <select name="assigned_to" class="tk-field-select">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div class="tk-field-label">Priority</div>
                    <select name="priority" class="tk-field-select">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div>
                    <div class="tk-field-label">Start Date</div>
                    <input type="date" name="start_date" class="tk-field-input">
                </div>
                <div>
                    <div class="tk-field-label">Due Date</div>
                    <input type="date" name="due_date" class="tk-field-input">
                </div>
            </div>
        </div>
        <div class="tk-create-footer">
            <button type="button" class="tk-btn-cancel" onclick="document.getElementById('createTaskModal').style.display='none'">Cancel</button>
            <button type="submit" class="tk-btn-save">Create Task</button>
        </div>
    </form>
</div>
</div>

{{-- ================================================================
     ADD COLUMN MODAL
================================================================ --}}
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
        <input type="hidden" name="color" id="add-color-value" value="gray">
        <div class="tk-create-body">
            <div>
                <div class="tk-field-label">Column Title</div>
                <input type="text" name="title" required class="tk-field-input" placeholder="e.g. In Review, Blocked…" style="width:100%">
            </div>
            <div>
                <div class="tk-field-label">Description <span style="font-weight:500;text-transform:none;letter-spacing:0;color:var(--soft);font-size:12px;">(optional)</span></div>
                <textarea name="description" class="tk-field-textarea" placeholder="What kind of tasks go here?" style="min-height:70px;"></textarea>
            </div>
            <div>
                <div class="tk-field-label" style="margin-bottom:.65rem;">Color</div>
                <div class="tk-color-swatches" id="add-swatches">
                    @foreach(['gray'=>'#94a3b8','blue'=>'#3b82f6','green'=>'#22c55e','yellow'=>'#eab308','red'=>'#ef4444','orange'=>'#f97316','purple'=>'#a855f7','pink'=>'#ec4899','teal'=>'#14b8a6','indigo'=>'#6366f1'] as $k => $c)
                    <div class="tk-color-swatch {{ $k === 'gray' ? 'selected' : '' }}"
                         data-color="{{ $k }}"
                         onclick="selectColor('add', '{{ $k }}')"
                         style="background:{{ $c }};">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="tk-create-footer">
            <button type="button" class="tk-btn-cancel" onclick="document.getElementById('addColumnModal').style.display='none'">Cancel</button>
            @if(auth()->user()->can_access('can_create_tasks'))
            <button type="submit" class="tk-btn-save">Add Column</button>
            @endif
        </div>
    </form>
</div>
</div>

{{-- ================================================================
     EDIT COLUMN MODAL
================================================================ --}}
<div class="tk-modal-overlay" id="editColumnModal">
<div class="tk-create-modal">
    <div class="tk-create-head">
        <div class="tk-create-title">Edit Column</div>
        <button class="tk-detail-close" onclick="document.getElementById('editColumnModal').style.display='none'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>
    <form id="editColumnForm" method="POST">
        @csrf @method('PUT')
        <input type="hidden" name="color" id="edit-color-value" value="gray">
        <div class="tk-create-body">
            <div>
                <div class="tk-field-label">Title</div>
                <input type="text" name="title" id="edit-col-title" required class="tk-field-input" style="width:100%">
            </div>
            <div>
                <div class="tk-field-label">Description <span style="font-weight:500;text-transform:none;letter-spacing:0;color:var(--soft);font-size:12px;">(optional)</span></div>
                <textarea name="description" id="edit-col-description" class="tk-field-textarea" placeholder="What kind of tasks go here?" style="min-height:70px;"></textarea>
            </div>
            <div>
                <div class="tk-field-label" style="margin-bottom:.65rem;">Color</div>
                <div class="tk-color-swatches" id="edit-swatches">
                    @foreach(['gray'=>'#94a3b8','blue'=>'#3b82f6','green'=>'#22c55e','yellow'=>'#eab308','red'=>'#ef4444','orange'=>'#f97316','purple'=>'#a855f7','pink'=>'#ec4899','teal'=>'#14b8a6','indigo'=>'#6366f1'] as $k => $c)
                    <div class="tk-color-swatch"
                         data-color="{{ $k }}"
                         onclick="selectColor('edit', '{{ $k }}')"
                         style="background:{{ $c }};">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="tk-create-footer">
            <button type="button" class="tk-btn-cancel" onclick="document.getElementById('editColumnModal').style.display='none'">Cancel</button>
            <button type="submit" class="tk-btn-save">Update Column</button>
        </div>
    </form>
</div>
</div>

{{-- ================================================================
     FULL HISTORY MODAL
================================================================ --}}
<div class="tk-modal-overlay" id="historyModal" style="z-index:9999;">
    <div class="tk-create-modal" style="max-width:500px;">
        <div class="tk-create-head">
            <div class="tk-create-title">Task History</div>
            <button class="tk-detail-close" onclick="document.getElementById('historyModal').style.display='none'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="tk-create-body" id="full-history-content" style="max-height:400px;overflow-y:auto;"></div>
    </div>
</div>

</x-app-layout>