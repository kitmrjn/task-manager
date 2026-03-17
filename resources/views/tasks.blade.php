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
                {{-- ADD COLUMN DROPDOWN --}}
                <div class="relative">
                    <button @click="isAddingColumn = !isAddingColumn" 
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition shadow-sm">
                        + Add New List
                    </button>

                    <div x-show="isAddingColumn" @click.away="isAddingColumn = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-gray-200 z-[60] p-6 text-left"
                         style="display: none;">
                        
                        <form action="{{ route('columns.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="board_id" value="{{ $board->id }}">
                            <h3 class="text-lg font-black text-gray-900 mb-4">Create List</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Title</label>
                                    <input type="text" name="title" required autofocus
                                        class="w-full border-gray-200 rounded-xl focus:ring-blue-500 text-sm py-2 px-4 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Description</label>
                                    <textarea name="description" rows="2" 
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
        </div>
    </x-slot>

    {{-- MAIN DATA SCOPE --}}
    <div class="py-8" x-data="{ 
        isModalOpen: false, 
        activeColumnId: null, 
        isEditModalOpen: false, 
        isEditListModalOpen: false, 
        isHistoryOpen: false,
        editTask: { checklist_items: [], members: [] }, 
        editList: {} 
    }">
        <div class="max-w-[98%] mx-auto px-4 sm:px-6 lg:px-8">
            <div id="board-container" class="flex space-x-8 overflow-x-auto pb-10 items-start">
                @if($board)
                    @foreach($board->columns as $column)
                        @php
                            $headerColor = match($column->color) {
                                'blue' => 'bg-blue-400',
                                'green' => 'bg-green-400',
                                'yellow' => 'bg-yellow-400',
                                'red' => 'bg-red-400',
                                default => 'bg-gray-500',
                            };
                        @endphp

                        <div id="column-wrapper-{{ $column->id }}" class="column-wrapper bg-gray-100 rounded-2xl w-[24rem] min-w-[24rem] flex-shrink-0 shadow-sm flex flex-col max-h-[82vh] overflow-hidden border border-gray-200">
                            
                            {{-- COLUMN HEADER --}}
                            <div class="p-5 {{ $headerColor }}" x-data="{ colMenu: false }">
                                <div class="flex justify-between items-start text-white">
                                    <div class="overflow-hidden flex-1">
                                        <h3 class="font-black text-lg tracking-tight truncate">{{ $column->title }}</h3>
                                        @if($column->description)
                                            <p class="text-[11px] text-white/80 font-medium leading-tight mt-1 line-clamp-2">{{ $column->description }}</p>
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
                                                <button type="button" @click="moveColumn({{ $column->id }}, 'left')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center group/move">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400 group-hover/move:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                                                    Move Left
                                                </button>
                                                <button type="button" @click="moveColumn({{ $column->id }}, 'right')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center group/move">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400 group-hover/move:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
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
                                    <div data-task-id="{{ $task->id }}" 
                                         @click="editTask = {{ $task->load(['checklistItems', 'members', 'activities.user'])->toJson() }}; isEditModalOpen = true"
                                         class="task-card p-6 rounded-2xl shadow-sm border transition-all cursor-grab active:cursor-grabbing min-h-[140px] flex flex-col group relative 
                                         {{ $task->is_completed ? 'bg-green-50/50 border-green-200 opacity-80' : 'bg-white border-gray-200 hover:border-blue-400' }}">
                                        
                                        <div class="mb-4 flex items-center justify-between">
                                            <div class="flex flex-wrap gap-2 items-center">
                                                {{-- Priority Badge --}}
                                                @php
                                                    $priorityColor = match($task->priority) {
                                                        'high' => 'bg-red-50 text-red-700 border-red-100',
                                                        'medium' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                                        default => 'bg-green-50 text-green-700 border-green-100',
                                                    };
                                                @endphp
                                                <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-md border {{ $priorityColor }}">
                                                    {{ $task->priority }}
                                                </span>
                                                
                                                {{-- Date Range Badge --}}
                                                @if($task->due_date)
                                                    <span class="text-[9px] font-bold text-gray-400 flex items-center bg-gray-50 px-2 py-1 rounded-md border border-gray-100">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                        
                                                        @if($task->start_date)
                                                            {{ \Carbon\Carbon::parse($task->start_date)->format('M d') }} - 
                                                        @endif
                                                        {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                                                    </span>
                                                @endif

                                                {{-- TASK COMPLETED BADGE --}}
                                                @if($task->is_completed)
                                                    <span class="bg-green-100 text-green-700 text-[9px] font-black uppercase px-2 py-1 rounded-md border border-green-200 flex items-center shadow-sm">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                                        Task Completed
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <h4 class="font-extrabold text-base text-gray-900 leading-tight mb-2 group-hover:text-blue-600 transition-colors">{{ $task->title }}</h4>
                                        <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed flex-1">{{ $task->description }}</p>
                                        
                                        {{-- Checklist Badge --}}
                                        @if($task->checklistItems->count() > 0)
                                            <div class="mt-3 flex items-center text-[10px] font-bold text-gray-400">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                {{ $task->checklistItems->where('is_completed', true)->count() }}/{{ $task->checklistItems->count() }} Items
                                            </div>
                                        @endif

                                        {{-- CARD FOOTER --}}
                                        <div class="mt-5 pt-4 border-t border-gray-50 flex items-center justify-between">
                                            <div class="flex flex-col">
                                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Lead</span>
                                                <span class="text-[11px] font-bold text-gray-900 truncate max-w-[100px]">
                                                    {{ $task->assignee->name ?? 'Unassigned' }}
                                                </span>
                                            </div>

                                            <div class="flex -space-x-2 overflow-hidden">
                                                @foreach($task->members as $member)
                                                    <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-blue-500 flex items-center justify-center shadow-sm" title="{{ $member->name }}">
                                                        <span class="text-[10px] font-black text-white uppercase">{{ substr($member->name, 0, 1) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
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
                                <div><label class="text-xs font-black text-gray-400 uppercase mb-2 block">Assign To</label>
                                    <select name="assigned_to" class="w-full border-gray-200 rounded-xl py-3 px-3 shadow-sm">
                                        <option value="">Unassigned</option>
                                        @foreach($users as $user) <option value="{{ $user->id }}">{{ $user->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div><label class="text-xs font-black text-gray-400 uppercase mb-2 block">Priority</label>
                                    <select name="priority" class="w-full border-gray-200 rounded-xl py-3 px-3 shadow-sm">
                                        <option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mt-10 flex justify-end items-center space-x-4"><button type="button" @click="isModalOpen = false" class="text-sm font-bold text-gray-400">Cancel</button><button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl text-sm font-black uppercase hover:bg-blue-700 shadow-lg transition">Create Task</button></div>
                    </form>
                </div>
            </div>
        </div>

{{-- EDIT TASK MODAL (TRELLO-STYLE SIDEBAR LAYOUT) --}}
<div x-show="isEditModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div x-show="isEditModalOpen" class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" @click="isEditModalOpen = false"></div>
        
        {{-- Main Modal Container with Max Height --}}
        <div x-show="isEditModalOpen" 
             class="bg-white rounded-3xl text-left shadow-2xl transform transition-all sm:max-w-6xl sm:w-full z-[110] overflow-hidden flex flex-col max-h-[90vh]">
            
            <form x-bind:action="`/tasks/${editTask.id}`" method="POST" class="flex flex-col flex-1 min-h-0">
                @csrf @method('PUT')
                <input type="hidden" name="is_completed" :value="editTask.is_completed ? 1 : 0">
                
                {{-- SCROLLABLE BODY SECTION --}}
                <div class="flex flex-1 min-h-0 overflow-hidden">
                    
                    {{-- LEFT SIDE: MAIN CONTENT (Scrollable) --}}
                    <div class="flex-1 p-10 overflow-y-auto space-y-8 custom-scrollbar">
                        {{-- Header & Completion Toggle --}}
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-black text-gray-900" x-text="editTask.title || 'Task Details'"></h3>
                                <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest">In List: <span class="text-blue-500" x-text="editTask.column ? editTask.column.title : ''"></span></p>
                            </div>
                            <button type="button" 
                                    @click="
                                        editTask.is_completed = !editTask.is_completed;
                                        fetch(`/tasks/${editTask.id}/toggle-complete`, {
                                            method: 'PATCH',
                                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                        });
                                    "
                                    :class="editTask.is_completed ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-400'"
                                    class="flex items-center px-4 py-2 rounded-xl transition-all duration-300 shadow-sm">
                                <span class="text-[10px] font-black uppercase tracking-widest" x-text="editTask.is_completed ? '✓ Completed' : 'Mark as Complete'"></span>
                            </button>
                        </div>

                        {{-- Title & Description --}}
                        <div>
                            <label class="text-xs font-black text-gray-400 uppercase mb-3 block flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                                Description
                            </label>
                            <input type="text" name="title" x-model="editTask.title" placeholder="Task Title" class="w-full border-gray-200 rounded-xl py-3 px-4 mb-4 font-bold focus:ring-blue-500 shadow-sm">
                            <textarea name="description" x-model="editTask.description" rows="4" placeholder="Add a more detailed description..." class="w-full border-gray-200 rounded-xl py-3 px-4 shadow-sm focus:ring-blue-500 text-sm"></textarea>
                        </div>

                        {{-- Checklist --}}
                        <div class="pt-4">
                            <h4 class="text-sm font-black text-gray-900 flex items-center mb-4">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                Checklist
                            </h4>
                            
                            <template x-if="editTask.checklist_items && editTask.checklist_items.length > 0">
                                <div class="flex items-center space-x-3 mb-6">
                                    <span class="text-[10px] font-black text-gray-400 w-8" x-text="Math.round((editTask.checklist_items.filter(i => i.is_completed).length / editTask.checklist_items.length) * 100) + '%'"></span>
                                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-green-500 transition-all duration-500" :style="'width: ' + (Math.round((editTask.checklist_items.filter(i => i.is_completed).length / editTask.checklist_items.length) * 100)) + '%'"></div>
                                    </div>
                                </div>
                            </template>

                            <div class="space-y-3">
                                <template x-for="item in editTask.checklist_items" :key="item.id">
                                    <div class="flex items-center justify-between group bg-gray-50 p-3 rounded-xl border border-transparent hover:border-gray-200 transition-all">
                                        <div class="flex items-center flex-1">
                                            <input type="checkbox" :checked="item.is_completed" @change="item.is_completed = $el.checked; fetch(`/checklist-items/${item.id}/toggle`, { method: 'PATCH', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' } });" class="rounded text-blue-600 focus:ring-blue-500 mr-3 border-gray-300 shadow-sm">
                                            <span :class="item.is_completed ? 'line-through text-gray-400' : 'text-gray-700'" class="text-sm font-medium" x-text="item.title"></span>
                                        </div>
                                        <button type="button" @click="if(confirm('Delete?')) { fetch(`/checklist-items/${item.id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => { editTask.checklist_items = editTask.checklist_items.filter(i => i.id !== item.id); }); }" class="text-gray-300 hover:text-red-500 transition-colors p-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                </template>
                                <input type="text" placeholder="Add an item..." class="w-full text-sm border-dashed border-2 border-gray-200 rounded-xl px-4 py-3 mt-2 focus:border-blue-500 focus:ring-0 transition-all"
                                       @keydown.enter.prevent="if ($el.value.trim() === '') return; fetch(`/tasks/${editTask.id}/checklist`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ title: $el.value }) }).then(res => res.json()).then(newItem => { if (!editTask.checklist_items) editTask.checklist_items = []; editTask.checklist_items.push(newItem); $el.value = ''; })">
                            </div>
                        </div>
                    </div>
                    {{-- RIGHT SIDEBAR: SETTINGS & ACTIVITY (Scrollable) --}}
                        <div class="w-80 bg-gray-50 border-l border-gray-100 p-8 overflow-y-auto flex flex-col space-y-6">
                            {{-- Lead Assignee --}}
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Lead Assignee</label>
                                <select name="assigned_to" x-model="editTask.assigned_to" class="w-full border-gray-200 rounded-xl py-2 px-3 text-sm focus:ring-blue-500 shadow-sm">
                                    <option value="">Unassigned</option>
                                    @foreach($users as $user) <option value="{{ $user->id }}">{{ $user->name }}</option> @endforeach
                                </select>
                            </div>

                            {{-- Start Date Section --}}
                            <div x-data="{ showStartDate: false }" 
                                x-init="$watch('isEditModalOpen', value => { if(value && editTask.start_date) showStartDate = true; else if(value) showStartDate = false; })">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Start Date</label>
                                    <button type="button" @click="showStartDate = !showStartDate; if(!showStartDate) editTask.start_date = null" class="text-[10px] font-bold text-blue-600 hover:text-blue-800">
                                        <span x-text="showStartDate ? '- Remove' : '+ Add Start Date'"></span>
                                    </button>
                                </div>
                                <div x-show="showStartDate" x-transition class="mt-1">
                                    <input type="date" name="start_date" x-model="editTask.start_date" class="w-full border-gray-200 rounded-xl py-2 px-3 text-sm focus:ring-blue-500 shadow-sm">
                                </div>
                            </div>

                            {{-- Due Date --}}
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Due Date</label>
                                <input type="date" name="due_date" x-model="editTask.due_date" class="w-full border-gray-200 rounded-xl py-2 px-3 text-sm focus:ring-blue-500 shadow-sm">
                            </div>

                            {{-- Priority --}}
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Priority</label>
                                <select name="priority" x-model="editTask.priority" class="w-full border-gray-200 rounded-xl py-2 px-3 text-sm focus:ring-blue-500 shadow-sm">
                                    <option value="low">Low</option><option value="medium">Medium</option><option value="high">High</option>
                                </select>
                            </div>

                            {{-- Collaborators --}}
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Collaborators</label>
                                <div class="space-y-1 max-h-32 overflow-y-auto pr-2 custom-scrollbar">
                                    @foreach($users as $user)
                                        <label class="flex items-center space-x-2 cursor-pointer group py-1">
                                            <input type="checkbox" value="{{ $user->id }}"
                                                :checked="editTask.members && editTask.members.some(m => m.id === {{ $user->id }})"
                                                @change="toggleMember({{ $user->id }}, editTask.id); if ($el.checked) { editTask.members.push({id: {{ $user->id }}, name: '{{ $user->name }}'}); } else { editTask.members = editTask.members.filter(m => m.id !== {{ $user->id }}); }"
                                                class="rounded-sm text-blue-600 focus:ring-0 border-gray-300 w-3 h-3">
                                            <span class="text-[11px] font-bold text-gray-500 group-hover:text-gray-900 transition-colors">{{ $user->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- ACTIVITY FEED SUMMARY --}}
                            <div class="mt-4 pt-6 border-t border-gray-200">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Activity</h4>
                                    <button type="button" @click="isHistoryOpen = true" class="text-[10px] font-bold text-blue-600 hover:text-blue-800">View History</button>
                                </div>
                                <div class="space-y-3">
                                    <template x-for="activity in editTask.activities ? editTask.activities.slice(0, 2) : []" :key="activity.id">
                                        <div class="flex items-start space-x-2">
                                            <div :class="activity.user.avatar_color || 'bg-gray-400'" class="w-5 h-5 rounded-full flex-shrink-0 flex items-center justify-center text-[8px] font-black text-white uppercase shadow-sm">
                                                <span x-text="activity.user.name.substring(0,1)"></span>
                                            </div>
                                            <p class="text-[10px] leading-tight text-gray-600 truncate">
                                                <span class="font-bold text-gray-900" x-text="activity.user.name"></span>
                                                <span x-text="activity.description"></span>
                                            </p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div> {{-- END RIGHT SIDEBAR --}}
                    </div> {{-- END FLEX BODY --}}

                    {{-- FIXED FOOTER: NEATLY PINNED AT THE BOTTOM --}}
                    <div class="p-6 border-t border-gray-100 bg-white flex justify-between items-center bg-gray-50/30">
                        <button type="button" @click="if(confirm('Delete this task?')) document.getElementById('delete-form-' + editTask.id).submit();" 
                                class="text-[10px] font-black text-red-500 uppercase hover:text-red-700 transition-colors">
                            Delete Task
                        </button>
                        
                        <div class="flex items-center space-x-4">
                            <button type="button" @click="isEditModalOpen = false" class="text-sm font-bold text-gray-400 hover:text-gray-600">Cancel</button>
                            <button type="submit" class="bg-blue-600 text-white px-10 py-3 rounded-2xl text-xs font-black uppercase hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all active:scale-95">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
                <form x-bind:id="'delete-form-' + editTask.id" x-bind:action="`/tasks/${editTask.id}`" method="POST" class="hidden">@csrf @method('DELETE')</form>
            </div>
        </div>
    </div>

        {{-- 3. FULL HISTORY POP-UP (Standalone Modal) --}}
        <div x-show="isHistoryOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;" 
             class="fixed inset-0 z-[150] overflow-y-auto">
            
            <div class="flex items-center justify-center min-h-screen p-4">
                {{-- Glass Backdrop --}}
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="isHistoryOpen = false"></div>

                <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md z-[160] overflow-hidden relative transform transition-all"
                     x-show="isHistoryOpen"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                    
                    {{-- Header --}}
                    <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <div>
                            <h3 class="text-xl font-black text-gray-900 tracking-tight">Activity Log</h3>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1" x-text="editTask.title"></p>
                        </div>
                        <button type="button" @click="isHistoryOpen = false" class="bg-white p-2 rounded-full shadow-sm border border-gray-100 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    {{-- Scrollable List --}}
                    <div class="p-8 max-h-[50vh] overflow-y-auto space-y-6 scrollbar-hide">
                        <template x-for="activity in editTask.activities" :key="activity.id">
                            <div class="flex items-start space-x-4 group">
                                <div :class="activity.user.avatar_color || 'bg-blue-500'" class="w-9 h-9 rounded-2xl flex-shrink-0 flex items-center justify-center text-xs font-black text-white uppercase shadow-md transition-transform group-hover:scale-110">
                                    <span x-text="activity.user.name.substring(0,1)"></span>
                                </div>
                                <div class="flex-1 border-b border-gray-50 pb-4">
                                    <p class="text-sm text-gray-700 leading-snug">
                                        <span class="font-black text-gray-900" x-text="activity.user.name"></span>
                                        <span class="text-gray-500 ml-1" x-text="activity.description"></span>
                                    </p>
                                    <div class="flex items-center mt-2 space-x-2">
                                        <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <p class="text-[10px] text-gray-400 font-bold tracking-wide" x-text="new Date(activity.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute:'2-digit' })"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <div class="p-6 bg-gray-50 flex justify-center">
                        <button type="button" @click="isHistoryOpen = false" class="bg-white px-8 py-3 rounded-2xl shadow-sm border border-gray-200 text-xs font-black uppercase text-gray-500 hover:text-blue-600 transition-all">Close History</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- EDIT LIST MODAL --}}
        <div x-show="isEditListModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="isEditListModalOpen" class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" @click="isEditListModalOpen = false"></div>
                <div x-show="isEditListModalOpen" class="bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-2xl sm:w-full z-[110]">
                    <form x-bind:action="`/columns/${editList.id}`" method="POST" class="p-8">
                        @csrf @method('PUT')
                        <h3 class="text-2xl font-black text-gray-900 mb-8">Edit List</h3>
                        <div class="space-y-6">
                            <div><label class="text-xs font-black text-gray-400 uppercase mb-2 block">Title</label><input type="text" name="title" x-model="editList.title" required class="w-full border-gray-200 rounded-xl py-3 px-4 shadow-sm"></div>
                            <div><label class="text-xs font-black text-gray-400 uppercase mb-2 block">Description</label><textarea name="description" x-model="editList.description" rows="3" class="w-full border-gray-200 rounded-xl py-3 px-4 shadow-sm"></textarea></div>
                            <div><label class="text-xs font-black text-gray-400 uppercase mb-2 block">Header Color</label>
                                <div class="grid grid-cols-5 gap-2">
                                    @foreach(['gray', 'blue', 'green', 'yellow', 'red'] as $c)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="color" value="{{ $c }}" class="hidden" x-model="editList.color">
                                            <div :class="editList.color === '{{ $c }}' ? 'ring-2 ring-offset-2 ring-black' : ''" class="w-full h-8 rounded-lg bg-{{ $c }}-400 transition shadow-inner"></div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="mt-10 flex justify-end items-center space-x-4"><button type="button" @click="isEditListModalOpen = false" class="text-sm font-bold text-gray-400">Cancel</button><button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl text-sm font-black uppercase hover:bg-blue-700 shadow-lg">Save Changes</button></div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const columns = document.querySelectorAll('.sortable-column');
            columns.forEach(column => {
                new Sortable(column, {
                    group: 'shared', animation: 250, ghostClass: 'bg-blue-50',
                    onEnd: function (evt) { saveMove(evt.item.getAttribute('data-task-id'), evt.to.getAttribute('data-column-id')); },
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
            
            window.toggleMember = function(userId, taskId) {
                fetch(`/tasks/${taskId}/members/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ user_id: userId })
                });
            };
        });
    </script>
    
</x-app-layout>