<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
{
    $query = \App\Models\User::query();

    // Search by name or email
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%'.$request->search.'%')
              ->orWhere('email', 'like', '%'.$request->search.'%');
        });
    }

    // Filter by role
    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    // Paginate
    $users = $query->paginate(5);

    // Keep current query params in pagination links
    $users->appends($request->query());

    return view('admin.users.index', compact('users'));
}

    

    // Show add form
    public function create()
    {
       return view('admin.users.add');

    }

    //add user

   public function store(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users',
        'password' => 'required|confirmed|min:6',
        'role'     => 'required|in:admin,staff',
    ]);

    User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => $request->role,
    ]);

    // ✅ dynamic message based on role
    return redirect()->route('users.index')
        ->with('success', ucfirst($request->role) . ' account created successfully!');
}



    public function edit($id)
{
    $user = User::findOrFail($id);
    return view('admin.users.edit', compact('user'));
}


//update
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required|string',
    ]);

    $user->update($request->only('name', 'email', 'role'));

    return redirect()->route('users.index')->with('success', 'User updated successfully.');
}

//delete
public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->route('users.index')->with('success', 'User deleted successfully.');
}

    
}
