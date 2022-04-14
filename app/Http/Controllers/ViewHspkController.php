<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewHspkController extends Controller
{
    public function index(Request $request)
    {
        return view('view-hspk.index');
    }
    //
}
