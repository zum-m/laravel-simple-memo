@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">新規メモ作成</div>
    <form class="card-body" action="{{ route('store') }}" method="POST">
    <!-- または<form class="card-body" action="/store" method="POST"> -->
        @csrf
        <div class="mb-3">
            <!-- <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label> -->
            <textarea class="form-control" name="content" rows="3" placeholder="ここにメモを入力"></textarea>
        </div>
        @foreach($tags as $t)
        <div> 
            <input class="form-check-input" type="checkbox" name="tag[]" id="{{ $t['id'] }}" value="{{ $t['id'] }}"  ></input>
            <label class="form-check-lavel" for="{{ $t['id'] }}">{{ $t['name'] }}</label>
        </div>
        @endforeach
        <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="ここにタグを入力" >
        <button type="submit" class="btn btn-primary">保存</button>
        <!-- 🟡<button type="submit">とは？？-->
    </form>
</div>
@endsection

