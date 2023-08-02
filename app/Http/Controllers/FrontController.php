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
        $settings   = Settings::first();
        $categories = Category::query()->where("status", 1)->get();
        return view("front.index", compact("settings", "categories"));
    }

    public function category(Request $request, string $slug)
    {
        $settings   = Settings::first();
        $categories = Category::query()->where("status", 1)->get();
        $category   = Category::query()->with("articlesActive")->where("slug", $slug)->first();

        /* ? Alternative for relations with data
         * $articles = $category->articlesActive()->with(["user", "category"])->paginate(2);
         * */

        $articles = Article::query()
            ->with(["category:id,name", "user:id,name,username"])
            ->whereHas("category", function ($query) use ($slug) {
                $query->where("slug", $slug);
            })->paginate(12);

        return view("front.article-list", compact("settings", "categories", "category", "articles"));
    }

    public function articleDetail(Request $request, string $username, string $articleSlug)
    {
        $settings   = Settings::first();
        $categories = Category::query()->where("status", 1)->get();

        $article    = Article::query()->with([
            "user",
            "user.articleLike",
            "comments"          => function ($query) {
                $query->where("status", 1)
                    ->whereNull("parent_id");
            },
            "comments.user",
            "comments.children" => function ($query) {
                $query->where("status", 1);
            },
            "comments.children.user"
        ])
            ->where("slug", $articleSlug)
            ->first();

        $userLike = $article->user->articleLike->where("article_id", $article->id)->first();

        $article->increment("view_count");
        $article->save();

        return view("front.article-detail", compact("settings", "categories", "article", "userLike"));
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
