<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TopController extends Controller
{
    public function index()
    {
        $profile = auth()->user()->profile;

        return view('top', compact('profile'));
    }
}
