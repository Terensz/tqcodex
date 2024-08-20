<?php

declare(strict_types=1);

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
        Schema::create('articles', function (Blueprint $table): void {
            $table->id();
        });

        // Schema::create('article_translations', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('article_id')->constrained()->onDelete('cascade');
        //     $table->string('locale')->index();
        //     $table->string('name')->nullable();
        //     $table->string('slug')->nullable();
        //     $table->text('plain_description')->nullable();
        //     $table->text('short_description')->nullable();
        //     $table->text('long_description')->nullable();
        //     $table->unique(['article_id', 'locale']);
        // });

        /**
         * Comments for an article.
         */
        Schema::create('comments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('articles_id')->nullable()->constrained();
            $table->string('locale')->nullable();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
        Schema::dropIfExists('article_translations');
        Schema::dropIfExists('articles');
    }
};
