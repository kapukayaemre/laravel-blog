<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleCreateRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
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

    public function edit(Request $request, int $articleID)
    {
        // $article = Article::find($articleID);
        $article = Article::query()
                            ->where("id", $articleID)
                            ->first();
        $categories = Category::all();
        $users = User::all();

        if (is_null($article))
        {
            $statusText = "Article Not Found";

            alert()
                ->error('Error', $statusText)
                ->showConfirmButton("Okay", "#3085d6")
                ->autoClose(5000);
            return redirect()->route("article.index");
        }

        return view("admin.articles.create-update", compact("article", "categories", "users"));
    }

    public function update(ArticleUpdateRequest $request)
    {
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

        if ($request->has("image"))
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

            $data["image"] = $publicPath. "/" .$fileName;
        }

        $data["user_id"] = auth()->id();

        $articleQuery = Article::query()->where("id", $request->id);
        $articleFind = $articleQuery->first();
        $articleQuery->update($data);

        if (!is_null($request->image))
        {
            if (file_exists(public_path($articleFind->image)))
            {
                \File::delete(public_path($articleFind->image));
            }
            $imageFile->storeAs($folder, $fileName, "public");
        }

        alert()
            ->success('Success', "Article updated successfully")
            ->showConfirmButton('Okay', '#3085d6')
            ->autoClose(5000);

        return redirect()->route("article.index");
    }

    public function slugCheck(string $text)
    {
        return Article::where("slug", $text)->first();
    }
}
