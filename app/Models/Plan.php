<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Plan extends Model
{
    //
    public function up()
{
    Schema::create('plans', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description');
        $table->integer('premium');
        $table->integer('coverage');
        $table->timestamps();
    });
}
}
