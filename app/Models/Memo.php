<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;

    public function getMyMemo(){
        $query_tag = \Request::query('tag');
        // 46 分岐処理
        if ( !empty($query_tag)){

            $memos = Memo::select('memos.*')
             ->leftJoin('memo_tags', 'memo_tags.memo_id' ,'=', 'memos.id' )
             ->where('memo_tags.tag_id', '=', $query_tag)
                ->where('user_id', '=', \Auth::id())
                ->whereNull('deleted_at')
                ->orderBy('updated_at', 'DESC')// ASC＝小さい順、DESC=大きい順
                ->get();

        }else{

            $memos = Memo::select('memos.*')
                ->where('user_id', '=', \Auth::id())
                ->whereNull('deleted_at')
                ->orderBy('updated_at', 'DESC')// ASC＝小さい順、DESC=大きい順
                ->get();

        }
    return $memos;

    }
}
