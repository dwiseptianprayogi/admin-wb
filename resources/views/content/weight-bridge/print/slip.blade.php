<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip No {{$slip_no}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 5px;
            margin: 0;
            padding: 0;
            /* Set the base font size and remove default margins */
        }

        @page {
            margin: 0;
            /* Kecilkan lebar fisik kertas dari 80mm menjadi 72mm
               supaya hasil cetak sedikit lebih kecil di semua PC */
            size: 72mm auto;
        }

        .header,
        .footer {
            font-size: 5px;
            /* Smaller text for header/footer */
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 5px;
            /* Smaller table text */
        }

        .table th,
        .table td {
            padding: 2px;
            border: 1px solid black;
            text-align: left;
        }

        .title {
            font-size: 5px;
            font-weight: bold;
            /* Slightly larger for the title */
        }

        .doc-name {
            font-size: 5px;
            font-weight: bolder;
            /* Slightly larger for the title */
        }

        .small-text {
            font-size: 5px;
            /* The smallest text */
        }
    </style>
</head>

<body>
    <pre style="font-family: monospace; font-size: 5px; line-height: 1.1; margin: 0;">@php
        // Kecilkan lagi lebar slip dengan mengurangi jumlah karakter per baris
        $w = 37;
        $border = '+' . str_repeat('-', $w) . '+';
        $emptyLine = '|' . str_repeat(' ', $w) . '|';
        $center = function ($text) use ($w) {
            return '|' . str_pad(mb_substr($text, 0, $w), $w, ' ', STR_PAD_BOTH) . '|';
        };
        $left = function ($text) use ($w) {
            return '|' . str_pad(mb_substr($text, 0, $w), $w) . '|';
        };
        // Helper untuk membungkus teks panjang ke beberapa baris kiri dalam kotak
        $wrapLeft = function ($text) use ($w) {
            $len = mb_strlen($text);
            for ($offset = 0; $offset < $len; $offset += $w) {
                $chunk = mb_substr($text, $offset, $w);
                echo '|' . str_pad($chunk, $w) . "|\n";
            }
        };
        // Helper untuk label + nilai yang bisa turun ke baris berikutnya dengan indent di bawah ':'
        // Prefix memakai label persis (tanpa spasi ekstra) supaya kolom nilai sejajar dengan No.Doc/No Polisi
        $wrapLabelValue = function ($label, $value) use ($w) {
            $prefix = rtrim($label);
            $prefixLen = mb_strlen($prefix);
            $available = max(0, $w - $prefixLen);
            $len = mb_strlen($value);

            // Baris pertama: label + bagian pertama nilai
            $firstChunk = mb_substr($value, 0, $available);
            echo '|' . str_pad($prefix . $firstChunk, $w) . "|\n";

            // Baris lanjutan (jika masih ada sisa nilai)
            for ($offset = $available; $offset < $len; $offset += $available) {
                $chunk = mb_substr($value, $offset, $available);
                // Hilangkan spasi di awal potongan agar baris lanjutan tidak tampak ada "spasi dobel"
                $chunk = ltrim($chunk);
                echo '|' . str_pad(str_repeat(' ', $prefixLen) . $chunk, $w) . "|\n";
            }
        };

        echo $border . "\n";
        echo $center('PT. KERAMINDO MEGAH PERTIWI') . "\n";
        echo $center('TANGERANG') . "\n";
        echo $emptyLine . "\n";
        echo $center('SLIP TIMBANGAN') . "\n";
        echo $border . "\n";


        $direction = ($status == 'FG-OUT' || $status == 'RM-OUT') ? 'Keluar' : 'Masuk';

        if ($weight_type == 'rm') {
            // --- Raw Material: No.Doc dan Date baris terpisah ---
            $wrapLabelValue('No.Doc     :', $slip_no);
            echo $left('Date       :' . date('d-m-Y', strtotime($weight_in_date))) . "\n";
            $nopolLine = 'No Polisi  :' . $vehicle_no . str_repeat(' ', max(0, $w - strlen('No Polisi  :' . $vehicle_no . '  > ' . $direction))) . '> ' . $direction;
            echo $left($nopolLine) . "\n";
            if (!empty($transporter_name)) {
                $wrapLabelValue('Transporter:', $transporter_name);
            }
            echo $border . "\n";
        } else {
            // --- Finish Good: format baru (No.Doc dan Date baris terpisah) ---
            $wrapLabelValue('No.Doc     :', $slip_no);
            echo $left('Date       :' . date('d-m-Y', strtotime($weight_in_date))) . "\n";
            $nopolLine = 'No Polisi  :' . $vehicle_no . str_repeat(' ', max(0, $w - strlen('No Polisi  :' . $vehicle_no . '  > ' . $direction))) . '> ' . $direction;
            echo $left($nopolLine) . "\n";
            // Tampilkan transporter dengan nama yang bisa turun ke bawah ':'
            $wrapLabelValue('Transporter:', $transporter_name);
            echo $border . "\n";
            echo $left('Jenis Kendaraan: ' . $vehicle_type) . "\n";
        }


        // Samakan posisi tanda ':' dengan baris Total QTY dan Keterangan
        echo $left('Jenis Muatan   : ' . ($weight_type == 'rm' ? 'Raw Material' : 'Finish Good')) . "\n";
        echo $left('Total QTY      : ' . number_format(round($total_qty, 0, PHP_ROUND_HALF_UP), 0)) . "\n";
        // Bersihkan prefix "Muatan:" di remark kalau ada, supaya tidak dobel di Keterangan
        $remarkClean = $remark ?? '';
        if ($remarkClean !== '') {
            $remarkClean = preg_replace('/^\s*Muatan\s*:?\s*/i', '', $remarkClean);
        }
        echo $left('Keterangan     : ' . $remarkClean) . "\n";
        echo $border . "\n";

        // Rapikan kolom berat dan Time/Date agar sejajar di semua baris
        $labelCol = 11;  // panjang "Masuk (KG):" / "Keluar(KG):" / "Netto (KG):"
        $weightCol = 8;  // angka + "Kg" rata kanan

        $weightInText = number_format(round($weight_in, 0, PHP_ROUND_HALF_UP), 0) . 'Kg';
        $lineMasuk = str_pad('Masuk (KG):', $labelCol) . ' ' .
            str_pad($weightInText, $weightCol, ' ', STR_PAD_LEFT) . ' Time In:' . $weight_in_time;
        echo $left($lineMasuk) . "\n";

        $weightOutText = number_format(round($weight_out, 0, PHP_ROUND_HALF_UP), 0) . 'Kg';
        $lineKeluar = str_pad('Keluar(KG):', $labelCol) . ' ' .
            str_pad($weightOutText, $weightCol, ' ', STR_PAD_LEFT) . ' Date Out:' .
            ($weight_out_date ? date('d-m-Y', strtotime($weight_out_date)) : '');
        echo $left($lineKeluar) . "\n";

        $weightNettoText = number_format(round($weight_netto, 0, PHP_ROUND_HALF_UP), 0) . 'Kg';
        $lineNetto = str_pad('Netto (KG):', $labelCol) . ' ' .
            str_pad($weightNettoText, $weightCol, ' ', STR_PAD_LEFT) . ' Time Out:' . $weight_out_time;
        echo $left($lineNetto) . "\n";
        echo $border . "\n";

        echo $left('Petugas Timbangan        Pengemudi') . "\n";
        echo $emptyLine . "\n";
        echo $emptyLine . "\n";
        echo $left('(               )    (             )') . "\n";
        echo $emptyLine . "\n";
        echo $emptyLine . "\n";

        if ($weight_type == 'fg' && $status == 'FG-OUT') {

            // Sesuaikan lebar kolom dengan baris 35 karakter
            // Kolom kiri sedikit diperkecil agar kolom Dist Weight tetap muat label penuh
            $col1 = 18; // DO/SPB No
            $col2 = $w - $col1 - 1; // Dist Weight (KG)
            // Header kolom: rapatkan label Dist Weight (KG) ke kiri kolom kanan
            echo $left(str_pad('DO/SPB No:', $col1) . ' ' . str_pad('Dist Weight (KG)', $col2)) . "\n";
            echo $left(str_repeat('-', $col1) . ' ' . str_repeat('-', $col2)) . "\n";

            $totalWeight = 0;
            $targetTotalWeight = round($weight_netto, 0, PHP_ROUND_HALF_UP);

            // Grouping dan jumlahkan Dist Weight per LegalNumber
            $grouped = collect($spb_details)
                ->groupBy('LegalNumber')
                ->map(function ($items) use ($total_berat_standart) {
                    $sum = 0;
                    foreach ($items as $item) {
                        $sum += round($item->TotalNetWeight * $total_berat_standart, 0, PHP_ROUND_HALF_UP);
                    }
                    return $sum;
                });
            // Urutkan berdasarkan angka terkecil dari nomor SPB
            $groupedSorted = $grouped->sortBy(function ($value, $key) {
                // Ambil angka dari format PK.00XXX/XX/XX
                if (preg_match('/PK\\.(\\d+)\\//', $key, $matches)) {
                    return (int) $matches[1];
                }
                return $key;
            });

            $currentIndex = 0;
            $totalItems = $groupedSorted->count();

            foreach ($groupedSorted as $legalNumber => $distWeight) {
                $currentIndex++;

                // Jika ini adalah baris terakhir, sesuaikan distWeight agar totalnya sama persis dengan target
                if ($currentIndex === $totalItems) {
                    $distWeight = $targetTotalWeight - $totalWeight;
                }

                // Angka Dist Weight dibuat rata kanan di dalam kolom kanan
                echo $left(str_pad($legalNumber, $col1) . ' ' . str_pad(number_format($distWeight, 0), $col2, ' ', STR_PAD_LEFT)) . "\n";
                $totalWeight += $distWeight;
            }

            echo $left(str_repeat('-', $col1) . ' ' . str_repeat('-', $col2)) . "\n";
            // Total Weight: angka juga rata kanan dan sejajar dengan baris di atas
            echo $left(str_pad('Total Weight:', $col1) . ' ' . str_pad(number_format($totalWeight, 0), $col2, ' ', STR_PAD_LEFT)) . "\n";
        }

        echo $border . "\n";
    @endphp


</pre>
</body>

</html>