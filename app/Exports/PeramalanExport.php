<?php

namespace App\Exports;

use App\Models\Peramalan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PeramalanExport implements FromView
{

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.peramalan', $this->data);
    }
}
