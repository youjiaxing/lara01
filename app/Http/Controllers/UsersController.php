<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function create()
    {
        return view("users.create");
    }

    public function show(User $user)
    {
        return view("users.show", compact('user'));
    }

    public function store(SignupRequest $request)
    {
        $user = User::create($request->only(['name', 'email', 'password']));

        \Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route("users.show", [$user->id]);
    }
}
