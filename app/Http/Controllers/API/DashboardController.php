<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vaksin;
use App\Models\TransaksiVaksin;
use App\Models\Peramalan;
use App\Models\Bulan;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    
    public function getChart(Request $request)
    {
        return response()->json([
            'jumlah' => $this->getJumlah(),
            'chart' => $this->getGrafik(),
        ], 200);
    }

    public function getGrafik()
    {
        $tanggal = date("Y-m-d");

        $bulanTahun = [];

        for ($i = 0; $i < 12; $i++) {
            $getPeriode = date("Y-m", strtotime( $tanggal." - $i months"));
            
            list($tahun, $bulan) = explode('-', $getPeriode);
            $bulanName = Bulan::where('kode', $bulan)->first();

            $qty = TransaksiVaksin::where('bulan', $bulan)->where('tahun', (int) $tahun)->where('jenis_transaksi', 'jual')->sum('qty');

            $title = Str::limit($bulanName->nama, 3, '').' '.$tahun;

            array_push($bulanTahun, [
                'x' => Str::ucfirst($title),
                'y' => (int) $qty
            ]);
        }

        return [
            ['name' => 'Pengeluaran', 'data' => array_reverse($bulanTahun)]
        ];
    }

    public function getJumlah()
    {
        $jumlahVaksin = Vaksin::count();
        $jumlahTransaksi = TransaksiVaksin::where('jenis_transaksi', 'jual')->sum('qty');
        $jumlahHistory = Peramalan::count();

        return [
            'vaksin' => $jumlahVaksin,
            'transaksi' => number_format($jumlahTransaksi,0, ",", "."),
            'history' => $jumlahHistory,
        ];
    }
}
