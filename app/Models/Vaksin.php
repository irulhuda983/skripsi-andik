<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Vaksin extends Model
{
    use HasFactory;

    protected $table = 'vaksin';

    protected $guarded = ['id'];

    public function transaksi()
    {
        return $this->hasMany(TransaksiVaksin::class, 'id_vaksin');
    }

    public function scopeFilterJenisTransaksi($query, $jenis)
    {
        if($jenis) {
            $query->whereIn('id',
                DB::table('vaksin')
                ->select('vaksin.id')
                ->leftjoin('transaksi_vaksin', 'vaksin.id', '=', 'transaksi_vaksin.id_vaksin')
                ->where('transaksi_vaksin.jenis_transaksi', $jenis)
                ->pluck('id')
                ->all()
            );
        }

        return $query;
    }

    public function scopeFilterTahun($query, $tahun)
    {
        if($tahun) {
            $query->whereIn('id',
                DB::table('vaksin')
                ->select('vaksin.id')
                ->leftjoin('transaksi_vaksin', 'vaksin.id', '=', 'transaksi_vaksin.id_vaksin')
                ->where('transaksi_vaksin.tahun', $tahun)
                ->pluck('id')
                ->all()
            );
        }

        return $query;
    }

    public function scopeFilterBulan($query, $bulan)
    {
        if($bulan) {
            $query->whereIn('id',
                DB::table('vaksin')
                ->select('vaksin.id')
                ->leftjoin('transaksi_vaksin', 'vaksin.id', '=', 'transaksi_vaksin.id_vaksin')
                ->where('transaksi_vaksin.bulan', $bulan)
                ->pluck('id')
                ->all()
            );
        }

        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if($search) {
            $query->whereIn('id',
                DB::table('vaksin')
                ->select('vaksin.id')
                ->whereRaw('LOWER(vaksin.nama) like ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(vaksin.kode) like ?', ['%' . strtolower($search) . '%'])
                ->pluck('id')
                ->all()
            );
        }

        return $query;
    }
}
