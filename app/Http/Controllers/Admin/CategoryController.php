<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        /**
         *? parentCategory:id,name şeklinde ilişkili olan datada istediğimiz columnları getirir
         *? ama direk datadaki belli columnları seçmek istersek select(['id','name']) şeklinde kullanmak gerekir.
         */

        $categories = Category::with(["parentCategory:id,name", "user"])->orderBy('order','DESC')->get();
        return view("admin.categories.list", ['categories' => $categories]);
    }

    public function create()
    {
        return view("admin.categories.create-update");
    }

    public function changeStatus(Request $request)
    {
        /**? exists:categories if category not exist return false */
        $request->validate([
            'id' => ['required', 'integer', 'exists:categories']
        ]);

        $categoryID = $request->id;
        $category = Category::where("id", $categoryID)->first();
        $oldStatus = $category->status;
        $category->status = !$category->status;
        $category->save();

        $statusText = ($oldStatus === 1 ? "Active" : "Inactive"). " changed to ".($category->status === 1 ? "Active" : "Inactive");

        alert()
            ->success('Success', $category->name." status $statusText")
            ->showConfirmButton('Confirm', '#3085d6')
            ->autoClose(5000);

        return redirect()->back();

    }

    public function changeFeatureStatus(Request $request)
    {
        /**? exists:categories if category not exist return false */
        $request->validate([
            'id' => ['required', 'integer', 'exists:categories']
        ]);

        $categoryID = $request->id;
        $category = Category::where("id", $categoryID)->first();
        $oldStatus = $category->feature_status;
        $category->feature_status = !$category->feature_status;
        $category->save();

        $statusText = ($oldStatus === 1 ? "Active" : "Inactive"). " changed to ".($category->feature_status === 1 ? "Active" : "Inactive");

        alert()
            ->success('Success', $category->name." feature status $statusText")
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
            ->success('Success', $category->name." is deleted successfully")
            ->showConfirmButton('Confirm', '#3085d6')
            ->autoClose(5000);

        return redirect()->back();

    }

    public function edit(Request $request)
    {
        $categoryID = $request->id;
        $category = Category::where("id", $categoryID)->first();

        if (is_null($category))
        {
            alert()
                ->warning('Warning', "Category Not Found")
                ->showConfirmButton('Confirm', '#3085d6')
                ->autoClose(5000);

            return redirect()->route('category.index');
        }

        return view("admin.articles.create-update", compact("category"));
    }
}
