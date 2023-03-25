<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->longText('title');
            $table->longText('content')->nullable();
            $table->longText('slug')->nullable();
            $table->dateTime('date_publish')->nullable();
            $table->longText('ogImage');
            $table->string('authorName');
            $table->string('categoryName');
            $table->string('categorySlug');
            $table->string('authorSlug');
            $table->integer('status')->default(0);
            $table->integer('likesCount')->default(0);
            $table->integer('commentsCount')->default(0);
            $table->integer('commentStatus')->default(1);
            $table->integer('viewsCount')->default(0);
            $table->unsignedBigInteger('author_id')->nullable();
            $table->unsignedBigInteger('post_id')->nullable();
            $table->integer('visible')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
