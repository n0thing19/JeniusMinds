<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    // Primary key adalah 'question_id'
    protected $primaryKey = 'question_id';

    protected $fillable = [
        'question_text',
        'topic_id',
        'q_type_id',
    ];

    // Asumsikan tidak menggunakan timestamps
    public $timestamps = false;

    /**
     * Mendefinisikan relasi Many-to-One dengan model Topic.
     * Sebuah Question dimiliki oleh satu Topic.
     */
    public function topic()
    {
        // 'Topic::class' adalah model yang berelasi.
        // 'topic_id' adalah foreign key di tabel 'questions'.
        // 'topic_id' adalah local key di tabel 'topics'.
        return $this->belongsTo(Topic::class, 'topic_id', 'topic_id');
    }

    /**
     * Mendefinisikan relasi Many-to-One dengan model QuestionType.
     * Sebuah Question dimiliki oleh satu QuestionType.
     */
    public function questionType()
    {
        // 'QuestionType::class' adalah model yang berelasi.
        // 'q_type_id' adalah foreign key di tabel 'questions'.
        // 'q_type_id' adalah local key di tabel 'question_types'.
        return $this->belongsTo(QuestionType::class, 'q_type_id', 'q_type_id');
    }

    /**
     * Mendefinisikan relasi One-to-Many dengan model Choice.
     * Sebuah Question memiliki banyak Choices.
     */
    public function choices()
    {
        // 'Choice::class' adalah model yang berelasi.
        // 'question_id' adalah foreign key di tabel 'choices'.
        // 'question_id' adalah local key di tabel 'questions'.
        return $this->hasMany(Choice::class, 'question_id', 'question_id');
    }
}
