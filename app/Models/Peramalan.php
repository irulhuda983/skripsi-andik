<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Peramalan extends Model
{
    use HasFactory;

    protected $table = 'peramalan_vaksin';

    protected $guarded = ['id'];

    public function vaksin()
    {
        return $this->belongsTo(Vaksin::class, 'id_vaksin');
    }
}
