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
        Schema::create('contactstatuses', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 32);
            $table->string('name', 32);
            $table->string('desc')->nullable();
            $table->boolean('enabled')->default(true);
            $table->tinyInteger('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactstatuses');
    }
};
