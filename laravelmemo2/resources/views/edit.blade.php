@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        メモ編集
        <form action="{{ route('destroy') }}" method="POST">
            @csrf
            <!-- 🟡valueは何のために必要？？ 🟡その部分動画とかえて抜いている-->
            <!-- 🟦↪︎valerを除いてしまうと、home.controllerにpostでわたるnemeの中身が渡らないことがわかった -->
            <input type="hidden" name="memo_id" value="{{ $edit_memo['id'] }}">

            <button>削除</button>
        </form>
    </div>
    <form class="card-body" action="{{ route('update') }}" method="POST">
    <!-- または<form class="card-body" action="/store" method="POST"> -->
        @csrf
        <input type="hidden" name="memo_id" value="{{ $edit_memo['id']}}">
        <div class="mb-3">
            <textarea class="form-control" name="content" rows="3"">
            {{ $edit_memo['content'] }}
            </textarea>
        </div>
        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>
@endsection

