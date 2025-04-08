<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('type');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->string('description')->nullable();
            $table->decimal('income', 15, 2)->default(0);
            $table->decimal('outcome', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('wallets');
    }
};