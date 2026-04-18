<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('professionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('phone', 20);
            $table->string('city');
            $table->text('description')->nullable();
            $table->json('skills')->nullable();
            $table->json('languages')->nullable();
            $table->json('travel_cities')->nullable();
            $table->enum('availability', ['available', 'busy', 'closed'])->default('available');
            $table->string('avatar')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->unsignedInteger('total_reviews')->default(0);
            $table->unsignedInteger('total_views')->default(0);
            $table->unsignedInteger('total_whatsapp_clicks')->default(0);
            $table->unsignedInteger('total_calls')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('professionals');
    }
};
