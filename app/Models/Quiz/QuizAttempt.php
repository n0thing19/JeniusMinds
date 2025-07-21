<?php

namespace App\Models\Quiz;

use App\Models\Quiz\Topic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'topic_id',
        'score',
        'total_questions',
        'user_answers',

    ];

    protected $casts = [
        'user_answers' => 'array', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'topic_id');
    }
}
