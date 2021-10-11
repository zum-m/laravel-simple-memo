@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">新規メモ作成</div>
    <form class="card-body" action="/store" method="POST">
        <div class="form-group">
            <!-- <label for="exampleFormControlTextarea1">Example textarea</label> -->
            <textarea class="form-control" name="content" rows="3" placeholder="ここにメモを入力"></textarea>
        </div>
    </form>
</div>

@endsection
