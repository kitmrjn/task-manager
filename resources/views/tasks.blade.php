<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" x-data="{ isAddingColumn: false }">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $board ? $board->name : 'No Boards Available' }}
                </h2>
                @if($board && $board->description)
                    <p class="text-sm text-gray-500 mt-1">{{ $board->description }}</p>
                @endif
            </div>

            <div class="flex items-center space-x-4">
                {{-- TRELLO-STYLE ADD COLUMN DROPDOWN --}}
                <div class="relative">
                    <button @click="isAddingColumn = !isAddingColumn" 
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition shadow-sm">
                        + Add New List
                    </button>

                    <div x-show="isAddingColumn" @click.away="isAddingColumn = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-96 bg-white rounded-2xl shadow-2xl border border-gray-200 z-[60] p-6 text-left"
                         style="display: none;">
                        
                        <form action="{{ route('columns.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="board_id" value="{{ $board->id }}">
                            <h3 class="text-lg font-black text-gray-900 mb-4">Create List</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Title</label>
                                    <input type="text" name="title" placeholder="e.g. Done" required autofocus
                                        class="w-full border-gray-200 rounded-xl focus:ring-blue-500 text-sm py-2 px-4 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Description</label>
                                    <textarea name="description" rows="2" placeholder="List purpose..." 
                                        class="w-full border-gray-200 rounded-xl focus:ring-blue-500 py-2 px-4 text-sm shadow-sm"></textarea>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Header Color</label>
                                    <div class="grid grid-cols-5 gap-2" x-data="{ selectedColor: 'gray' }">
                                        @foreach(['gray' => 'bg-gray-400', 'blue' => 'bg-blue-400', 'green' => 'bg-green-400', 'yellow' => 'bg-yellow-400', 'red' => 'bg-red-400'] as $key => $bgClass)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="color" value="{{ $key }}" class="hidden" @click="selectedColor = '{{ $key }}'" {{ $key == 'gray' ? 'checked' : '' }}>
                                                <div :class="selectedColor === '{{ $key }}' ? 'ring-2 ring-offset-2 ring-black' : ''" 
                                                     class="w-full h-8 rounded-lg {{ $bgClass }} transition shadow-inner"></div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end items-center space-x-3">
                                <button type="button" @click="isAddingColumn = false" class="text-sm font-bold text-gray-400">Cancel</button>
                                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl text-xs font-black uppercase hover:bg-blue-700 shadow-md">Add List</button>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </x-slot>

    {{-- MAIN DATA SCOPE --}}
    <div class="py-8" x-data="{ 
        isModalOpen: false, 
        activeColumnId: null, 
        isEditModalOpen: false, 
        isEditListModalOpen: false, 
        editTask: {}, 
        editList: {} 
    }">
        <div class="max-w-[98%] mx-auto px-4 sm:px-6 lg:px-8">
            <div id="board-container" class="flex space-x-8 overflow-x-auto pb-10 items-start">
                @if($board)
                    @foreach($board->columns as $column)
                        {{-- MAP DATABASE COLOR TO TAILWIND CLASS --}}
                        @php
                            $headerColor = match($column->color) {
                                'blue' => 'bg-blue-400',
                                'green' => 'bg-green-400',
                                'yellow' => 'bg-yellow-400',
                                'red' => 'bg-red-400',
                                default => 'bg-gray-500',
                            };
                        @endphp

                        <div id="column-wrapper-{{ $column->id }}" 
                             class="column-wrapper bg-gray-100 rounded-2xl w-[24rem] min-w-[24rem] flex-shrink-0 shadow-sm flex flex-col max-h-[82vh] overflow-hidden border border-gray-200">
                            
{{-- COLUMN HEADER --}}
<div class="p-5 {{ $headerColor }}" x-data="{ colMenu: false }">
    <div class="flex justify-between items-start text-white">
        <div class="overflow-hidden flex-1">
            <h3 class="font-black text-lg tracking-tight truncate">{{ $column->title }}</h3>
            
            {{-- THIS PART SHOWS THE DESCRIPTION --}}
            @if($column->description)
                <p class="text-[11px] text-white/80 font-medium leading-tight mt-1 line-clamp-2">
                    {{ $column->description }}
                </p>
            @else
                {{-- Optional: Show nothing or a tiny spacer --}}
                <div class="h-2"></div>
            @endif
        </div>
                                    <div class="flex items-center space-x-2 ml-2">
                                        <span class="bg-black/10 px-3 py-1 rounded-full text-[10px] font-black backdrop-blur-md border border-white/20 column-count">
                                            {{ $column->tasks->count() }}
                                        </span>
                                        <div class="relative">
                                            <button @click="colMenu = !colMenu" class="text-white/70 hover:text-white transition">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
                                            </button>
                                            <div x-show="colMenu" @click.away="colMenu = false" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl z-50 py-2 text-left" style="display: none;">
                                                <button type="button" @click="editList = {{ $column->toJson() }}; isEditListModalOpen = true; colMenu = false" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                    Edit List Details
                                                </button>
{{-- Move Left Button --}}
<button type="button" @click="moveColumn({{ $column->id }}, 'left')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center group/move">
    <svg class="w-4 h-4 mr-2 text-gray-400 group-hover/move:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    Move Left
</button>

{{-- Move Right Button --}}
<button type="button" @click="moveColumn({{ $column->id }}, 'right')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center group/move">
    <svg class="w-4 h-4 mr-2 text-gray-400 group-hover/move:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
    Move Right
</button>
                                                <div class="border-t border-gray-100 my-1"></div>
                                                <form action="{{ route('columns.destroy', $column->id) }}" method="POST" onsubmit="return confirm('Delete list?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 font-bold">Delete List</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- TASKS AREA --}}
                            <div id="column-{{ $column->id }}" data-column-id="{{ $column->id }}" class="sortable-column space-y-5 overflow-y-auto flex-1 p-5 pr-4 min-h-[120px]">
                                @foreach($column->tasks as $task)
                                    <div data-task-id="{{ $task->id }}" @click="editTask = {{ $task->toJson() }}; isEditModalOpen = true"
                                         class="task-card bg-white p-6 rounded-2xl shadow-sm border border-gray-200 cursor-grab hover:border-blue-400 transition-all active:cursor-grabbing min-h-[140px] flex flex-col group">
                                        <div class="mb-4">
                                            @php
                                                $priorityColor = match($task->priority) {
                                                    'high' => 'bg-red-50 text-red-700 border-red-100',
                                                    'medium' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                                    default => 'bg-green-50 text-green-700 border-green-100',
                                                };
                                            @endphp
                                            <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-md border {{ $priorityColor }}">{{ $task->priority }}</span>
                                        </div>
                                        <h4 class="font-extrabold text-base text-gray-900 leading-tight mb-2 group-hover:text-blue-600 transition-colors">{{ $task->title }}</h4>
                                        <p class="text-sm text-gray-500 line-clamp-3 leading-relaxed flex-1">{{ $task->description }}</p>
                                        <div class="mt-5 pt-4 border-t border-gray-50 text-[11px] font-black text-gray-400 uppercase tracking-widest">
                                            Assignee: <span class="text-gray-800 ml-1">{{ $task->assignee->name ?? 'Unassigned' }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="p-4 bg-gray-100 border-t border-gray-200/50">
                                <button @click="isModalOpen = true; activeColumnId = {{ $column->id }}" type="button" class="w-full py-3 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-blue-600 hover:bg-white rounded-xl transition shadow-sm">+ Add Task</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- --- MODALS --- --}}
        
        {{-- CREATE TASK MODAL --}}
        <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="isModalOpen" class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" @click="isModalOpen = false"></div>
                <div x-show="isModalOpen" class="bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg sm:w-full z-[110]">
                    <form action="/tasks" method="POST" class="p-8">
                        @csrf
                        <input type="hidden" name="board_column_id" :value="activeColumnId">
                        <h3 class="text-2xl font-black text-gray-900 mb-8">New Task</h3>
                        <div class="space-y-6">
                            <div><label class="text-xs font-black text-gray-400 uppercase mb-2 block">Title</label><input type="text" name="title" required class="w-full border-gray-200 rounded-xl py-3 px-4 shadow-sm"></div>
                            <div><label class="text-xs font-black text-gray-400 uppercase mb-2 block">Description</label><textarea name="description" rows="4" class="w-full border-gray-200 rounded-xl py-3 px-4 shadow-sm"></textarea></div>
                            <div class="grid grid-cols-2 gap-6">
                                <div><label class="text-xs font-black text-gray-400 uppercase mb-2 block">Assign To</label><select name="assigned_to" class="w-full border-gray-200 rounded-xl py-3 px-3 shadow-sm">@foreach($users as $user) <option value="{{ $user->id }}">{{ $user->name }}</option> @endforeach</select></div>
                                <div><label class="text-xs font-black text-gray-400 uppercase mb-2 block">Priority</label><select name="priority" class="w-full border-gray-200 rounded-xl py-3 px-3 shadow-sm"><option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option></select></div>
                            </div>
                        </div>
                        <div class="mt-10 flex justify-end items-center space-x-4"><button type="button" @click="isModalOpen = false" class="text-sm font-bold text-gray-400">Cancel</button><button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl text-sm font-black uppercase hover:bg-blue-700 shadow-lg transition">Create Task</button></div>
                    </form>
                </div>
            </div>
        </div>

        {{-- EDIT TASK MODAL --}}
        <div x-show="isEditModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="isEditModalOpen" class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" @click="isEditModalOpen = false"></div>
                <div x-show="isEditModalOpen" class="bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg sm:w-full z-[110]">
                    <form x-bind:action="`/tasks/${editTask.id}`" method="POST" class="p-8">
                        @csrf @method('PUT')
                        <div class="flex justify-between items-center mb-8"><h3 class="text-2xl font-black text-gray-900">Task Details</h3><button type="button" @click="if(confirm('Delete?')) document.getElementById('delete-form-' + editTask.id).submit();" class="text-xs font-black text-red-500 uppercase">Delete</button></div>
                        <div class="space-y-6">
                            <div><label class="text-xs font-black text-gray-400 uppercase mb-2 block">Title</label><input type="text" name="title" x-model="editTask.title" required class="w-full border-gray-200 rounded-xl py-3 px-3 shadow-sm"></div>
                            <div><label class="text-xs font-black text-gray-400 uppercase mb-2 block">Description</label><textarea name="description" x-model="editTask.description" rows="4" class="w-full border-gray-200 rounded-xl py-3 px-3 shadow-sm"></textarea></div>
                            <div class="grid grid-cols-2 gap-6">
                                <div><label class="text-xs font-black text-gray-400 uppercase mb-2 block">Assign To</label><select name="assigned_to" x-model="editTask.assigned_to" class="w-full border-gray-200 rounded-xl py-3 px-3">@foreach($users as $user) <option value="{{ $user->id }}">{{ $user->name }}</option> @endforeach</select></div>
                                <div><label class="text-xs font-black text-gray-400 uppercase mb-2 block">Priority</label><select name="priority" x-model="editTask.priority" class="w-full border-gray-200 rounded-xl py-3 px-3"><option value="low">Low</option><option value="medium">Medium</option><option value="high">High</option></select></div>
                            </div>
                        </div>
                        <div class="mt-10 flex justify-end items-center space-x-4"><button type="button" @click="isEditModalOpen = false" class="text-sm font-bold text-gray-400">Close</button><button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl text-sm font-black uppercase hover:bg-blue-700 shadow-lg">Save</button></div>
                    </form>
                    <form x-bind:id="'delete-form-' + editTask.id" x-bind:action="`/tasks/${editTask.id}`" method="POST" class="hidden">@csrf @method('DELETE')</form>
                </div>
            </div>
        </div>

        {{-- EDIT LIST MODAL --}}
        <div x-show="isEditListModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="isEditListModalOpen" class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" @click="isEditListModalOpen = false"></div>
                <div x-show="isEditListModalOpen" class="bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg sm:w-full z-[110]">
                    <form x-bind:action="`/columns/${editList.id}`" method="POST" class="p-8">
                        @csrf @method('PUT')
                        <h3 class="text-2xl font-black text-gray-900 mb-8">Edit List</h3>
                        <div class="space-y-6">
                            <div>
                                <label class="text-xs font-black text-gray-400 uppercase mb-2 block">Title</label>
                                <input type="text" name="title" x-model="editList.title" required class="w-full border-gray-200 rounded-xl py-3 px-4 shadow-sm">
                            </div>
                            <div>
                                <label class="text-xs font-black text-gray-400 uppercase mb-2 block">Description</label>
                                <textarea name="description" x-model="editList.description" rows="3" class="w-full border-gray-200 rounded-xl py-3 px-4 shadow-sm"></textarea>
                            </div>
                            <div>
                                <label class="text-xs font-black text-gray-400 uppercase mb-2 block">Header Color</label>
                                <div class="grid grid-cols-5 gap-2">
                                    @foreach(['gray', 'blue', 'green', 'yellow', 'red'] as $c)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="color" value="{{ $c }}" class="hidden" x-model="editList.color">
                                            <div :class="editList.color === '{{ $c }}' ? 'ring-2 ring-offset-2 ring-black' : ''" 
                                                 class="w-full h-8 rounded-lg bg-{{ $c }}-400 transition shadow-inner"></div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="mt-10 flex justify-end items-center space-x-4">
                            <button type="button" @click="isEditListModalOpen = false" class="text-sm font-bold text-gray-400">Cancel</button>
                            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl text-sm font-black uppercase hover:bg-blue-700 shadow-lg">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const columns = document.querySelectorAll('.sortable-column');
            columns.forEach(column => {
                new Sortable(column, {
                    group: 'shared', animation: 250, ghostClass: 'bg-blue-50',
                    onEnd: function (evt) {
                        saveMove(evt.item.getAttribute('data-task-id'), evt.to.getAttribute('data-column-id'));
                    },
                });
            });

            window.moveColumn = function(columnId, direction) {
                const wrapper = document.getElementById(`column-wrapper-${columnId}`);
                if (!wrapper) return;
                if (direction === 'left' && wrapper.previousElementSibling) wrapper.parentNode.insertBefore(wrapper, wrapper.previousElementSibling);
                else if (direction === 'right' && wrapper.nextElementSibling) wrapper.parentNode.insertBefore(wrapper.nextElementSibling, wrapper);

                fetch(`/columns/${columnId}/move`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: JSON.stringify({ direction: direction })
                });
            };

            function saveMove(taskId, newColumnId) {
                fetch(`/tasks/${taskId}/move`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: JSON.stringify({ board_column_id: newColumnId })
                }).then(res => res.json()).then(data => { if(data.success) updateColumnCounts(); });
            }

            function updateColumnCounts() {
                document.querySelectorAll('.sortable-column').forEach(list => {
                    const count = list.querySelectorAll('.task-card').length;
                    const badge = list.closest('.column-wrapper').querySelector('.column-count');
                    if(badge) badge.textContent = count;
                });
            }
        });
    </script>
</x-app-layout>