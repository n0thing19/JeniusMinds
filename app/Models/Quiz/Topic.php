<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\User;

class Topic extends Model
{
    use HasFactory;

    protected $table = 'topics';
    protected $primaryKey = 'topic_id';

    protected $fillable = [
        'topic_name',
        'subject_id',
        'user_id',
        'code',
    ];

    public $timestamps = false;

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'topic_id', 'topic_id');
    }

    /**
     * Accessor untuk menghasilkan data modal secara dinamis.
     * Termasuk URL gambar dari placehold.co dengan warna yang sesuai.
     */
    protected function modalData(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Definisikan data warna untuk setiap subjek di sini.
                $subjectColors = [
                    'Mathematics' => ['FEE394', 'a16207'],
                    'English'     => ['FFE1D6', 'c2410c'],
                    'Chemistry'   => ['C9E3F2', '1d4ed8'],
                    'Computers'   => ['DEDDE8', '5b21b6'],
                    'Biology'     => ['F1F2E2', '4d7c0f'],
                    'Economy'     => ['E5E7EB', '374151'],
                    'Geography'   => ['EEF5F8', '0e7490'],
                    'Physics'     => ['FEF08A', 'a16207'],
                    'Music'       => ['E0E7FF', '4338ca'],
                    'Sports'      => ['FEE2E2', 'b91c1c'],
                    'Mandarin'    => ['FEFCE8', 'b45309'],
                ];

                // Ambil warna untuk subjek saat ini, atau gunakan warna default.
                $colors = $subjectColors[$this->subject->subject_name] ?? ['E5E7EB', '4B5563'];
                $bgColor = $colors[0];
                $textColor = $colors[1];

                // Buat URL gambar dari placehold.co
                $imageUrl = "https://placehold.co/600x400/{$bgColor}/{$textColor}?text=" . urlencode($this->topic_name);

                // Kembalikan semua data yang dibutuhkan oleh modal.
                return [
                    'id' => $this->topic_id,
                    'topic_name' => $this->topic_name,
                    'subject_name' => $this->subject->subject_name,
                    'question_count' => $this->questions_count, // Dari withCount()
                    'image_url' => $imageUrl,
                ];
            },
        );
    }
}
