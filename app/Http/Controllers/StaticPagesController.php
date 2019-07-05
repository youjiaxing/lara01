<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    public function home()
    {
        $feed_items  = [];
        if (\Auth::check()) {
            $feed_items  = \Auth::user()->feed()->with('user')->paginate(30);
        }

        return view('static_pages.' . explode('::', __METHOD__)[1], compact('feed_items'));
    }

    public function about()
    {
        return view('static_pages.' . explode('::', __METHOD__)[1]);
    }

    public function help()
    {
        return view('static_pages.' . explode('::', __METHOD__)[1]);
    }
}
