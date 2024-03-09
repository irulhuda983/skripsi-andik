<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Bulan;

class PeramalanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'idVaksin' => $this->id_vaksin,
            'vaksin' => $this->vaksin->nama,
            'tanggal' => $this->tanggal,
            'bulan' => $this->bulan,
            'namaBulan' => ucfirst(Bulan::where('kode', $this->bulan)->first()->nama),
            'tahun' => $this->tahun,
            'alpha' => (float) $this->nilai_alpha,
            'hasil' => json_decode($this->hasil),
            'createdAt' => $this->created_at,
        ];
    }
}
