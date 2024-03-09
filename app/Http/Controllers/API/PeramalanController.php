<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Peramalan;
use App\Models\Vaksin;
use App\Models\TransaksiVaksin;
use App\Services\ForecastService;
use App\Http\Resources\PeramalanResource;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PeramalanController extends Controller
{
    
    public function index(Request $request)
    {
        $user = $request->user();

        list($sort, $dir) = explode(':', $request->sort ?? 'created_at:desc');
        $limit = $request->limit ?? 10;

        $query = Peramalan::orderBy($sort, $dir)
                        ->paginate($limit);

        return PeramalanResource::collection($query);
    }

    public function show(Peramalan $peramalan)
    {
        return new PeramalanResource($peramalan);
    }

    public function getForecast(Request $request)
    {
        $request->validate([
            'idVaksin' => 'required',
            'periode' => 'required',
            'alpha' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        $peramalanService = new ForecastService($request->idVaksin, $request->periode, $request->alpha, $request->bulan, $request->tahun);

        $forecast = $peramalanService->generate();

        $next = $forecast['nextForecast'];

        $data = [
            'idVaksin' => $request->idVaksin,
            'vaksin' => Vaksin::find($request->idVaksin)->nama,
            'alpha' => (float) $request->alpha,
            'periode' => $request->periode,
            'tanggal' => $next['tahun'].'-'.$next['bulan'].'-01',
            'bulan' => $next['bulan'],
            'tahun' => $next['tahun'],
            'hasil' => $forecast,
        ];

        return response()->json(compact('data'), 200);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'idVaksin' => 'required',
            'periode' => 'required',
            'alpha' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        try{
            DB::beginTransaction();

            $peramalanService = new ForecastService($request->idVaksin, $request->periode, $request->alpha, $request->bulan, $request->tahun);

            $forecast = $peramalanService->generate();

            $next = $forecast['nextForecast'];

            Peramalan::create([
                'id_user' => $user->id,
                'id_vaksin' => $request->idVaksin,
                'tanggal' => $next['tahun'].'-'.$next['bulan'].'-01',
                'bulan' => $next['bulan'],
                'tahun' => $next['tahun'],
                'nilai_alpha' => (float) $request->alpha,
                'hasil' => json_encode($forecast),
            ]);

            DB::commit();
            return response()->json(['message' => 'Success save peramalan'], 200);
        }catch(\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function destroy(Peramalan $peramalan)
    {
        try{
            DB::beginTransaction();

            $peramalan->delete();

            DB::commit();
            return response()->json(['message' => 'Success delete data'], 200);
        }catch(\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
