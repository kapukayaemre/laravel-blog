<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingsRequest;
use App\Models\Settings;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    use Loggable;

    public function show()
    {
        $settings = Settings::first();
        return view("admin.settings.update", compact("settings"));
    }

    public function update(SettingsRequest $request)
    {
        $settings = Settings::first();
        $settings->header_text = $request->header_text;
        $settings->footer_text = $request->footer_text;
        $settings->telegram_link = $request->telegram_link;
        $settings->seo_keywords_home = $request->seo_keywords_home;
        $settings->seo_description_home = $request->seo_description_home;
        $settings->seo_keywords_articles = $request->seo_keywords_articles;
        $settings->seo_description_articles = $request->seo_description_articles;

        if ($request->video_is_active)
            $settings->video_is_active = 1;
        else
            $settings->video_is_active = 0;

        if ($request->author_is_active)
            $settings->author_is_active = 1;
        else
            $settings->author_is_active = 0;

        if ($request->feature_categories_is_active)
            $settings->feature_categories_is_active = 1;
        else
            $settings->feature_categories_is_active = 0;

        if ($request->has("logo"))
            $settings->logo = $this->imageUpload($request, "logo", $settings->logo);

        if ($request->has("category_default_image"))
            $settings->category_default_image = $this->imageUpload($request, "category_default_image", $settings->category_default_image);

        if ($request->has("article_default_image"))
            $settings->article_default_image = $this->imageUpload($request, "article_default_image", $settings->article_default_image);

        if ($request->has("reset_password_image"))
            $settings->reset_password_image = $this->imageUpload($request, "reset_password_image", $settings->reset_password_image);

        $this->updateLog($settings, Settings::class);
        $settings->save();

        alert()
            ->success('Success', "Settings updated successfully")
            ->showConfirmButton('Confirm', '#3085d6')
            ->autoClose(5000);

        return redirect()->route("settings");

    }

    public function imageUpload(Request $request, string $imageName, string|null $oldImagePath): string
    {
        $imageFile         = $request->file($imageName);
        $originalName      = $imageFile->getClientOriginalName();
        $originalExtension = $imageFile->getClientOriginalExtension();
        $explodeName       = explode(".", $originalName)[0];
        $fileName          = Str::slug($explodeName) . "." . $originalExtension;

        $folder     = "settings";
        $publicPath = "storage/" . $folder;

        if (file_exists(public_path($oldImagePath)))
        {
            \File::delete(public_path($oldImagePath));
        }

        $imageFile->storeAs($folder, $fileName, "public");
        return $publicPath . "/" . $fileName;
    }


}
