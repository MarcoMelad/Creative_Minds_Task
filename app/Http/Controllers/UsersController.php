<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authentication\RegisterRequest;
use App\Models\User;
use App\Notifications\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users',
            'phone_number' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
        User::create([
            'name' => $request->post('name'),
            'email' => $request->post('email'),
            'user_name' => $request->post('user_name'),
            'phone_number' => $request->post('phone_number'),
            'type' => $request->post('type'),
            'address' => $request->post('address'),
            'image' => $request->post('image'),
            'password' => Hash::make($request->post('password')),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users',
            'phone_number' => 'required|string|max:255|unique:users',
            'address' => 'required|string|max:255',
        ]);

        $user->update($request->all());

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function notify(User $user)
    {
        $user->notify(new PushNotification());
    }
}
