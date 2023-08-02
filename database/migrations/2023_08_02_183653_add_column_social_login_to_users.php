<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("google_id")->nullable()->after("remember_token");
            $table->string("facebook_id")->nullable()->after("google_id");
            $table->string("twitter_id")->nullable()->after("facebook_id");
            $table->string("github_id")->nullable()->after("twitter_id");
            $table->string("password")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
