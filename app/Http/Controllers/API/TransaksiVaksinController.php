<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vaksin;
use App\Models\TransaksiVaksin;
use App\Http\Resources\TransaksiVaksinResource;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransaksiVaksinController extends Controller
{
    
    public function showByVaksin(Request $request, Vaksin $vaksin)
    {
        $tahun = $request->tahun;

        $query = TransaksiVaksin::filterJenis('jual')->filterTahun($tahun)->get();
    }
}
