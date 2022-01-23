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
        // dd($posts);
        
        // classを作成先指定::sql文法([key]=>value,)の形。valueはhttp通信で受け取ったデータ
        Memo::insert(['content'=>$posts['content'],'user_id'=>\Auth::id() ]);
        // dd(\Auth::id());

        return redirect( route('home'));
    }
}
