<?php

declare(strict_types=1);

use Domain\Customer\Enums\Location;
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
        Schema::create('organizations', function (Blueprint $table): void {
            $table->id();
            $table->boolean('is_banned')->default(false)->index();

            $table->string('name')->index();
            $table->string('long_name')->nullable()->index();
            // Magyar adószám
            $table->string('taxpayer_id', 15)->nullable()->index();
            $table->string('vat_code', 3)->nullable();
            // Országkód
            $table->string('country_code', 3)->nullable()->index();
            // Megyekód (NAV adat)
            $table->string('county_code', 2)->nullable()->index();

            $table->timestamp('vat_verified_at')->nullable();
            $table->boolean('vat_banned')->default(false)->index();

            //
            $table->string('org_type')->nullable()->index();

            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable()->index();

            $table->string('eutaxid')->nullable()->index();
            // Bármilyen nem magyar és nem EU-s cég azonosítója. (pl. svájci, angol, dubaji, amerikai)
            $table->string('taxid')->nullable()->index();
            //To-Do: átalakítani PHP enum alapúra, it csak string legyen a mező
            $table->string('location')->default(Location::HU->name)->index();

            $table->text('social_media')->nullable();
            $table->text('map_coordinates')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
