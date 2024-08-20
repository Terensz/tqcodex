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
        Schema::create('contactprofile_organizations', function (Blueprint $table): void {
            $table->unsignedBigInteger('contact_profile_id');
            $table->unsignedBigInteger('organization_id');
            $table->foreign('contact_profile_id')->references('id')->on('contactprofiles')
                ->onDelete('cascade');
            $table->foreign('organization_id')->references('id')->on('organizations')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactprofile_organizations');
    }
};
