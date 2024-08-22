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
        // dump(ContactSegments::none->name); exit;
        Schema::create('contacts', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('referred_by')->nullable()->index();
            $table->boolean('is_banned')->default(false)->index();
            $table->tinyInteger('level')->default(0);
            $table->decimal('money_spent')->default(0.0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            //To-Do: Az SQL enum -> PHP enumra, itt pedig csak string
            $table->string('segment')->default(ContactSegment::NONE->value)->index();
            $table->string('business_type')->default(BusinessType::B2C->value)->index();
            $table->boolean('terms_ok')->default(false)->index();
            $table->boolean('news_ok')->default(false)->index();
            $table->boolean('direct_sales_ok')->default(false)->index();
            $table->boolean('photo_show_ok')->default(false)->index();
            $table->string('referral_key')->nullable()->unique();
            //To-do: ez is string legyen, PHP enum alapján.
            // $table->unsignedBigInteger('contact_status_id')->default(1)->index();
            $table->string('status')->default(ContactStatus::ACTIVE->value)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
