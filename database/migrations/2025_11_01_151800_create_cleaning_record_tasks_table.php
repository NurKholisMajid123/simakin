<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cleaning_record_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cleaning_record_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->constrained('tugas')->onDelete('cascade');
            $table->boolean('is_done')->default(false);
            $table->text('note')->nullable();
            $table->foreignId('completed_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['cleaning_record_id', 'task_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cleaning_record_tasks');
    }
};