<table border="1">
    <thead>
        <tr>
            <th colspan="7">α = {{ $alpha }}</th>
        </tr>
    </thead>
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Bulan</th>
            <th rowspan="2">Aktual</th>
            <th rowspan="2">Forecast</th>
            <th>MAD</th>
            <th>MSE</th>
            <th>MAPE</th>
        </tr>
        <tr>
            <th>(At-Ft) / n</th>
            <th>(Xt-Ft)^2/n</th>
            <th>(At-Ft)/At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($detail as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ ucwords($item['namaBulan']) }} - {{ $item['tahun'] }}</td>
            <td>{{ $item['actual'] }}</td>
            <td>{{ $item['forecast'] }}</td>
            <td>{{ $item['mad'] }}</td>
            <td>{{ $item['mse'] }}</td>
            <td>{{ $item['mape'] }}</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="3">Rata-rata</td>
            <td></td>
            <td>{{ $average['mad'] }}</td>
            <td>{{ $average['mse'] }}</td>
            <td>{{ $average['mape'] }}</td>
        </tr>
        <tr>
            <td colspan="3">Prediksi {{ ucwords($next['namaBulan']) }} {{ $next['tahun'] }}</td>
            <td>{{ $next['forecast'] }}</td>
            <td colspan="3">{{ $average['mape'] * 100 }}</td>
        </tr>
    </tbody>
</table>