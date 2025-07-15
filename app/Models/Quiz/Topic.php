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
    ];

    public $timestamps = false;

    /**
     * Relasi ke model Subject.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }

    /**
     * Relasi ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi ke model Question.
     */
    public function questions()
    {
        return $this->hasMany(Question::class, 'topic_id', 'topic_id');
    }

    // --- BAGIAN YANG PERLU DITAMBAHKAN --- //
    /**
     * Mengambil kunci route untuk model.
     * Mengganti pencarian default dari ID ke 'topic_name'.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'topic_name';
    }
    // ------------------------------------ //

    protected function modalData(): Attribute
    {
        return Attribute::make(
            get: fn () => [
                'id' => $this->topic_id,
                'topic_name' => $this->topic_name,
                'subject_name' => $this->subject->subject_name,
                'question_count' => $this->questions_count, // Dari withCount()
                'image_url' => $this->image_url ?? 'https://placehold.co/600x400/F5F5F5/fbbf24?text=' . urlencode($this->topic_name),
            ],
        );
    }
}