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
        Schema::create('orgaddresses', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable()->index();
            $table->unsignedBigInteger('country_id')->nullable()->unsigned()->index();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->string('title', 255)->default('Megnevezés');
            // $table->enum('type', ['billing', 'shipping'])->default('billing')->index();
            $table->boolean('main')->default(false);
            // A NAV-tól jön majd bele adat. Pl.: HQ
            $table->string('address_type')->nullable()->index();
            $table->string('postal_code')->nullable()->index();
            $table->string('region')->nullable()->index();
            $table->string('city')->nullable()->index();
            $table->string('street_name')->nullable()->index();
            /**
             * @property \Domain\Shared\Enums\AddressPublicPlaceCategory|null $type
             */
            $table->string('public_place_category', 100)->nullable()->index();
            $table->string('number', 30)->nullable()->index();
            $table->string('building', 30)->nullable()->index();
            $table->string('floor', 30)->nullable()->index();
            $table->string('door', 30)->nullable()->index();
            $table->string('address', 512)->nullable()->index();
            // Ez mi?
            $table->string('lane', 512)->nullable()->index();
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
        Schema::dropIfExists('orgaddresses');
    }
};
