<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['create', 'store', 'show', 'index', 'confirmEmail']]);
        $this->middleware('guest', ['only' => ['create', 'store', 'confirmEmail']]);
    }

    public function create()
    {
        return view("users.create");
    }

    public function show(User $user)
    {
        $statuses = $user->statuses()->paginate();
        return view("users.show", compact('user', 'statuses'));
    }

    public function store(SignupRequest $request)
    {
        $user = new User($request->only(['name', 'email']));
        $user->password = bcrypt($request->input('password'));
        $user->save();

//        \Auth::login($user);

        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route("home", [$user->id])->with("success", "验证邮件已发送到你的注册邮箱上，请注意查收。");
    }

    public function confirmEmail(User $user, string $token)
    {
        if (!empty($user->activation_token) && $user->activation_token !== $token) {
            throw new NotFoundHttpException("激活出错");
        }

        $user->is_active = true;
        $user->activation_token = null;
        $user->save();
        \Auth::login($user);
        return redirect()->route('home')->with("success", "激活成功");
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
