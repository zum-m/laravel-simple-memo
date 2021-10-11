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
        //ここでメモを取得,Auth::id()でログインユーザーのみデータを所得
        $memos = Memo::select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC')// ASC＝小さい順、DESC=大きい順
            ->get();
            // dd($memos);

            // compactでレンダリング？🟡
        return view('create', compact('memos'));

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

    public function edit($id)//引数にmemosからのidを
    {
        $memos = Memo::select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC')// ASC＝小さい順、DESC=大きい順
            ->get();

        $edit_memo = Memo::find($id);//findは主キーを取得する
        
        return view('edit', compact('memos','edit_memo'));

    }

}
