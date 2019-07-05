<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusStoreRequest;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusesController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }


    // 创建
    public function store(StatusStoreRequest $request)
    {
        $status = \Auth::user()->statuses()->create($request->only(['content']));
        return redirect()->back()->with("info", "创建成功");
    }

    public function destroy(Status $status)
    {
        $this->authorize("destroy", $status);
        $status->delete();
        return redirect()->back()->with("danger", "删除成功");
    }
}
