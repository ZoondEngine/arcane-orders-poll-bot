<?php

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
        if(Schema::hasColumn('telegram_user_subscriptions', 'subscription_id'))
        {
            Schema::dropColumns('telegram_user_subscriptions', ['subscription_id']);
        }

        Schema::table('telegram_user_subscriptions', function (Blueprint $table) {
            $table->string('unit')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
