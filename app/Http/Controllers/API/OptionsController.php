<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Bulan;
use App\Models\TransaksiVaksin;
use App\Models\Vaksin;

class OptionsController extends Controller
{
    
    public function tahun(Request $request)
    {
        $awal = 2019;
        $akhir = date('Y') + 5;

        $data = [];

        for($i = $akhir; $i >= $awal; $i--) {
            array_push($data, [
                'id' => $i,
                'text' => $i,
            ]);
        }

        return response()->json(compact('data'), 200);
    }

    public function bulan(Request $request)
    {
        $query = Bulan::select('kode', 'nama as text')->get();

        return response()->json(['data' => $query], 200);
    }

    public function vaksin(Request $request)
    {
        $query = Vaksin::select('id', 'nama as text')->search($request->search)->get();

        return response()->json(['data' => $query], 200);
    }
}
