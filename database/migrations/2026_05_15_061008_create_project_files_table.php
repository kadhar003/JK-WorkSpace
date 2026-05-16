<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_files', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('name');

            $table->string('path');

            $table->string('type');

            $table->longText('content')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_files');
    }
};