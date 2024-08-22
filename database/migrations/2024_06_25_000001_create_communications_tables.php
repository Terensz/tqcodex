<?php

use Domain\Communication\Enums\CommunicationDispatchProcessStatus;
use Domain\Communication\Enums\CommunicationForm;
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
        /**
         * - The campaign is always the reason for the communication.
         *
         * - Your org can create a campaign. If it does so, than anyone can see that in the campaigns' list, who has the permission.
         *
         */
        Schema::create('communicationcampaigns', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->text('reference_code');
            $table->text('title_lang_ref'); // The title ref of the campaign, e.g.: "database.2024SummerSales".
            // $table->longText('raw_subject'); // Spatie translatable text of the subject of the sent mail (or other comm).
            // $table->longText('raw_body'); // Spatie translatable text of the mail body.
            $table->boolean('is_published')->default(false);
            $table->boolean('is_listable')->default(true); // Personal programmatically sent invitations are also count as a campaign, but we don't want to see them in our list.
            $table->boolean('is_editable')->default(true); // Non-editable campaigns will not bring us the possibility of editing when they are viewed.
            $table->boolean('is_redispatchable')->default(true); // If a campaign is non-redispatchable, than we will be not able to send it again.
            $table->timestamps();
        });

        /**
         * - A communication dispatch process is a set of sent comms, like emails or sms'.
         *
         * - A communication dispatch process always belong to a comm campaign (like a marketing campaign, or an invitation process generated by a bulk upload of compensation items).
         *
         * - One campaign can belong to multiple dispatch processes.
         */
        Schema::create('communicationdispatchprocesses', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('communicationcampaign_id');
            $table->foreign('communicationcampaign_id') // Every campaing can have multiple dispatch-processes.
                ->references('id')
                ->on('communicationcampaigns')
                ->onDelete('cascade');
            $table->unsignedBigInteger('sender_contact_id')->nullable();
            $table->foreign('sender_contact_id') // Every campaing can have multiple dispatch-processes.
                ->references('id')
                ->on('contacts')
                ->onDelete('set null');
            $table->string('communication_form')->default(CommunicationForm::EMAIL->value);
            $table->string('status')->default(CommunicationDispatchProcessStatus::IN_PROGRESS->value);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
        });

        /**
         * - A communication dispatch is one communication toward one terminal. (That terminal can be a human, an alien, an intelligent machine or whatever.)
         *
         * - A communication dispatch always belongs to a communication dispatch process, but it's possible for the entire process to have only one comm dispatch.
         */
        Schema::create('communicationdispatches', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('communicationdispatchprocess_id')->nullable();
            $table->foreign('communicationdispatchprocess_id')
                ->references('id')
                ->on('communicationdispatchprocesses')
                ->onDelete('set null');
            $table->text('sender_address')->nullable();
            $table->text('sender_name')->nullable();
            $table->unsignedBigInteger('recipient_contact_id')->nullable();
            $table->foreign('recipient_contact_id')
                ->references('id')
                ->on('contacts')
                ->onDelete('set null');
            $table->text('recipient_address')->nullable();
            $table->text('recipient_name')->nullable();
            $table->text('subject')->nullable();
            $table->longText('body')->nullable();
            $table->text('bounced_message')->nullable();
            $table->timestamp('sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communicationdispatches');
        Schema::dropIfExists('communicationdispatchprocesses');
        Schema::dropIfExists('communicationcampaigns');
    }
};
