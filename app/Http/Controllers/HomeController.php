<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $posts = $request->all();
        // dd(\Auth::id());
        // キー=>バリューで配列にしてdbに入れる？
        // 30🟡inseertとは
        Memo::insert(['content'=> $posts['content'],'user_id'=> \Auth::id()]);

        return redirect( route('home'));
    }
}
