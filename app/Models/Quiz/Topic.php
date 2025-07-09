<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $table = 'topics';

    // Primary key adalah 'topic_id'
    protected $primaryKey = 'topic_id';

    protected $fillable = [
        'topic_name',
        'subject_id', // Foreign key juga termasuk fillable jika ingin diisi secara massal
    ];

    // Asumsikan tidak menggunakan timestamps
    public $timestamps = false;

    /**
     * Mendefinisikan relasi Many-to-One dengan model Subject.
     * Sebuah Topic dimiliki oleh satu Subject.
     */
    public function subject()
    {
        // 'Subject::class' adalah model yang berelasi.
        // 'subject_id' adalah foreign key di tabel 'topics'.
        // 'subject_id' adalah local key di tabel 'subjects'.
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }

    /**
     * Mendefinisikan relasi One-to-Many dengan model Question.
     * Sebuah Topic memiliki banyak Questions.
     */
    public function questions()
    {
        // 'Question::class' adalah model yang berelasi.
        // 'topic_id' adalah foreign key di tabel 'questions'.
        // 'topic_id' adalah local key di tabel 'topics'.
        return $this->hasMany(Question::class, 'topic_id', 'topic_id');
    }
}
