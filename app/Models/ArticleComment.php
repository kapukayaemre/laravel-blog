<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ArticleComment extends Model
{
    protected $guarded = [];

    public function scopeApproveStatus($query)
    {
        return $query->where("status", 0);
    }

    public function scopeUser($query, $userID)
    {
        if (!is_null($userID))
            return $query->where("user_id", $userID);
    }

    public function scopeCreatedDate($query, $createdData)
    {
        if (!is_null($createdData))
            return $query->where("created_at", ">=" ,$createdData)
                ->where("created_at", "<=", now());
    }

    public function scopeSearchText($query, $search_text)
    {
        if (!is_null($search_text))
            return $query->where("comment", "LIKE", "%". $search_text ."%")
                ->orWhere("name", "LIKE", "%". $search_text ."%")
                ->orWhere("email", "LIKE", "%". $search_text ."%");
    }

    public function user():HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function article():HasOne
    {
        return $this->hasOne(Article::class, "id", "article_id");
    }

    public function parent():HasOne
    {
        return $this->hasOne(ArticleComment::class, "id", "parent_id");
    }

    public function children():HasMany
    {
        return $this->hasMany(ArticleComment::class, "parent_id", "id");
    }
}
