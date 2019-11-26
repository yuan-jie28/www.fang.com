<?php

namespace App\Http\Controllers\Admin;

use App\Models\Renting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RentingController extends BaseController
{
    //列表
    public function index()
    {
        $data = Renting::paginate($this->pagesize);
        return view('admin.renting.index',compact('data'));
    }
}
