<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ["id", "created_at", "updated_at"];

    public function scopeName($query, $name)
    {
        if (!is_null($name))
            return $query->where("name", "LIKE", "%". $name ."%");
    }
    public function scopeDescription($query, $description)
    {
        if (!is_null($description))
            return $query->where("description", "LIKE", "%". $description ."%");
    }
    public function scopeSlug($query, $slug)
    {
        if (!is_null($slug))
            return $query->where("slug", "LIKE", "%". $slug ."%");
    }
    public function scopeOrder($query, $order)
    {
        if (!is_null($order))
            return $query->where("order", $order);
    }
    public function scopeStatus($query, $status)
    {
        if (!is_null($status))
            return $query->where("status", "LIKE", "%". $status ."%");
    }
    public function scopeFeatureStatus($query, $feature_status)
    {
        if (!is_null($feature_status))
            return $query->where("feature_status", "LIKE", "%". $feature_status ."%");
    }

    public function scopeUser($query, $userID)
    {
        if (!is_null($userID))
            return $query->where("user_id", $userID);
    }

    public function scopeParentCategory($query, $parentID)
    {
        if (!is_null($parentID))
            return $query->where("parent_id", $parentID);
    }

    public function parentCategory(): HasOne
    {
        return $this->hasOne(Category::class, "id","parent_id");
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, "category_id", "id");
    }

    public function articlesActive(): HasMany
    {
        return $this->hasMany(Article::class, "category_id", "id")
            ->where("status", 1)
            ->whereNotNull("publish_date")
            ->where("publish_date", "<=", now());
    }
}
