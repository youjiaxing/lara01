<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{
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
            return redirect()->route("home")->with("info", "欢迎回来");
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
