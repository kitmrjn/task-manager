<x-app-layout>
<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome back, ') . Auth::user()->name }}
        </h2>
    </div>
</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">Total Tasks</p>
                    <p class="text-3xl font-bold">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">My Tasks</p>
                    <p class="text-3xl font-bold">{{ $stats['my_tasks'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">High Priority</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['high_priority'] }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">My Recent Tasks</h3>
                <div class="space-y-4">
                    @forelse($myTasks as $task)
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-md border border-gray-200">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $task->title }}</p>
                                <p class="text-xs text-gray-500 italic">Status: {{ $task->column->title }}</p>
                            </div>
                            <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:underline text-sm">View Board</a>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No tasks assigned to you yet.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>