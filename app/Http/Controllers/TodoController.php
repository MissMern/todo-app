<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    // Display the dashboard with all to-dos
    public function index()
    {
        $todos = Todo::where('user_id', Auth::id())->get();
        $user = Auth::user(); // Get the logged-in user
        return view('dashboard', compact('todos', 'user'));
    }
  
  
    
    // Store a new to-do
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'completed' => false,
        ]);

        return redirect()->route('dashboard')->with('success', 'To-Do Added Successfully');
    }

    // Show the edit form
    public function edit(Todo $todo)
    {
        if ($todo->user_id !== Auth::id()) {
            abort(403);
        }

        return view('edit', compact('todo'));
    }

    // Update an existing to-do
    public function update(Request $request, Todo $todo)
    {
        if ($todo->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $todo->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('dashboard')->with('success', 'To-Do Updated Successfully');
    }

    // Delete a to-do
    public function destroy(Todo $todo)
    {
        if ($todo->user_id !== Auth::id()) {
            abort(403);
        }

        $todo->delete();

        return redirect()->route('dashboard')->with('success', 'To-Do Deleted Successfully');
    }  
    // Toggle the status of a to-do
    public function toggleStatus(Todo $todo)
    {
        if ($todo->user_id !== Auth::id()) {
            abort(403);
        }

        $todo->update([
            'completed' => !$todo->completed,
        ]);

        return redirect()->route('dashboard')->with('success', 'To-Do Status Updated Successfully');
    } 
    
}
