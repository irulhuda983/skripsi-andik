<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class TransaksiVaksin extends Model
{
    use HasFactory;

    protected $table = 'transaksi_vaksin';

    protected $guarded = ['id'];

    public function vaksin()
    {
        return $this->belongsTo(Vaksin::class, 'id_vaksin');
    }

    public function scopeFilterVaksin($query, $idVaksin)
    {
        if($idVaksin) {
            $query->where('id_vaksin', $idVaksin);
        }

        return $query;
    }

    public function scopeFilterJenis($query, $jenis)
    {
        if($jenis) {
            $query->where('jenis_transaksi', $jenis);
        }

        return $query;
    }

    public function scopeFilterBulan($query, $bulan)
    {
        if($bulan) {
            $query->where('bulan', $bulan);
        }

        return $query;
    }

    public function scopeFilterTahun($query, $tahun)
    {
        if($tahun) {
            $query->where('tahun', $tahun);
        }

        return $query;
    }
}
