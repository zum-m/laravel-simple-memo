@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        メモ編集
        <form action="{{ route('destroy') }}" method="POST">
            @csrf
            <!-- 🟡valueは何のために必要？？ 🟡その部分動画とかえて抜いている-->
            <!-- 🟦↪︎valerを除いてしまうと、home.controllerにpostでわたるnemeの中身が渡らないことがわかった -->
            <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}">

            <button>削除</button>
        </form>
    </div>
    <form class="card-body" action="{{ route('update') }}" method="POST">
    <!-- または<form class="card-body" action="/store" method="POST"> -->
        @csrf
        <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id']}}">
        <div class="mb-3">
            <textarea class="form-control" name="content" rows="3"">
            {{ $edit_memo[0]['content'] }}
            </textarea>
        </div>
        @foreach($tags as $t)
        <div class="form-check form-check-inline mb-3">
            {{-- 3項演算子 → if文を1行で書く方法 {{ 条件 ? trueだったら : falseだったら }}--}}
            {{-- もし$include_tagsにループで回っているタグのidが含まれれば、ckeckedを書く --}}
          <input class="form-check-input" type="checkbox" name="tags[]" id="{{ $t['id'] }}" value="{{ $t['id'] }}" {{ in_array($t['id'], $include_tags) ? 'checked' : '' }}>
          <label class="form-check-label" for="{{ $t['id'] }}">{{ $t['name']}}</label>
        </div>
    @endforeach
        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>
@endsection

