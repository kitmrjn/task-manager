<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $board ? $board->name : 'No Boards Available' }}
        </h2>
        @if($board && $board->description)
            <p class="text-sm text-gray-500 mt-1">{{ $board->description }}</p>
        @endif
    </x-slot>

    <div class="py-8" x-data="{ isModalOpen: false, activeColumnId: null, isEditModalOpen: false, editTask: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex space-x-6 overflow-x-auto pb-4">
                
                @if($board)
                    @foreach($board->columns as $column)
                        <div class="bg-gray-100 rounded-lg w-80 min-w-[20rem] p-4 flex-shrink-0 shadow-sm flex flex-col max-h-[75vh]">
                            
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-gray-700">{{ $column->title }}</h3>
                                <span class="bg-gray-200 text-gray-600 text-xs font-bold px-2 py-1 rounded-full column-count">
                                    {{ $column->tasks->count() }}
                                </span>
                            </div>
                            
                            <div id="column-{{ $column->id }}" data-column-id="{{ $column->id }}" class="sortable-column space-y-3 overflow-y-auto flex-1 pr-2 min-h-[50px]">
                                @foreach($column->tasks as $task)
                                    <div data-task-id="{{ $task->id }}" 
                                         @click="editTask = {{ $task->toJson() }}; isEditModalOpen = true"
                                         class="bg-white p-4 rounded-md shadow border border-gray-200 cursor-grab hover:border-blue-400 transition-colors active:cursor-grabbing">
                                        
                                        <div class="mb-2">
                                            @php
                                                $priorityColor = match($task->priority) {
                                                    'high' => 'bg-red-100 text-red-800',
                                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                                    default => 'bg-green-100 text-green-800',
                                                };
                                            @endphp
                                            <span class="text-[10px] font-bold uppercase px-2 py-1 rounded {{ $priorityColor }}">
                                                {{ $task->priority }}
                                            </span>
                                        </div>

                                        <h4 class="font-semibold text-sm text-gray-800">{{ $task->title }}</h4>
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $task->description }}</p>
                                        
                                        <div class="mt-3 border-t pt-2 flex justify-between items-center">
                                            <div class="text-xs text-gray-500">
                                                Assignee: <span class="font-medium text-gray-700">{{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</span>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>

                            <button @click="isModalOpen = true; activeColumnId = {{ $column->id }}" type="button" class="mt-4 w-full py-2 flex justify-center items-center text-sm text-gray-500 hover:text-gray-800 hover:bg-gray-200 rounded transition">
                                + Add Task
                            </button>
                        </div>
                    @endforeach
                @else
                    <div class="bg-white p-6 rounded shadow w-full text-center text-gray-500">
                        No boards found. Please run the seeder!
                    </div>
                @endif

            </div>
            
            <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    
                    <div x-show="isModalOpen" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="isModalOpen = false"></div>

                    <div x-show="isModalOpen" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form action="/tasks" method="POST">
                            @csrf
                            <input type="hidden" name="board_column_id" :value="activeColumnId">

                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Create New Task</h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Task Title</label>
                                        <input type="text" name="title" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Assign To</label>
                                            <select name="assigned_to" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                <option value="">Unassigned</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Priority</label>
                                            <select name="priority" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                <option value="low">Low</option>
                                                <option value="medium" selected>Medium</option>
                                                <option value="high">High</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Save Task
                                </button>
                                <button type="button" @click="isModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div x-show="isEditModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    
                    <div x-show="isEditModalOpen" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="isEditModalOpen = false"></div>

                    <div x-show="isEditModalOpen" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        
                        <form x-bind:action="`/tasks/${editTask.id}`" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Task Details</h3>
                                    
                                    <button type="button" @click="if(confirm('Are you sure you want to delete this task?')) document.getElementById('delete-form-' + editTask.id).submit();" class="text-sm text-red-600 hover:text-red-900 font-medium">
                                        Delete Task
                                    </button>
                                </div>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Task Title</label>
                                        <input type="text" name="title" x-model="editTask.title" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea name="description" x-model="editTask.description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Assign To</label>
                                            <select name="assigned_to" x-model="editTask.assigned_to" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                <option value="">Unassigned</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Priority</label>
                                            <select name="priority" x-model="editTask.priority" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                <option value="low">Low</option>
                                                <option value="medium">Medium</option>
                                                <option value="high">High</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Save Changes
                                </button>
                                <button type="button" @click="isEditModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Close
                                </button>
                            </div>
                        </form>

                        <form x-bind:id="'delete-form-' + editTask.id" x-bind:action="`/tasks/${editTask.id}`" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Find all columns on the board
            const columns = document.querySelectorAll('.sortable-column');

            columns.forEach(column => {
                new Sortable(column, {
                    group: 'shared', // Allows dragging between different columns
                    animation: 150,  // Smooth sliding animation
                    ghostClass: 'bg-blue-50', // The placeholder style while dragging
                    
                    // This function fires the moment you drop a card
                    onEnd: function (evt) {
                        const itemEl = evt.item;  // The dragged HTML element
                        const toColumn = evt.to;  // The column it was dropped into
                        
                        const taskId = itemEl.getAttribute('data-task-id');
                        const newColumnId = toColumn.getAttribute('data-column-id');

                        // Send the background request to Laravel to update the database
                        fetch(`/tasks/${taskId}/move`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                board_column_id: newColumnId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                console.log('Database updated: ' + data.message);
                                updateColumnCounts(); // Update the number badges
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    },
                });
            });

            // Function to recount tasks in each column after a drop
            function updateColumnCounts() {
                document.querySelectorAll('.sortable-column').forEach(columnList => {
                    const count = columnList.querySelectorAll('[data-task-id]').length;
                    // Find the counter badge above this list and update it
                    const badge = columnList.parentElement.querySelector('.column-count');
                    if(badge) {
                        badge.textContent = count;
                    }
                });
            }
        });
    </script>
</x-app-layout>