<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;

    protected $table = 'choices';

    protected $primaryKey = 'choice_id';

    protected $fillable = [
        'choice_text',
        'is_correct',
        'correct_order',
        'question_id', 
    ];

    public $timestamps = false;

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'question_id');
    }
}
