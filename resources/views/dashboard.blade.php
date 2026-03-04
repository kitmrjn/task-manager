<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; padding: 20px; }
        .board-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; color: #333; }
        .status { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .status.todo { background-color: #e2e3e5; color: #383d41; }
        .status.in-progress { background-color: #cce5ff; color: #004085; }
        .status.done { background-color: #d4edda; color: #155724; }
    </style>
</head>
<body>

    <div class="board-container">
        <h2>Team Task Board</h2>

        <div style="background: #f8f9fa; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
            <h3>Create a New Task</h3>
            <form action="/tasks" method="POST">
                @csrf
                <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                    <input type="text" name="title" placeholder="Task Title" required style="flex: 1; padding: 8px;">
                    <input type="text" name="description" placeholder="Description (Optional)" style="flex: 2; padding: 8px;">
                </div>
                
                <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                    <select name="creator_id" required style="padding: 8px;">
                        <option value="">Who is creating this?</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                        @endforeach
                    </select>

                    <select name="assigned_to" style="padding: 8px;">
                        <option value="">Assign to (Optional)</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                        @endforeach
                    </select>

                    <button type="submit" style="padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Add Task</button>
                </div>
            </form>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Task Name</th>
                    <th>Description</th>
                    <th>Assigned To</th>
                    <th>Created By</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td><strong>{{ $task->title }}</strong></td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</td>
                        <td>{{ $task->creator->name }}</td>
                        <td>
                            <span class="status {{ $task->status }}">
                                {{ str_replace('-', ' ', $task->status) }}
                            </span>
                        </td>

                        <td>
                            @if($task->status === 'todo')
                                <form action="/tasks/{{ $task->id }}/status" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in-progress">
                                    <button type="submit" style="background:#004085; color:white; border:none; padding:4px 8px; border-radius:3px; cursor:pointer;">Start</button>
                                </form>
                            @endif

                            @if($task->status === 'in-progress')
                                <form action="/tasks/{{ $task->id }}/status" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="done">
                                    <button type="submit" style="background:#155724; color:white; border:none; padding:4px 8px; border-radius:3px; cursor:pointer;">Complete</button>
                                </form>
                            @endif
                        </td>
                    </tr>

                    
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>