<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;

    protected $table = 'choices';

    // Primary key adalah 'choice_id'
    protected $primaryKey = 'choice_id';

    protected $fillable = [
        'choice_text',
        'is_correct',
        'correct_order',
        'question_id', // Foreign key juga termasuk fillable
    ];

    // Asumsikan tidak menggunakan timestamps
    public $timestamps = false;

    /**
     * Mendefinisikan relasi Many-to-One dengan model Question.
     * Sebuah Choice dimiliki oleh satu Question.
     */
    public function question()
    {
        // 'Question::class' adalah model yang berelasi.
        // 'question_id' adalah foreign key di tabel 'choices'.
        // 'question_id' adalah local key di tabel 'questions'.
        return $this->belongsTo(Question::class, 'question_id', 'question_id');
    }
}
