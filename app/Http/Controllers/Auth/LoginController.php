<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Core\Auth;
use Core\Request\Request;
use Core\Response\Response;
use Core\View;

class LoginController extends Controller
{
    public function index()
    {
        return new View('auth.login');
    }

    public function handleLogin(Request $request)
    {
        $validated = $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required', 'min_length:8', 'max_length:32'],
        ]);

        $user = Auth::attempt($validated['email'], $validated['password']);

        if($user) {
            return Response::redirect('/');
        }

        Response::redirect('/login', 302, ['email' => 'Invalid credentials']);
    }
}