<?php

declare(strict_types=1);

use Domain\Customer\Enums\BusinessType;
use Domain\Customer\Enums\ContactSegment;
use Domain\Customer\Enums\ContactStatus;
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
        Schema::create('contactprofiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('contact_id')->nullable()->default(null)->constrained();
            // $table->foreignId('contact_id')->nullable()->index();
            $table->string('email')->nullable()->unique();
            $table->string('title', 30)->nullable()->index();
            $table->string('firstname', 100)->nullable()->index();
            $table->string('middlename', 100)->nullable()->index();
            $table->string('lastname', 100)->nullable()->index();
            $table->string('phone', 30)->nullable()->index();
            $table->string('mobile', 30)->nullable()->index();
            $table->string('position')->nullable()->index();
            $table->string('language', 2)->default('hu')->index();
            $table->text('profile_image')->nullable();
            $table->text('social_media')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactprofiles');
    }
};
