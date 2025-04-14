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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');

            // Additional columns
            $table->string('tags')->nullable();             // Tags or labels (comma separated or JSON later)
            $table->boolean('is_favorite')->default(false); // Mark note as favorite
            $table->enum('status', ['active', 'archived'])->default('active'); // Status of the note
            $table->timestamp('reminder_at')->nullable();   // Optional reminder timestamp
            $table->text('attachments')->nullable();        // For future file/image paths (JSON or comma separated)
            
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
