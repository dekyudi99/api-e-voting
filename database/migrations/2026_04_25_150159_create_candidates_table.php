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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->integer('no_undi')->unique();
            $table->string('path_photo');
            $table->string('calon_ketua');
            $table->string('calon_wakil');
            $table->text('visi')->nullable();
            $table->json('misi')->nullable();
            $table->text('proker_unggulan')->nullable();
            $table->timestamps();
            $table->boolean('on_deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
