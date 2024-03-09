<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Bulan extends Model
{
    use HasFactory;

    protected $table = 'bulan';

    protected $guarded = ['id'];

    public function scopeSearch($query, $search)
    {
        if($search) {
            $query->whereIn('id',
                DB::table('bulan')
                ->select('bulan.id')
                ->whereRaw('LOWER(bulan.nama) like ?', ['%' . strtolower($search) . '%'])
                ->pluck('id')
                ->all()
            );
        }

        return $query;
    }
}
