<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleCreateRequest;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        return view("admin.articles.list");
    }

    public function create()
    {
        $categories = Category::all();
        return view("admin.articles.create-update", compact('categories'));
    }

    public function store(ArticleCreateRequest $request)
    {
        $imageFile = $request->file("image");
        $originalName = $imageFile->getClientOriginalName();
        $originalExtension = $imageFile->getClientOriginalExtension();
        $explodeName = explode(".", $originalName)[0];
        $fileName = Str::slug($explodeName). ".". $originalExtension;

        $folder = "articles";
        $publicPath = "storage/".$folder;

        if (file_exists(public_path($publicPath. $fileName)))
        {
            return redirect()->back()->withErrors([
               'image' => 'Same picture already uploaded'
            ]);
        }

        $data = $request->except("_token");
        $slug = $data["slug"] ?? $data["title"];
        $slug = Str::slug($slug);
        $slugTitle = Str::slug($data["title"]);

        $checkSlug = $this->slugCheck($slug);

        /*? Check if slug used */
        if (!is_null($checkSlug))
        {
            $checkTitleSlug = $this->slugCheck($slugTitle);
            if (!is_null($checkTitleSlug))
            {
                /*? if 2 condition didn't return null create new unique slug*/
                $slug = Str::slug($slug. time());
            }
            else
            {
                $slug = $slugTitle;
            }
        }

        $data["slug"] = $slug;
        $data["image"] = $publicPath."/".$fileName;
        $data["user_id"] = auth()->id();

        Article::create($data);

        // $imageFile->store("articles", "public");
        $imageFile->storeAs($folder, $fileName, "public");

        alert()
            ->success("Success", "Article Created Successfully")
            ->showConfirmButton("Okay", "#3085d6")
            ->autoClose(5000);

        return redirect()->route("article.index");

    }

    public function slugCheck(string $text)
    {
        return Article::where("slug", $text)->first();
    }
}
