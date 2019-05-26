<?php
use Illuminate\Support\Facades\Schema; use Illuminate\Database\Schema\Blueprint; use Illuminate\Database\Migrations\Migration; class CreateCategoriesTable extends Migration { public function up() { Schema::create('categories', function (Blueprint $sp185401) { $sp185401->increments('id'); $sp185401->integer('user_id')->index(); $sp185401->text('name'); $sp185401->integer('sort')->default(1000); $sp185401->string('password')->nullable(); $sp185401->boolean('password_open')->default(false); $sp185401->boolean('enabled'); $sp185401->timestamps(); }); } public function down() { Schema::dropIfExists('groups'); } }