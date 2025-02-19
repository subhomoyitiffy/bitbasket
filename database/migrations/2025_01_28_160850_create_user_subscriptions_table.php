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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('subscription_id')->references('id')->on('packages');
            $table->integer('user_id')->references('id')->on('users');
            $table->integer('coupon_id')->references('id')->on('coupon')->nullable();
            $table->decimal('coupon_discount', 8, 2)->nullable();
            $table->string('coupon_code')->nullable();
            $table->decimal('payable_amount', 8, 2)->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->dateTime('subscription_start');
            $table->dateTime('subscription_end');
            $table->string('comment')->nullable();
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
