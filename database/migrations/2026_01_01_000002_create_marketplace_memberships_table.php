<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketplace_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role')->default('buyer');
            $table->string('status')->default('active');
            $table->timestamps();

            $table->unique(['marketplace_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_memberships');
    }
};
