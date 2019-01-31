<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\CommonController;

class SystemController extends Controller
{
    public function index()
    {
    	return view('admin.index.system');
    }
}
