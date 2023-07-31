<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Settings;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function home()
    {
        $settings = Settings::first();
        $categories = Category::query()->where("status", 1)->get();
        return view("front.index", compact("settings","categories"));
    }

    public function category(Request $request, string $slug)
    {
        $settings = Settings::first();
        $categories = Category::query()->where("status", 1)->get();
        $category = Category::query()->with("articlesActive")->where("slug", $slug)->first();

        /* ? Alternative for relations with data
         * $articles = $category->articlesActive()->with(["user", "category"])->paginate(2);
         * */

        $articles = Article::query()
            ->with(["category:id,name","user:id,name"])
            ->whereHas("category", function ($query) use ($slug) {
                $query->where("slug", $slug);
            })->paginate(1);

        return view("front.article-list", compact("settings", "categories", "category", "articles"));
    }
}
