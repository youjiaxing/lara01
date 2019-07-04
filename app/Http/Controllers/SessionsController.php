<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['only' => ['create', 'store']]);
        $this->middleware('auth', ['only' => 'destroy']);
    }

    public function create()
    {
        return view("users.login");
    }

    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => ['required', 'email', 'max:255'],
            'password' => 'required'
        ]);

        if (\Auth::attempt($credentials, $request->has("remember"))) {
            if (!\Auth::user()->is_active) {

                \Auth::logout();
                return redirect()->route("home")->with("warning", "账号未激活");
            }

            return redirect()->intended(route("home"))->with("info", "欢迎回来");
        } else {
            return redirect()->back()->with("danger", "账号或密码错误");
        }
    }

    public function destroy()
    {
        \Auth::logout();
        return redirect()->route("home")->with("info", "您已退出");
    }
}
