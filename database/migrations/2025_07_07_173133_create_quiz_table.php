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
        // Tabel untuk menyimpan mata pelajaran (e.g., Math, Science)
        Schema::create('subjects', function (Blueprint $table) {
            $table->id('subject_id'); // Primary key auto-increment (BIGINT)
            $table->string('subject_name');
            // $table->timestamps(); // Opsional: jika butuh created_at & updated_at
        });

        // Tabel untuk menyimpan tipe-tipe pertanyaan (e.g., Button, Checkbox)
        Schema::create('question_types', function (Blueprint $table) {
            $table->id('q_type_id'); // Primary key auto-increment
            $table->string('type_name')->unique(); // Nama tipe harus unik
        });

        // Tabel untuk menyimpan topik dari setiap mata pelajaran
        Schema::create('topics', function (Blueprint $table) {
            $table->id('topic_id'); // Primary key auto-increment
            $table->string('topic_name');
            
            // Foreign key ke tabel 'subjects'
            $table->foreignId('subject_id')
                  ->constrained('subjects')
                  ->onDelete('cascade'); // Jika subject dihapus, topic ikut terhapus
        });

        // Tabel utama untuk menyimpan pertanyaan
        Schema::create('questions', function (Blueprint $table) {
            $table->id('question_id'); // Primary key auto-increment
            $table->text('question_text'); // Gunakan text untuk pertanyaan yang lebih panjang

            // Foreign key ke tabel 'topics'
            $table->foreignId('topic_id')
                  ->constrained('topics')
                  ->onDelete('cascade');

            // Foreign key ke tabel 'question_types'
            $table->foreignId('q_type_id')
                  ->constrained('question_types');
            
            // Kolom 'correct_ans' dihapus karena informasinya dikelola di tabel 'choices'
        });

        // Tabel untuk menyimpan pilihan jawaban dari setiap pertanyaan
        Schema::create('choices', function (Blueprint $table) {
            $table->id('choice_id'); // Primary key auto-increment
            $table->string('choice_text');
            $table->boolean('is_correct')->default(false); // Untuk tipe soal pilihan ganda/checkbox
            
            // Untuk tipe soal 'reorder', kolom ini menyimpan urutan yang benar
            $table->unsignedInteger('correct_order')->nullable(); 

            // Foreign key ke tabel 'questions'
            $table->foreignId('question_id')
                  ->constrained('questions')
                  ->onDelete('cascade'); // Jika pertanyaan dihapus, pilihan jawaban ikut terhapus
        });
    }

    /**
     * Reverse the migrations.
     * (Anda harus menambahkan fungsi down() dengan urutan terbalik)
     */
    public function down(): void
    {
        Schema::dropIfExists('choices');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('topics');
        Schema::dropIfExists('question_types');
        Schema::dropIfExists('subjects');
    }
};