<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->status($request->status)
            ->searchText($request->search_text)
            ->withTrashed()
            ->paginate(10);
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
        $data["status"] = isset($data["status"]) ? 1 : 0;
        User::create($data);

        alert()
            ->success("Success", "User Created Successfully")
            ->showConfirmButton("Okay", "#3085d6")
            ->autoClose(5000);

        return redirect()->route("user.index");
    }

    public function changeStatus(Request $request): JsonResponse
    {
        $userID = $request->userID;

        $user = User::query()
            ->where("id", $userID)
            ->first();

        if ($user)
        {
            $user->status = $user->status ? 0 : 1;
            $user->save();

            return response()
                ->json(["status" => "success", "message" => "Success", "data" => $user, "user_status" => $user->status])
                ->setStatusCode(200);
        }

        return response()
            ->json(["status" => "error", "message" => "User Not Found"])
            ->setStatusCode(404);

    }

    public function edit(Request $request, User $user)
    {
        return view("admin.users.create-update", compact("user"));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->except("_token");
        if (!is_null($data["password"]))
        {
            $data["password"] = bcrypt($data["password"]);
        }
        else
        {
            unset($data["password"]);
        }
        $data["status"] = isset($data["status"]) ? 1 : 0;

        $user->update($data);

        alert()
            ->success("Success", "User Updated Successfully")
            ->showConfirmButton("Okay", "#3085d6")
            ->autoClose(5000);

        return redirect()->route("user.index");
    }

    public function delete(Request $request)
    {
        $user = User::findOrFail($request->userID);
        $user->delete();

        return response()
            ->json(["status" => "success", "message" => "User Deleted Successfully"])
            ->setStatusCode(200);
    }

    public function restore(Request $request)
    {

        $user = User::withTrashed()->findOrFail($request->userID);
        $user->restore();

        return response()
            ->json(["status" => "success", "message" => "User Restored Successfully"])
            ->setStatusCode(200);
    }
}
