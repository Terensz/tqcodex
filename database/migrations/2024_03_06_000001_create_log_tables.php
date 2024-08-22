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
        Schema::create('adminactivitylogs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained();
            $table->string('action');
            $table->string('modified_property', 128)->nullable();
            $table->text('original_value')->nullable();
            $table->text('modified_value')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('host')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        Schema::create('contactactivitylogs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('contact_id')->nullable()->constrained();
            $table->string('action');
            $table->string('modified_property', 128)->nullable();
            $table->text('original_value')->nullable();
            $table->text('modified_value')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('host')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        Schema::create('visitlogs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained();
            $table->foreignId('contact_id')->nullable()->constrained();
            $table->text('url');
            // $table->string('route_name');
            $table->string('ip_address')->nullable();
            $table->integer('count_of_visits')->nullable();
            $table->text('host')->nullable();
            $table->text('user_agent')->nullable();
            $table->date('day')->nullable();
            $table->timestamps();
        });

        Schema::create('exceptionlogs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained();
            $table->foreignId('contact_id')->nullable()->constrained();
            $table->text('message')->nullable();
            $table->string('code')->nullable();
            $table->string('file')->nullable();
            $table->string('line')->nullable();
            $table->text('trace')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adminactivitylogs');
        Schema::dropIfExists('contactactivitylogs');
        Schema::dropIfExists('visitlogs');
        Schema::dropIfExists('exceptionlogs');
    }
};
