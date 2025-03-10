<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <div class="max-w-3xl mx-auto mt-10">
        <h2 class="text-3xl font-bold text-center mb-5 text-gray-800">To-Do Dashboard</h2>

        <!-- Add New Task -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('todos.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <input type="text" name="title" placeholder="Task title" required
                        class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div class="mb-4">
                    <textarea name="description" placeholder="Task description" 
                        class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300"></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
                    Add Task
                </button>
            </form>
        </div>

        <hr class="my-6 border-gray-300">

        <!-- To-Do List -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-3">Tasks</h3>

            <div class="flex justify-between mb-4">
                <button class="filter-btn bg-blue-500 text-white px-4 py-2 rounded-lg" data-filter="all">All</button>
                <button class="filter-btn bg-yellow-500 text-white px-4 py-2 rounded-lg" data-filter="pending">Pending</button>
                <button class="filter-btn bg-green-500 text-white px-4 py-2 rounded-lg" data-filter="completed">Completed</button>
            </div>

            <ul class="divide-y divide-gray-300">
                @foreach($todos as $todo)
                <li class="todo-item p-4 flex justify-between items-center" data-status="{{ $todo->status }}">
                    <div>
                        <span class="font-medium text-lg">{{ $todo->title }}</span>
                        <span class="block text-sm text-gray-600">{{ ucfirst($todo->status) }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" class="toggle-status h-5 w-5" data-id="{{ $todo->id }}"
                            {{ $todo->status === 'completed' ? 'checked' : '' }}>
                        <form action="{{ route('todos.destroy', $todo->id) }}" method="POST" class="inline-block">
                            @csrf @method('DELETE')
                            <button type="submit" class="delete-btn text-red-500 hover:text-red-700">
                                ✖
                            </button>
                        </form>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(".toggle-status").change(function() {
                let todoId = $(this).data("id");
                let checkbox = $(this);
                $.ajax({
                    url: `/todos/${todoId}/toggle`,
                    type: "PATCH",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        let newStatus = response.status;
                        checkbox.closest(".todo-item").attr("data-status", newStatus);
                        Swal.fire({
                            title: "Updated!",
                            text: "Task status changed to " + newStatus,
                            icon: "success",
                            timer: 1000,
                            showConfirmButton: false
                        });
                    }
                });
            });

            $(".delete-btn").click(function(event) {
                event.preventDefault();
                let form = $(this).closest("form");

                Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this task!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            $(".filter-btn").click(function() {
                let filter = $(this).data("filter");
                $(".todo-item").each(function() {
                    if (filter === "all" || $(this).data("status") === filter) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>

</body>
</html>
