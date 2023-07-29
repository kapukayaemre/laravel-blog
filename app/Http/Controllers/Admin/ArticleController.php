<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleCreateRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        $categories = Category::all();

        $articles = Article::query()
            ->with(["category", "user"])
            ->where(function ($query) use ($request) {
                $query->orWhere("title", "LIKE", "%". $request->search_text ."%")
                      ->orWhere("slug", "LIKE", "%". $request->search_text ."%")
                      ->orWhere("body", "LIKE", "%". $request->search_text ."%")
                      ->orWhere("tags", "LIKE", "%". $request->search_text ."%");
            })
            ->status($request->status)
            ->category($request->category_id)
            ->user($request->user_id)
            ->publishDate($request->publish_date)
            ->where(function ($query) use ($request) {
                if ($request->min_view_count) {
                    $query->where('view_count', ">=", (int)$request->min_view_count);
                }

                if ($request->max_view_count) {
                    $query->where('view_count', "<=", (int)$request->max_view_count);
                }

                if ($request->min_like_count) {
                    $query->where('like_count', ">=", (int)$request->min_like_count);
                }

                if ($request->max_like_count) {
                    $query->where('like_count', "<=", (int)$request->max_like_count);
                }
            })
            ->paginate(5);

        return view("admin.articles.list", compact("users", "categories", "articles"));
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

    public function changeStatus(Request $request): JsonResponse
    {
        $articleID = $request->articleID;

        $article = Article::query()
            ->where("id", $articleID)
            ->first();

        if ($article)
        {
            $article->status = $article->status ? 0 : 1;
            $article->save();

            return response()
                ->json(["status" => "success", "message" => "Success", "data" => $article, "article_status" => $article->status])
                ->setStatusCode(200);
        }

        return response()
            ->json(["status" => "error", "message" => "Article Not Found"])
            ->setStatusCode(404);

    }

    public function delete(Request $request)
    {
        $articleID = $request->articleID;

        $article = Article::query()
            ->where("id", $articleID)
            ->first();

        if ($article)
        {
            $article->delete();

            return response()
                ->json(["status" => "success", "message" => "Success", "data" => "" ])
                ->setStatusCode(200);
        }

        return response()
            ->json(["status" => "error", "message" => "Article Not Found"])
            ->setStatusCode(404);

    }

    public function slugCheck(string $text)
    {
        return Article::where("slug", $text)->first();
    }
}
