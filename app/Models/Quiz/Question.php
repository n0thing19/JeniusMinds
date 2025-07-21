<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $primaryKey = 'question_id';

    protected $fillable = [
        'question_text',
        'topic_id',
        'q_type_id',
    ];

    public $timestamps = false;

    // 'q_type_id' adalah foreign key di tabel 'questions'.
    // 'q_type_id' adalah local key di tabel 'question_types'.
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'topic_id');
    }

    public function questionType()
    {
        return $this->belongsTo(QuestionType::class, 'q_type_id', 'q_type_id');
    }

    public function choices()
    {
        return $this->hasMany(Choice::class, 'question_id', 'question_id');
    }
}
