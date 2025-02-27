<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Création de la table categories EN PREMIER
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug');
            $table->timestamps();
        });

        // Création de la table pivot APRÈS les deux tables
        Schema::create('category_post', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->primary(['category_id', 'post_id']);
        });

        DB::table('categories')->insert([
            ['name' => 'Films', 'slug' => 'films'],
            ['name' => 'Natures', 'slug' => 'natures'],
            ['name' => 'Technologies', 'slug' => 'technologies'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_post');
        Schema::dropIfExists('categories');
    }
};
