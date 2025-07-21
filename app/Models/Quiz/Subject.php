<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';
    protected $primaryKey = 'subject_id';
    protected $fillable = [
        'subject_name',
    ];

    public $timestamps = false;
    public function topics()
    {
        return $this->hasMany(Topic::class, 'subject_id', 'subject_id');
    }
}
