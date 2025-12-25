<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    public function index()
    {
        return User::latest()->get();
    }


    public function show(User $user)
    {
        return $user;
    }

    // Create new user
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'nullable|in:customer,admin,editor',
            'phone' => 'nullable|string|max:30',
            'avatar' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'last_login' => 'nullable|date',
        ]);

        $user = User::create($data);

        return response()->json($user, 201);
    }


    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|in:customer,admin,editor',
            'phone' => 'sometimes|string|max:30',
            'avatar' => 'sometimes|string',
            'is_active' => 'sometimes|boolean',
            'last_login' => 'sometimes|date',
        ]);

        $user->update($data);

        return response()->json($user);
    }

  
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
