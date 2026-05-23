<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('users', function (Blueprint $blueprint) {
        // Remove ->after('email') since MongoDB doesn't use column ordering
        $blueprint->boolean('is_admin')->default(false);
    });
}

    public function down(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->dropColumn('is_admin');
        });
    }
};