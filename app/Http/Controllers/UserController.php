<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required|min:8',
        ]);

        $user = new User;
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->password = Hash::make($validated['password']);
        $user->save();

        return response()->json([
            'message' => 'User registered successfully',
            'id' => $user->id,
            'redirect' => 'users/{id}'
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $user = User::find($id);
        return response()->json($user);
    }

    public function edit($id): JsonResponse
    {
        $user = User::find($id);
        return response()->json($user);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required',
            'password' => 'required|min:8',
        ]);

        $user = User::find($id);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->password = Hash::make($validated['password']);
        $user->save();

        return response()->json([
            'message' => 'User updated successfully'
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        $user = User::find($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }
}
