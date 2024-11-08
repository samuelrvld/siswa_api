<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scantum\HasApiTokens;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'kelas', 'umur'];

}
