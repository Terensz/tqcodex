<?php

use Domain\Customer\Models\ContactToken;
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
        Schema::create('contact_tokens', function (Blueprint $table) {
            $table->string('email');
            $table->string('token');
            $table->string('token_type')->default(ContactToken::TOKEN_TYPE_PASSWORD_RESET);
            $table->timestamp('created_at')->nullable();
            $table->primary(['email', 'token_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_tokens');
    }
};
