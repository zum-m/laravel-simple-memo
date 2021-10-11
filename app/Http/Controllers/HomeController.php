<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use DB;

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
        // dd($posts);

        // ------
        DB::transaction(function() use($posts){//クロージャーとは？
            $memo_id = Memo::insertGetId(['content' => $posts['content'],'user_id'=>\Auth::id()]);
            if(!empty($posts['new_tag'])){
                dd('sinnkitaguaari');
            }

        });

        // ------
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

    public function update(Request $request)
    {
        $posts = $request->all();
        // dd($posts);

        Memo::where('id', $posts['memo_id'])->update(['content'=> $posts['content']]);
        return redirect( route('home'));
    }

    public function destroy(Request $request)
    {
        $posts = $request->all();

        // Memo::where('id', $posts['memo_id'])->delete();←NGこれやると物理削除
        Memo::where('id', $posts['memo_id'])->update(['deleted_at' => date("Y-m-d H:i:s", time())]);

        return redirect( route('home') );
    }


}
