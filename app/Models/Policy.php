<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class Policy extends Model
{
    //
    public function up()
{
    Schema::create('policies', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('plan_id')->constrained()->onDelete('cascade');
        $table->date('start_date');
        $table->string('status')->default('active');
        $table->timestamps();
    });
}
}
