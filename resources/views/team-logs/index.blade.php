<x-app-layout>
    @section('title', 'Team Time Logs')
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold" style="color: var(--c-navy);">Team Time Logs</h2>
                        
                        <a href="{{ route('team-logs.export', request()->all()) }}" 
                           class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium text-sm transition shadow-sm">
                            Export CSV
                        </a>
                    </div>

                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('team-logs.index') }}" class="mb-6 flex gap-4 items-end bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">From Date</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">To Date</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">Filter</button>
                            <a href="{{ route('team-logs.index') }}" class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm font-medium">Clear</a>
                        </div>
                    </form>

                    {{-- Logs Table --}}
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Breaks</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Net Hours</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->log_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $log->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->time_in ? $log->time_in->format('h:i A') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->time_out ? $log->time_out->format('h:i A') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $log->total_break_minutes }}m</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold {{ $log->total_hours >= 8 ? 'text-green-600' : 'text-amber-600' }}">
                                            {{ $log->total_hours }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No time logs found for the selected period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>