<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\TransaksiVaksin;
use App\Models\Bulan;
use App\Models\Vaksin;
use Carbon\Carbon;

class ForecastService {

    public $idVaksin = null;
    public $alpha = null;
    public $periode = null;
    public $bulan = null;
    public $tahun = null;

    public function __construct($idVaksin, $periode, $alpha, $bulan, $tahun)
    {
        $this->idVaksin = $idVaksin;
        $this->periode = (int) $periode;
        $this->alpha = $alpha;
        $this->bulan = $bulan;
        $this->tahun = (int) $tahun;
    }

    public function generate()
    {
        $forecastData = $this->getForecastData();
        $nextData = $this->getNextData();
        $lastData = end( $forecastData );

        $hasilForecast = $this->rumusForecast($lastData['actual'], $lastData['forecast'], $this->alpha, 1);

        $nextForecast = [
            'bulan' => $nextData['bulan'],
            'namaBulan' => $nextData['namaBulan'],
            'tahun' => $nextData['tahun'],
            'actual' => $nextData['actual'],
            'forecast' => $hasilForecast,
            'mad' => null,
            'mse' => null,
            'mape' => null,
        ];

        $averageData = $this->getAverage($forecastData);

        return [
            'detailForecast' => $forecastData,
            'nextForecast' => $nextForecast,
            'average' => $averageData
        ];
    }

    public function getAverage($arr)
    {
        $averageMad = 0;
        $averageMse = 0;
        $averageMape = 0;

        if(count($arr) == 0) {
            $averageMad = 0;
            $averageMse = 0;
            $averageMape = 0;
        }

        $listMad = [];
        $listMse = [];
        $listMape = [];

        foreach($arr as $key => $item)
        {
            if($key > 0) {
                array_push($listMad, $item['mad']);
                array_push($listMse, $item['mse']);
                array_push($listMape, $item['mape']);
            }
        }

        return [
            'mad' => array_sum($listMad) / count($listMad),
            'mse' => array_sum($listMse) / count($listMse),
            'mape' => array_sum($listMape) / count($listMape),
        ];
    }

    public function getActualData()
    {
        $now = $this->tahun.'-'.$this->bulan.'-01';

        $tanggal = date("Y-m-d", strtotime( $now." - 1 months"));

        $bulanTahun = [];

        for ($i = 0; $i < (int) $this->periode; $i++) {
            $getPeriode = date("Y-m", strtotime( $tanggal." - $i months"));
            
            list($tahun, $bulan) = explode('-', $getPeriode);
            $bulanName = Bulan::where('kode', $bulan)->first();

            $qty = TransaksiVaksin::where('id_vaksin', $this->idVaksin)->where('bulan', $bulan)->where('tahun', (int) $tahun)->where('jenis_transaksi', 'jual')->sum('qty');

            array_push($bulanTahun, [
                'bulan' => $bulan,
                'namaBulan' => $bulanName->nama,
                'tahun' => (int) $tahun,
                'actual' => (int) $qty
            ]);
        }
        return array_reverse($bulanTahun);
    }

    public function getForecastData()
    {
        $actualData = $this->getActualData();

        if(count($actualData) == 0) {
            return [];
        }

        $forecastData = [];

        foreach ($actualData as $key => $item) {

            if($key == 0) {
                $hasilMad = $this->rumusMad($item['actual'], $item['actual'], $key);
                $hasilMse = $this->rumusMse($item['actual'], $item['actual'], $key);
                $hasilMape = $this->rumusMape($item['actual'], $item['actual']);
                $arrList = [
                    'bulan' => $item['bulan'],
                    'namaBulan' => $item['namaBulan'],
                    'tahun' => $item['tahun'],
                    'actual' => $item['actual'],
                    'forecast' => $item['actual'],
                    'mad' => $hasilMad,
                    'mse' => $hasilMse,
                    'mape' => $hasilMape,
                ];
            }else{

                $lastData = $forecastData[$key - 1];
                $hasilForecast = $this->rumusForecast($lastData['actual'], $lastData['forecast'], $this->alpha, $key);
                $hasilMad = $this->rumusMad($item['actual'], $hasilForecast, $key);
                $hasilMse = $this->rumusMse($item['actual'], $hasilForecast, $key);
                $hasilMape = $this->rumusMape($item['actual'], $hasilForecast);
    
                $arrList = [
                    'bulan' => $item['bulan'],
                    'namaBulan' => $item['namaBulan'],
                    'tahun' => $item['tahun'],
                    'actual' => $item['actual'],
                    'forecast' => $hasilForecast,
                    'mad' => $hasilMad,
                    'mse' => $hasilMse,
                    'mape' => $hasilMape,
                ];
            }

            array_push($forecastData, $arrList);
        }

        return $forecastData;
    }

    public function getNextData()
    {
        $now = $this->tahun.'-'.$this->bulan.'-01';

        $getPeriode = date("Y-m", strtotime( $now ));
            
        list($tahun, $bulan) = explode('-', $getPeriode);
        $bulanName = Bulan::where('kode', $bulan)->first();

        $data = [
            'bulan' => $bulan,
            'namaBulan' => $bulanName->nama,
            'tahun' => (int) $tahun,
            'actual' => '?'
        ];

        return $data;
    }

    public function rumusForecast($act, $frc, $alpha, $index = null)
    {
        if($index == 0) {
            return (float) $act;
        }

        $hasil = $act > 0 ? (float) $frc + ( (float) $alpha * ( (int) $act - (float) $frc) ) : 0;

        return (float) $hasil;
    }

    public function rumusMad($act, $fcr, $index = null)
    {
        if($index == 0) {
            return (float) abs((float) $act - (float) $fcr);
        }

        // $hasil = $act > 0 ? abs((float) $act - (float) $fcr) / ((int) $this->periode - 1) : 0;
        $hasil = $act > 0 ? abs((float) $act - (float) $fcr) : 0;

        return (float) $hasil;
    }

    public function rumusMse($act, $fcr, $index = null)
    {
        if($index == 0) {
            return (float) pow( ((float) $act - (float) $fcr), 2 );
        }

        $hasil = $act > 0 ? pow( ((float) $act - (float) $fcr), 2 ) / ((int) $this->periode - 1) : 0;

        return (float) $hasil;
    }

    public function rumusMape($act, $fcr)
    {

        $hasil = $act > 0 ? abs((float) $act - (float) $fcr) / (float) $act : 0;

        return (float) $hasil;
    }
}