<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Bulan;
use App\Models\TransaksiVaksin;

class VaksinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'kode' => $this->kode,
            'nama' => $this->nama,
            'createdAt' => $this->created_at,
        ];

        if($request->showJumlahTransaksi) {
            $tahun = $request->tahun ?? date('Y');
            $bulan = Bulan::all();

            foreach ($bulan as $bln) {
                $transaksi = TransaksiVaksin::filterVaksin($this->id)
                                            ->filterTahun($tahun)
                                            ->filterJenis('jual')
                                            ->filterBulan($bln->kode)
                                            ->first();
    
                $data[$bln->nama] = $transaksi ? $transaksi->qty : 0;
            }
        }

        return $data;
    }
}
