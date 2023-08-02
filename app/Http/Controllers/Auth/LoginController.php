<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $email    = $request->email;
        $password = $request->password;
        $remember = $request->remember;

        !is_null($remember) ? $remember = true : $remember = false;

        $user = User::where("email", $email)->first();

        if ($user && \Hash::check($password, $user->password))
        {
            Auth::login($user, $remember);
            /*? Auth::loginUsingId($user->id); => alternative login */
            return redirect()->route("admin.index");
        }
        else
        {
            return redirect()->route("login")->withErrors([
                'email' => 'Check your infos'
            ])->onlyInput("email");
        }

    }

    /*? Login Alternative */
    public function login2(LoginRequest $request)
    {
        $email = $request->email;
        $password = $request->password;
        $remember = $request->remember;

        !is_null($remember) ? $remember = true : $remember = false;

        if (Auth::attempt(['email' => $email, 'password' => $password], $remember))
        {
            return redirect()->route("admin.index");
        }
        else
        {
            return redirect()->route("login")->withErrors([
                'email' => 'Check your infos'
            ])->onlyInput("email");
        }
    }

    /*? Login Alternative */
    public function login3(LoginRequest $request)
    {
        $email = $request->email;
        $password = $request->password;
        $remember = $request->remember;

        !is_null($remember) ? $remember = true : $remember = false;

        if (Auth::attempt(['email' => $email, 'password' => $password, 'status' => 1], $remember))
        {
            return redirect()->route("admin.index");
        }
        else
        {
            return redirect()->route("login")->withErrors([
                'email' => 'Check your infos'
            ])->onlyInput("email");
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check())
        {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        }
    }

    public function showRegister()
    {
        return view('front.auth.register');
    }

    public function register(UserStoreRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->status = 0;
        $user->save();

    }

}
