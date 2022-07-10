@extends('layouts.app')

@section('title')
    Home
@endsection

@use(\Core\Auth)

@section('content')
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="text-center">
            @guest
                <h1 class="display-4"><span class="fw-bold">Hello</span></h1>
            @endguest

            @auth
            <h1 class="display-4"><span class="fw-bold">Hello {{ Auth::user()->name }}</span></h1>
            @endauth
        </div>
    </div>
@endsection