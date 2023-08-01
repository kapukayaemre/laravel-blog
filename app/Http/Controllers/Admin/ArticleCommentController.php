<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleComment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleCommentController extends Controller
{
    public function approvalList(Request $request)
    {
        $users = User::all();
        $comments = ArticleComment::query()
            ->with(["user", "article", "children", "article.user"])
            ->approveStatus()
            ->user($request->user_id)
            ->createdDate($request->created_at)
            ->searchText($request->search_text)
            ->paginate(10);

        $page = "approval";

        return view("admin.articles.comment-list", compact("comments", "users", "page"));
    }

    public function list(Request $request)
    {
        $users = User::all();
        $comments = ArticleComment::query()
            ->withTrashed()
            ->with(["user", "article", "children", "article.user"])
            ->status($request->status)
            ->user($request->user_id)
            ->createdDate($request->created_at)
            ->searchText($request->search_text)
            ->paginate(10);

        $page = "comment-list";

        return view("admin.articles.comment-list", compact("comments", "users", "page"));
    }

    public function changeStatus(Request $request): JsonResponse
    {
        $comment = ArticleComment::findOrFail($request->id);
        $comment->status = $comment->status ? 0 : 1;
        $comment->save();

        return response()
            ->json([
                "status" => "success",
                "message" => "Comment Accepted",
                "data" => $comment,
                "comment_status" => $comment->status
            ])
            ->setStatusCode(200);
    }

    public function delete(Request $request)
    {
        $comment = ArticleComment::findOrFail($request->commentID);
        $comment->delete();

        return response()
            ->json([
                "status" => "success",
                "message" => "Comment Deleted",
                "data" => $comment,
                "comment_status" => $comment->status
            ])
            ->setStatusCode(200);
    }

    public function restore(Request $request)
    {
        $comment = ArticleComment::withTrashed()->findOrFail($request->commentID);
        $comment->restore();

        return response()
            ->json([
                "status" => "success",
                "message" => "Comment Restored",
                "data" => $comment,
                "comment_status" => $comment->status
            ])
            ->setStatusCode(200);

    }

}
