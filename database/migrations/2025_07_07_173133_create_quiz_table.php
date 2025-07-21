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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id('subject_id'); 
            $table->string('subject_name');
        });

        Schema::create('question_types', function (Blueprint $table) {
            $table->id('q_type_id'); 
            $table->string('type_name')->unique(); 
        });

        Schema::create('topics', function (Blueprint $table) {
            $table->id('topic_id'); 
            $table->string('topic_name'); 
            $table->foreignId('subject_id')
                  ->constrained(table: 'subjects', column: 'subject_id')
                  ->onDelete('cascade'); 
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained(table: 'users', column: 'id')
                  ->onDelete('cascade');
            $table->string('code', 6)
                  ->unique()
                  ->nullable();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id('question_id'); 
            $table->text('question_text');
            $table->foreignId('topic_id')
                  ->constrained(table: 'topics', column: 'topic_id')
                  ->onDelete('cascade');
            $table->foreignId('q_type_id')
                  ->constrained(table: 'question_types', column: 'q_type_id');
        });

        Schema::create('choices', function (Blueprint $table) {
            $table->id('choice_id');
            $table->string('choice_text');
            $table->boolean('is_correct')->default(false); 
            $table->unsignedInteger('correct_order')->nullable(); 
            $table->foreignId('question_id')
                  ->constrained(table: 'questions', column: 'question_id')
                  ->onDelete('cascade'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('choices');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('topics');
        Schema::dropIfExists('question_types');
        Schema::dropIfExists('subjects');
    }
};
