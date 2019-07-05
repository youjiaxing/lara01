<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FollowersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function store(User $user)
    {
        // 不能关注自己
        if ($user->id === \Auth::id()) {
            throw new BadRequestHttpException("不能关注自己");
        }

        \Auth::user()->follow($user->id);
        return redirect()->back();
//        return response("store ok");
    }

    public function destroy(User $user)
    {
        if (\Auth::id() === $user->id) {
            throw new BadRequestHttpException("不能删除自己");
        }

        \Auth::user()->unfollow($user->id);
        return redirect()->back();
//        return response("delete ok");
    }
}
