<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    // Nama tabel yang direpresentasikan oleh model ini.
    // Laravel secara default akan mencari tabel 'subjects' (bentuk plural dari 'Subject').
    // Jika nama tabel Anda berbeda, Anda perlu menentukannya: protected $table = 'nama_tabel_anda';
    protected $table = 'subjects';

    // Nama primary key untuk tabel ini.
    // Laravel secara default mengasumsikan 'id'. Karena Anda menggunakan 'subject_id', kita perlu menentukannya.
    protected $primaryKey = 'subject_id';

    // Kolom yang dapat diisi secara massal (mass assignable).
    // Ini penting untuk keamanan agar tidak ada kolom yang tidak diinginkan diisi.
    protected $fillable = [
        'subject_name',
    ];

    // Jika Anda tidak menggunakan timestamps (created_at dan updated_at), set ini ke false.
    // Berdasarkan migrasi Anda, timestamps dikomentari, jadi kita asumsikan tidak digunakan.
    public $timestamps = false;

    /**
     * Mendefinisikan relasi One-to-Many dengan model Topic.
     * Sebuah Subject memiliki banyak Topics.
     */
    public function topics()
    {
        // 'Topic::class' adalah model yang berelasi.
        // 'subject_id' adalah foreign key di tabel 'topics'.
        // 'subject_id' adalah local key di tabel 'subjects'.
        return $this->hasMany(Topic::class, 'subject_id', 'subject_id');
    }
}
