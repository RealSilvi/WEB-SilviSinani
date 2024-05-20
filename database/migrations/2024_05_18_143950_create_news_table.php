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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('title')->nullable();
            $table->string('body')->nullable();
            $table->boolean('seen')->default(false);
            $table->string('seen_at')->nullable();
            $table->unsignedBigInteger('profile_id');
            $table->unsignedBigInteger('from');
            $table->timestamps();

            $table->foreign('profile_id')->references('id')->on('profiles')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
