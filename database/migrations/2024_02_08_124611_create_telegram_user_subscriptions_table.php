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
        Schema::create('telegram_user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('telegram_user_id')
                ->references('id')
                ->on('telegram_users');
            $table->integer('subscription_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_user_subscriptions');
    }
};
