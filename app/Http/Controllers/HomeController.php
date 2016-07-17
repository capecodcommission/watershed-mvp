<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        // $scenarios = $user->scenarios()->orderBy('CreateDate', 'desc');
        // $user = Auth::user()->with('scenarios');
        // dd($user);
        return view('home', ['user'=>$user]);

    }
}
