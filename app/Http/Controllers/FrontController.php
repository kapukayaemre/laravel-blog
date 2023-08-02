<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\Category;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    public function home()
    {
        return view("front.index");
    }

    public function category(Request $request, string $slug)
    {

        $category   = Category::query()->with("articlesActive")->where("slug", $slug)->first();

        /* ? Alternative for relations with data
         * $articles = $category->articlesActive()->with(["user", "category"])->paginate(2);
         * */

        $articles = Article::query()
            ->with(["category:id,name", "user:id,name,username"])
            ->whereHas("category", function ($query) use ($slug) {
                $query->where("slug", $slug);
            })->paginate(12);

        return view("front.article-list", compact("category", "articles"));
    }

    public function articleDetail(Request $request, string $username, string $articleSlug)
    {

        $article    = Article::query()->with([
            //"user",
            "user.articleLike",
            "comments"          => function ($query) {
                $query->where("status", 1)
                    ->whereNull("parent_id");
            },
            "comments.commentLikes",
            "comments.user",
            "comments.children" => function ($query) {
                $query->where("status", 1);
            },
            "comments.children.user",
            "comments.children.commentLikes"
        ])
            ->where("slug", $articleSlug)
            ->first();

        $userLike = $article
            ->articleLikes
            ->where("article_id", $article->id)
            ->where("user_id", \auth()->id())
            ->first();

        $article->increment("view_count");
        $article->save();

        return view("front.article-detail", compact("article", "userLike"));
    }

    public function articleComment(Request $request, Article $article)
    {
        $data = $request->except("_token");
        if (Auth::check()) {
            $data["user_id"] = Auth::id();
        }
        $data["article_id"] = $article->id;
        $data["ip"]         = $request->ip();

        ArticleComment::create($data);

        alert()
            ->success('Success', "Comment sent successfully. After check we will share")
            ->showConfirmButton('Confirm', '#3085d6')
            ->autoClose(5000);

        return redirect()->back();

    }

}
