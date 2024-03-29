<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        /**
         *? parentCategory:id,name şeklinde ilişkili olan datada istediğimiz columnları getirir
         *? ama direk datadaki belli columnları seçmek istersek select(['id','name']) şeklinde kullanmak gerekir.
         */

        $parentCategories = Category::all();
        $users = User::all();

        /** $parentID = $request->parent_id;
        $userID = $request->user_id; */

        $categories = Category::with(["parentCategory:id,name", "user"])
            /**
             *? Scope Kullanımının Alternatifi
            ->where(function ($query) use ($parentID, $userID) {
                if (!is_null($parentID))
                {
                    $query->where('parent_id', $parentID);
                }
                if (!is_null($userID))
                {
                    $query->where("user_id", $userID);
                }
            })*/
            ->name($request->name)
            ->description($request->description)
            ->slug($request->slug)
            ->order($request->order)
            ->status($request->status)
            ->featureStatus($request->feature_status)
            ->user($request->user_id)
            ->parentCategory($request->parent_id)
            ->orderBy('order', 'DESC')
            ->paginate(10)
            ->onEachSide(1);
        return view("admin.categories.list", ['categories' => $categories, 'parentCategories' => $parentCategories, 'users' => $users]);
    }

    public function create()
    {
        $categories = Category::all();
        return view("admin.categories.create-update", compact('categories'));
    }

    public function store(CategoryStoreRequest $request)
    {
        $slug = Str::slug($request->slug);
        try {
            $category                  = new Category();
            $category->name            = $request->name;
            $category->color           = $request->color;
            $category->slug            = is_null($this->slugCheck($slug)) ? $slug : Str::slug($slug . time());
            $category->description     = $request->description;
            $category->status          = $request->status ? 1 : 0;
            $category->parent_id       = $request->parent_id;
            $category->feature_status  = $request->feature_status ? 1 : 0;
            $category->seo_keywords    = $request->seo_keywords;
            $category->seo_description = $request->seo_description;
            $category->user_id         = auth()->id();
            $category->order           = $request->order;

            if ($request->has("image"))
            {
                $imageFile = $request->file("image");
                $originalName = $imageFile->getClientOriginalName();
                $originalExtension = $imageFile->getClientOriginalExtension();
                $explodeName = explode(".", $originalName)[0];
                $fileName = Str::slug($explodeName). ".". $originalExtension;

                $folder = "categories";
                $publicPath = "storage/".$folder;

                if (file_exists(public_path($publicPath. $fileName)))
                {
                    return redirect()->back()->withErrors([
                        'image' => 'Same picture already uploaded'
                    ]);
                }

                $category->image = $publicPath. "/" .$fileName;
                $imageFile->storeAs($folder, $fileName, "public");
            }

            $category->save();
        } catch (\Exception $exception) {
            abort(404, $exception->getMessage());
        }

        alert()
            ->success('Success', "Category created successfully")
            ->showConfirmButton('Confirm', '#3085d6')
            ->autoClose(5000);

        return redirect()->back();

    }

    /*? Check if slug exists then add time function for unique slug */
    public function slugCheck(string $text)
    {
        return Category::where("slug", $text)->first();
    }

    public function changeStatus(Request $request)
    {
        /**? exists:categories => if category not exist return false */
        $request->validate([
            'id' => ['required', 'integer', 'exists:categories']
        ]);

        $categoryID       = $request->id;
        $category         = Category::where("id", $categoryID)->first();
        $oldStatus        = $category->status;
        $category->status = !$category->status;
        $category->save();

        $statusText = ($oldStatus === 1 ? "Active" : "Inactive") . " changed to " . ($category->status === 1 ? "Active" : "Inactive");

        alert()
            ->success('Success', $category->name . " status $statusText")
            ->showConfirmButton('Confirm', '#3085d6')
            ->autoClose(5000);

        return redirect()->back();

    }

    public function changeFeatureStatus(Request $request)
    {
        /**? exists:categories => if category not exist return false */
        $request->validate([
            'id' => ['required', 'integer', 'exists:categories']
        ]);

        $categoryID               = $request->id;
        $category                 = Category::where("id", $categoryID)->first();
        $oldStatus                = $category->feature_status;
        $category->feature_status = !$category->feature_status;
        $category->save();

        $statusText = ($oldStatus === 1 ? "Active" : "Inactive") . " changed to " . ($category->feature_status === 1 ? "Active" : "Inactive");

        alert()
            ->success('Success', $category->name . " feature status $statusText")
            ->showConfirmButton('Confirm', '#3085d6')
            ->autoClose(5000);

        return redirect()->back();

    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:categories']
        ]);

        $category = Category::where("id", $request->id)->first();
        $category->delete();

        alert()
            ->success('Success', $category->name . " is deleted successfully")
            ->showConfirmButton('Confirm', '#3085d6')
            ->autoClose(5000);

        return redirect()->back();

    }

    public function edit(Request $request)
    {
        $categories = Category::all();

        $categoryID = $request->id;
        $category   = Category::where("id", $categoryID)->first();

        if (is_null($category)) {
            alert()
                ->warning('Warning', "Category Not Found")
                ->showConfirmButton('Confirm', '#3085d6')
                ->autoClose(5000);

            return redirect()->route('category.index');
        }

        return view("admin.categories.create-update", compact("category", "categories"));
    }

    public function update(CategoryStoreRequest $request)
    {
        $slug      = Str::slug($request->slug);
        $slugCheck = $this->slugCheck($slug);

        try {
            $category       = Category::find($request->id);
            $category->name = $request->name;
            $category->color  = $request->color;

            if ((!is_null($slugCheck) && $slugCheck->id === $category->id) || is_null($slugCheck)) {
                $category->slug = $slug;
            } else if (!is_null($slugCheck) && $slugCheck->id !== $category->id) {
                $category->slug = Str::slug($slug . time());
            } else {
                $category->slug = Str::slug($slug . time());
            }

            $category->description     = $request->description;
            $category->status          = $request->status ? 1 : 0;
            $category->parent_id       = $request->parent_id;
            $category->feature_status  = $request->feature_status ? 1 : 0;
            $category->seo_keywords    = $request->seo_keywords;
            $category->seo_description = $request->seo_description;
            // $category->user_id         = random_int(1, 10);
            $category->order = $request->order;

            if ($request->has("image"))
            {
                $imageFile = $request->file("image");
                $originalName = $imageFile->getClientOriginalName();
                $originalExtension = $imageFile->getClientOriginalExtension();
                $explodeName = explode(".", $originalName)[0];
                $fileName = Str::slug($explodeName). ".". $originalExtension;

                $folder = "categories";
                $publicPath = "storage/".$folder;

                if (file_exists(public_path($publicPath. $fileName)))
                {
                    return redirect()->back()->withErrors([
                        'image' => 'Same picture already uploaded'
                    ]);
                }

                if (file_exists(public_path($category->image)))
                {
                    \File::delete(public_path($category->image));
                }

                $category->image = $publicPath. "/" .$fileName;
                $imageFile->storeAs($folder, $fileName, "public");
            }

            $category->save();
        } catch (\Exception $exception) {
            abort(400, $exception->getMessage());
        }

        alert()
            ->success('Success', "Category updated successfully")
            ->showConfirmButton('Confirm', '#3085d6')
            ->autoClose(5000);

        return redirect()->route("category.index");

    }

}
