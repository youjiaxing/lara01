<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    public function home()
    {
        return view('static_pages.' . explode('::', __METHOD__)[1]);
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
