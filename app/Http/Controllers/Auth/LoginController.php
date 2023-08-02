<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showLoginUser()
    {
        return view('front.auth.login');
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

            $userIsAdmin = Auth::user()->is_admin;
            if (!$userIsAdmin)
                return redirect()->route("home");

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
            $isAdmin = Auth::user()->is_admin;

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if (!$isAdmin)
                return redirect()->route("home");

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

        $token = Str::random("60");
        UserVerify::create([
           "user_id" => $user->id,
           "token" => $token
        ]);

        Mail::send("email.verify", compact("token", "user"), function ($mail) use ($user) {
            $mail->to($user->email);
            $mail->subject("Verify User");
        });

        alert()
            ->success("Success", "Check your mail for verify account")
            ->showConfirmButton("Okay", "#3085d6")
            ->autoClose(5000);

        return redirect()->back();

    }

    public function verify(Request $request, $token)
    {
        $verifyQuery = UserVerify::query()->where("token", $token);
        $find = $verifyQuery->first();

        if (!is_null($find))
        {
            $user = $find->user;

            if (is_null($user->email_verified_at))
            {
                $user->email_verified_at = now();
                $user->status = 1;
                $user->save();
                $verifyQuery->delete();
                $message = "Email Verified Successfully";
            }
            else
            {
                $message = "Email Verified Already";
            }

            alert()
                ->success("Success", $message)
                ->showConfirmButton("Okay", "#3085d6")
                ->autoClose(5000);

            return redirect()->route("login");
        }
        else
        {
            abort(404);
        }
    }

    public function socialLogin($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function socialVerify($driver)
    {
        $user = Socialite::driver($driver)->user();

        $userCheck = User::where("email", $user->getEmail())->first();
        if ($userCheck)
        {
            Auth::login($userCheck);
            return redirect()->route("home");
        }

        $username = Str::slug($user->getName());
        $userCreate = User::create([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => bcrypt(""),
            'username' => is_null($this->checkUsername($username)) ? $username : $username.uuid_create(),
            "status" => 1,
            "email_verified_at" => now(),
            $driver."_id" => $user->getId()
        ]);

        Auth::login($userCreate);
        return redirect()->route("home");
    }

    public function checkUsername(string $username): null|object
    {
        return User::query()->where("username", $username)->first();
    }

}
