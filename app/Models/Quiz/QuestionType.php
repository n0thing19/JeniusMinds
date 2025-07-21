<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    use HasFactory;

    protected $table = 'question_types';

    protected $primaryKey = 'q_type_id';

    protected $fillable = [
        'type_name',
    ];

    public $timestamps = false;

    public function questions()
    {
        return $this->hasMany(Question::class, 'q_type_id', 'q_type_id');
    }
}
