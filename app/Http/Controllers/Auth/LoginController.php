<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\UserStoreRequest;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Models\UserVerify;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    use Loggable;

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showLoginUser()
    {
        return view('front.auth.login');
    }

    public function showPasswordReset()
    {
        return view("front.auth.reset-password");
    }

    public function showPasswordResetConfirm(Request $request)
    {
        $token = $request->token;

        $tokenExist = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$tokenExist)
        {
            abort(404);
        }

        return view("front.auth.reset-password", compact("token"));
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

            $this->log("login", $user->id, $user->toArray(), User::class);

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

            $this->log("logout", \auth()->id(), \auth()->user()->toArray(), User::class);
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

        event(new UserRegistered($user));

        alert()
            ->success("Success", "Check your mail for verify account")
            ->showConfirmButton("Okay", "#3085d6")
            ->autoClose(5000);

        return redirect()->back();

    }

    public function verify(Request $request, $token)
    {
        $verifyQuery = UserVerify::query()->with("user")->where("token", $token);
        $find = $verifyQuery->first();

        if (!is_null($find))
        {
            $user = $find->user;

            if (is_null($user->email_verified_at))
            {
                $user->email_verified_at = now();
                $user->status = 1;
                $user->save();

                $this->log("verify user", $user->id, $user->toArray(), User::class);

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
            $this->log("verify user", \auth()->id(), \auth()->user()->toArray(), User::class);

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

    public function sendPasswordReset(Request $request)
    {
        $email = $request->email;
        $find = User::query()->where("email", $email)->firstOrFail();

        $tokenFind = DB::table('password_reset_tokens')->where("email", $email)->first();
        if ($tokenFind)
        {
            $token = $tokenFind->token;
        }
        else
        {
            $token = Str::random(60);
            DB::table("password_reset_tokens")->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => now()
            ]);
        }

        if ($tokenFind && now()->diffInHours($tokenFind->created_at) < 6)
        {
            alert()
                ->info('Success', "You sent password reset mail before. Try 6 hours later again.")
                ->showConfirmButton('Tamam', '#3085d6')
                ->autoClose(5000);

            return redirect()->back();
        }

        Mail::to($find->email)->send(new ResetPasswordMail($find, $token));
        $this->log("password reset mail send", $find->id, $find->toArray(), User::class, true);

        alert()
            ->success('Success', "Password reset mail sent")
            ->showConfirmButton('Okay', '#3085d6')
            ->autoClose(5000);

        return redirect()->back();
    }

    public function passwordReset(PasswordResetRequest $request)
    {
        $tokenQuery = DB::table("password_reset_tokens")->where('token', $request->token);
        $tokenExist = $tokenQuery->first();
        if (!$tokenExist)
            abort(404);

        $userExist= User::query()->where('email', $tokenExist->email)->first();
        if (!$userExist)
            abort(400, 'Please contact with your administrator');

        $userExist->update(['password' => Hash::make($request->password)]);

        $tokenQuery->delete();

        alert()
            ->success('Success', "Password reset, you can login with new password")
            ->showConfirmButton('Okay', '#3085d6')
            ->autoClose(5000);

        return redirect()->route('user.login');
    }

}
