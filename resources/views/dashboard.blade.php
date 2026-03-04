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
        
        <table>
            <thead>
                <tr>
                    <th>Task Name</th>
                    <th>Description</th>
                    <th>Assigned To</th>
                    <th>Created By</th>
                    <th>Status</th>
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>