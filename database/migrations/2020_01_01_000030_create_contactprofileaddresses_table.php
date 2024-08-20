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
        Schema::create('contactprofileaddresses', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('contact_profile_id');
            $table->foreign('contact_profile_id')->references('id')->on('contactprofiles');
            $table->string('title')->default('Megnevezés');
            $table->enum('type', ['billing', 'shipping'])->default('billing')->index();
            $table->boolean('main')->default(false);
            $table->string('postal_code')->nullable()->index();
            $table->string('city')->nullable()->index();
            $table->string('lane', 512)->nullable()->index();
            $table->string('region')->nullable()->index();
            $table->string('country_code', 2)->nullable()->index();
            $table->unsignedBigInteger('country_id')->nullable()->unsigned()->index();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->text('shipping_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactaddresses');
    }
};
