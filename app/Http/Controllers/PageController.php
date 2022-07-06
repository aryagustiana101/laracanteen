<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class PageController extends Controller
{
    public function dashboard()
    {
        return view('dashboard.index', [
            'tabTitle' => 'Dashboard'
        ]);
    }

    public function profile()
    {
        return view('profile.index', [
            'tabTitle' => 'Profile'
        ]);
    }
}
