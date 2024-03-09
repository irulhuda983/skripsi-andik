<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vaksin;
use App\Models\Bulan;
use App\Models\TransaksiVaksin;
use App\Http\Resources\VaksinResource;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VaksinController extends Controller
{
    
    public function index(Request $request)
    {
        $user = $request->user();

        list($sort, $dir) = explode(':', $request->sort ?? 'created_at:desc');
        $limit = $request->limit ?? 10;
        $request->showJumlahTransaksi = true;

        $query = Vaksin::search($request->search)
                        ->orderBy($sort, $dir)
                        ->paginate($limit);

        return VaksinResource::collection($query);
    }

    public function listByBulan(Request $request, Vaksin $vaksin)
    {
        $tahun = $request->tahun ?? date('Y');
        $bulan = Bulan::all();

        $data = [];

        foreach ($bulan as $bln) {
            $transaksi = TransaksiVaksin::filterVaksin($vaksin->id)
                                        ->filterTahun($tahun)
                                        ->filterJenis('jual')
                                        ->filterBulan($bln->kode)
                                        ->first();

            $dt = [
                'kodeBulan' => $bln->kode,
                'namaBulan' => $bln->nama,
                'idTransaksi' => $transaksi ? $transaksi->id : null,
                'qty' => $transaksi ? $transaksi->qty : 0,
            ];

            array_push($data, $dt);
        }

        return response()->json([
            'tahun' => $tahun,
            'dataVaksin' => new VaksinResource($vaksin),
            'dataPerBulan' => $data,
        ], 200);
    }

    public function show(Vaksin $vaksin)
    {
        return new VaksinResource($vaksin);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        try{
            DB::beginTransaction();

            $vaksin = Vaksin::create([
                'kode' => null,
                'nama' => $request->nama,
            ]);

            DB::commit();
            return response()->json(['message' => 'Success', 'data' => new VaksinResource($vaksin)], 200);
        }catch(\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function updateTransaksi(Request $request, Vaksin $vaksin)
    {
        $user = $request->user();

        $request->validate([
            'tahun' => 'required',
            'transaksi' => 'required',
            // 'transaksi.*.kodeBulan' => 'required',
            // 'transaksi.*.qty' => 'required',
        ]);

        try{
            DB::beginTransaction();

            $data = [];
            $dataTransaksi = json_decode($request->transaksi, true);

            if(count($dataTransaksi) > 0) {
                foreach($dataTransaksi as $transaksi) {
                    $tgl = '01-'.$transaksi['kodeBulan'].'-'.$request->tahun;
                    $insertTransaksi = TransaksiVaksin::updateOrCreate(
                        ['id_vaksin' => $vaksin->id, 'bulan' => $transaksi['kodeBulan'], 'jenis_transaksi' => 'jual', 'tahun' => $request->tahun],
                        [
                            'id_user' => $user->id,
                            'tanggal' => date('Y-m-d H:i:s', strtotime($tgl)),
                            'qty' => $transaksi['qty'],
                        ]
                    );

                    array_push($data, $insertTransaksi);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Success updated data', 'data' => $data], 200);
        }catch(\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function update(Request $request, Vaksin $vaksin)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        try{
            DB::beginTransaction();

            $vaksin->update([
                'kode' => null,
                'nama' => $request->nama,
            ]);

            DB::commit();
            return response()->json(['message' => 'Success', 'data' => new VaksinResource($vaksin)], 200);
        }catch(\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function destroy(Vaksin $vaksin)
    {
        try{
            DB::beginTransaction();

            TransaksiVaksin::where('id_vaksin', $vaksin->id)->delete();

            $vaksin->delete();

            DB::commit();
            return response()->json(['message' => 'Success delete data'], 200);
        }catch(\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function destroyTransaksi(Request $request, Vaksin $vaksin)
    {
        $request->validate([
            'idsTransaksi' => 'required|array',
        ]);

        try{
            DB::beginTransaction();

            $listDelete = [];

            if(count($request->idsTransaksi) > 0) {
                foreach($request->idsTransaksi as $ids) {
                    $transaksi = TransaksiVaksin::where('id', $ids)->first();

                    if($transaksi) {
                        $transaksi->delete();

                        array_push($listDelete, 1);
                    }

                    array_push($listDelete, 0);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Success delete'. array_sum($listDelete) .'transaksi'], 200);
        }catch(\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
