<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    use HasFactory;

    protected $table = 'question_types';

    // Primary key adalah 'q_type_id'
    protected $primaryKey = 'q_type_id';

    protected $fillable = [
        'type_name',
    ];

    // Asumsikan tidak menggunakan timestamps
    public $timestamps = false;

    /**
     * Mendefinisikan relasi One-to-Many dengan model Question.
     * Sebuah QuestionType memiliki banyak Questions.
     */
    public function questions()
    {
        // 'Question::class' adalah model yang berelasi.
        // 'q_type_id' adalah foreign key di tabel 'questions'.
        // 'q_type_id' adalah local key di tabel 'question_types'.
        return $this->hasMany(Question::class, 'q_type_id', 'q_type_id');
    }
}
