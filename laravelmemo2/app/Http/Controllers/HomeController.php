<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use Illuminate\Support\Facades\Redirect;

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

// 
    public function index()
    {
        // 🟡⭐️こちらのメソッドでメモを取得するのはなぜ？returnはview('app',compact())ではないのか？  🟡select()の構文は？
        $memos = Memo::select('memos.*')
            ->where('user_id', '=' , \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC')
            ->get();

        // dd($memos);


        return view('create' , compact('memos'));
    }
    
    
// メモを作成する関数
    public function store(Request $request)
    {
        $posts = $request->all();
        // dd($posts);
        
        // classを作成先指定::sql文法([key]=>value,)の形。valueはhttp通信で受け取ったデータ
        Memo::insert(['content'=>$posts['content'],'user_id'=>\Auth::id() ]);
        // dd(\Auth::id());
        
        return redirect( route('home'));
    }
    
    
// メモを編集する関数
    public function edit($id)
    {
        // dd($id); // app.bladeの一覧表示の<a>のheaf属性からの受け取り確認。$memos（DBから作成した配列）があることが前提
        $memos = Memo::select('memos.*')
            ->where('user_id', '=' , \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC')
            ->get();
    
        $edit_memo = Memo::find($id);
    
    
        return view('edit' , compact('memos' , 'edit_memo'));
    }

// 投稿編集後の一覧アップデートの処理
    public function update(Request $request)
    {
        $posts = $request->all();
        // dd($posts);

        Memo::where('id', $posts['memo_id'])->update(['content' => $posts['content']]);

        return redirect( route('home'));
    }

}

