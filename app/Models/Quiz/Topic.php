<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    protected function modalData(): Attribute
    {
        return Attribute::make(
            get: fn () => [
                'id' => $this->topic_id,
                'topic_name' => $this->topic_name,
                'subject_name' => $this->subject->subject_name,
                'question_count' => $this->questions_count, // Dari withCount()
                'image_url' => $this->image_url ?? 'https://placehold.co/600x400/F5F5F5/fbbf24?text=' . urlencode($this->topic_name), // Ganti dengan path gambar Anda jika ada
                // Tambahkan data lain yang dibutuhkan di sini
            ],
        );
    }
}
