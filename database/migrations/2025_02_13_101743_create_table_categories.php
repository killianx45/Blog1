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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('categorie_post', function (Blueprint $table) {
            $table->foreignId('categories_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->primary(['categories_id', 'post_id']);
        });

        // Insertion des 3 catégories demandées
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
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
};
