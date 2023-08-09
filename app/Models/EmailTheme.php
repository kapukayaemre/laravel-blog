<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailTheme extends Model
{
    protected $guarded = [];

    public const THEME_TYPE = [
        "Choose Theme Type",
        "I Want To Create Myself",
        "Password Reset Mail"
    ];
    public const PROCESS = [
        "Choose Process Type",
        "Email Verify Mail Content",
        "Password Reset Mail Content",
        "After Password Reset Mail Content"
    ];


    /*** Veritabanından bir integer değer geliyor
     * * örnek olarak 2 diyelim karşılığındaki değeri
     * * yukarıdaki sabitlerin index sırasına göre bulup
     * * o değeri döndürüyor.
     * * ÖRN: theme_type 2 için Password Reset Mail döndürülüyor.
     * */
    public function getThemeTypeAttribute($value):string
    {
        return self::THEME_TYPE[$value];
    }

    public function getProcessAttribute($value):string
    {
        return self::PROCESS[$value];
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

}
