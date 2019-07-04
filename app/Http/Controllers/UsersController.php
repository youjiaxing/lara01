<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['create', 'store', 'show', 'index']]);
        $this->middleware('guest', ['only' => ['create', 'store']]);
    }

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

    public function index()
    {
        $users = User::query()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $this->authorize("update", $user);
        return view("users.edit", compact('user'));
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $this->authorize("update", $user);
        $user->setAttribute("name", $request->input('name'));
        if ($request->input("password")) {
            $user->setAttribute("password", bcrypt($request->input('password')));
        }
        $user->save();
        return redirect()->route("users.show", [$user->id])->with("info", "资料更新成功");
    }

    public function destroy(User $user)
    {
        $this->authorize("destroy", $user);
        $user->delete();
        return back()->with("info", "删除成功");
    }
}
