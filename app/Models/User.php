<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'about',
        'image',
        'google_id',
        'facebook_id',
        'twitter_id',
        'github_id',
        'status',
        'is_admin',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function scopeStatus($query, $status)
    {
        if (!is_null($status))
            return $query->where("status", $status);
    }

    public function scopeSearchText($query, $search_text)
    {
        if (!is_null($search_text))
        {
            return $query->where(function ($q) use ($search_text)
            {
                $q->where("name", "LIKE", "%". $search_text ."%")
                    ->orWhere("username", "LIKE", "%". $search_text ."%")
                    ->orWhere("email", "LIKE", "%". $search_text ."%");
            });
        }
    }

    public function scopeIsAdmin($query, $is_admin)
    {
        if (!is_null($is_admin))
            return $query->where("is_admin", $is_admin);
    }

    public function articles():HasMany
    {
        return $this->hasMany(Article::class, "user_id", "id");
    }

    public function categories():HasMany
    {
        return $this->hasMany(Category::class, "user_id", "id");
    }

    public function articleLike():HasMany
    {
        return $this->hasMany(UserLikeArticle::class, "user_id", "id");
    }

    public function commentLike():HasMany
    {
        return $this->hasMany(UserLikeComment::class, "user_id", "id");
    }
}
