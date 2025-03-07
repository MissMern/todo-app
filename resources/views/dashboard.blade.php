@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-4">To-Do List</h2>
    <h3>Welcome, {{ $user->name }}!</h3>


    {{-- Display Success Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Add New To-Do Form --}}
    <form action="{{ route('todos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <input type="text" name="title" class="form-control" placeholder="What do you wanna do?">
            @error('title')
                <div class="alert alert-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Add To-Do</button>
    </form>

    {{-- List of To-Dos --}}
    <ul class="list-group mt-4">
        @foreach($todos as $todo)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>{{ $todo->title }}</span>
                <div>
                    {{-- Edit Button --}}
                    <a href="{{ route('todos.edit', $todo->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    {{-- Delete Button --}}
                    <form action="{{ route('todos.destroy', $todo->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@endsection
