@extends('layouts.app')

@section('title')
    Login
@endsection

@use(\Core\Request\Request)

@section('content')
    <div class="vh-100 d-flex justify-content-center align-items-center">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card bg-white shadow-lg">
                        <div class="card-body p-5">
                            <form action="/login" method="POST" class="mb-3 mt-md-1">
                                <h2 class="fw-bold mb-2 text-uppercase ">Login</h2>
                                <p class=" mb-5">Please enter your login and password!</p>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="text" class="form-control @if($errors->has('email')){{ 'is-invalid' }}@endif" id="email" name="email" placeholder="name@example.com" autocomplete="off">

                                    @if($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control @if($errors->has('password')){{ 'is-invalid' }}@endif" id="password" name="password" placeholder="*******" autocomplete="off">
                                                                    
                                    @if($errors->has('password'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>
                                <p class="small"><a class="text-dark" href="#">Forgot password?</a></p>
                                <div class="d-grid">
                                    <button class="btn btn-outline-dark" type="submit">Login</button>
                                </div>
                            </form>
                            <div>
                                <p class="mb-0 text-center">
                                    Don't have an account?
                                    <a href="#" class="text-dark fw-bold">Sign Up</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
