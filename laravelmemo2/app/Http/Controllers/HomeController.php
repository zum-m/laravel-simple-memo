<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\MemoTag;
use Illuminate\Support\Facades\Redirect;
use DB; //DBは何か？？

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
        $tags = Tag::select('tags.*')->
            where('user_id' , "=" , \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();
// dd($tags);
        // dd($memos);


        return view('create' , compact('memos' , 'tags'));
    }
    
    
// メモを作成する関数
    public function store(Request $request)
    {
        $posts = $request->all();
        // dd($posts);

        // ＝＝＝＝＝＝＝＝＝＝＝
        DB::transaction(function() use($posts){
            $memo_id = Memo::insertGetId(['content' => $posts['content'],'user_id'=> \Auth::id()]);
            // ここのタグexsistsは何に使う？？→タグがなければその表示処理が別のものになるから、ture,folseで返している。
            $tag_exists = Tag::where('user_id' , '=' , \Auth::id())
                ->where('name', '=' , $posts['new_tag'])
                ->exists();  //データが存在するかをtrue folseを返すメソッド
                    // $tag_name = Tag::where('user_id' , '=' , \Auth::id())
                        // ->where('name', '=' , $posts['new_tag']);

            // 新規タグに入力があるかチェック = 新規タグが既にtagsテーブルにあるかをチェック
            if( (!empty($posts['new_tag']) || $posts['new_tag']=== "0") && !$tag_exists ){
                // dd('タグが入力されている時の条件分岐確認');
                // dd($tag_exists);
                // 新しく入力するタグがあればそれをユーザーidと一緒にデータベースに入れてあげたい→そのためには
                // $tag_id = Tag::insert(['user_is' => \Auth::id() , 'name' => $tag_name]);
                $tag_id = Tag::insert(['user_id' => \Auth::id() , 'name' => $posts['new_tag']]);
                // 中間テーブルのインサートも必要
                MemoTag::insert([ 'memo_id' => $memo_id, 'tag_id' => $tag_id ]);
            }
            // 
            if(!empty($posts['tags'][0])){
                foreach($posts['tags'] as $tag){
                    MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag]);
                }
            }
        });
        // ＝＝＝＝＝＝＝＝＝＝＝
        

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

// 消去機能の実装
    public function destroy(Request $request)
    {
        $posts = $request->all();
        // dd($posts);

        // 消去するが、データベースのdeleted_atのカラムを加えていきたいから、updet()を使う
        // Memo::where('id', $posts['memo_id'])->delete();←NGこれやると物理削除
        Memo::where('id', $posts['memo_id'])->update(['deleted_at' => date("Y-m-d H:i:s", time())]);

        // Memo::where('id', $posts['memo_id'])->update(['deleted_at' => data('Y-m-d H:i:s', time() )]);  🟦date("")の引数はダブルクオーテーション

        return redirect( route('home'));
    }

}

