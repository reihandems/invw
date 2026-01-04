<?php

if (!function_exists('rupiah')) {
    function rupiah($angka)
    {
        return 'Rp. ' . number_format((float)$angka, 0, ',', '.');
    }

    function formatNominal($nominal, $type)
    {
        $sign  = $type === 'Pemasukan' ? '+' : '-';
        $class = $type === 'Pemasukan'
            ? 'text-primary-500'
            : 'text-danger-500';

        return "<span class='{$class} font-semibold'>{$sign}Rp. " .
            number_format((float)$nominal, 0, ',', '.') .
            "</span>";
    }

    if (!function_exists('tanggalIndo')) {
        function tanggalIndo($date)
        {
            return date('d F Y', strtotime($date));
        }
    }

    if (!function_exists('formatJam')) {
        function formatJam($time)
        {
            return date('H:i', strtotime($time));
        }
    }

    if (!function_exists('labelTanggal')) {
    function labelTanggal($date)
    {
        $tanggal = date('Y-m-d', strtotime($date));
        $hariIni = date('Y-m-d');
        $kemarin = date('Y-m-d', strtotime('-1 day'));

        if ($tanggal === $hariIni) {
            return 'Hari ini';
        } elseif ($tanggal === $kemarin) {
            return 'Kemarin';
        } else {
            return tanggalIndo($tanggal);
        }
    }

    function formatBadge($type)
    {
        $class = $type === 'Pemasukan'
            ? 'bg-primary-200'
            : 'bg-danger-200';

        return "<div class='badge {$class} text-dark-text p-4'>{$type}</div>";
    }
    
}

}
