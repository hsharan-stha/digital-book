<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role_id', [1, 2])->get();
        return view('users.index', compact('users'));
    }
    
    public function create()
    {
        $roles = Role::all();
        $companies = Company::all();
        return view('users.create', compact('roles', 'companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'email'=>'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        User::create($request->all());
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }
    
    
}
