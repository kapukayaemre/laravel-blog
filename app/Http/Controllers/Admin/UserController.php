<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view("admin.users.list", compact("users"));
    }

    public function create()
    {
        return view("admin.users.create-update");
    }

    public function store(UserStoreRequest $request)
    {
        $data = $request->except("_token");
        $data["password"] = bcrypt($data["password"]);
        User::create($data);

        alert()
            ->success("Success", "User Created Successfully")
            ->showConfirmButton("Okay", "#3085d6")
            ->autoClose(5000);

        return redirect()->route("user.index");
    }

    public function changeStatus(Request $request)
    {
        dd($request->all());
    }

    public function edit(Request $request)
    {
        $user = User::findOrFail($request->id);

        return view("admin.users.create-update", compact("user"));
    }

    public function update(Request $request, int $id)
    {
        dd($request->all());
    }

    public function delete(Request $request, int $id)
    {
        dd($request->all());
    }
}
