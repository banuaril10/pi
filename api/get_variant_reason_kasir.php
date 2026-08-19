<?php
include "../config/koneksi.php";

header('Content-Type: application/json; charset=utf-8');

$data = [
    [
        "id" => 1,
        "name" => "Uang Fisik Kurang"
    ],
    [
        "id" => 2,
        "name" => "Uang Fisik Lebih"
    ],
    [
        "id" => 3,
        "name" => "Salah kembalian"
    ],
    [
        "id" => 4,
        "name" => "Selisih Antar Kasir"
    ],
    [
        "id" => 5,
        "name" => "Gangguan Sistem/POS"
    ],
    [
        "id" => 6,
        "name" => "Salah Input Nominal Cash"
    ],
    [
        "id" => 7,
        "name" => "Transaksi Belum Terinput"
    ],
    [
        "id" => 8,
        "name" => "Transaksi Terinput Dua Kali"
    ],
    [
        "id" => 9,
        "name" => "Salah Akun User saat Tarik laporan Per_Kasir"
    ],
    [
        "id" => 10,
        "name" => "Salah Catat Nominal Pick Up Berjalan"
    ],
    [
        "id" => 11,
        "name" => "Nota manual belum di Input ke POS"
    ],
    [
        "id" => 12,
        "name" => "Salah Metode Pembayaran Cash_Non Cash"
    ],
    [
        "id" => 13,
        "name" => "Salah Metode Pembayaran Non Cash_Cash"
    ],
    [
        "id" => 14,
        "name" => "Uang Pribadi Terhitung Cash Drawer"
    ],
    [
        "id" => 15,
        "name" => "Uang Modal Terhitung Cash drawer saat EOD"
    ],
    [
        "id" => 16,
        "name" => "Salah input Nominal Cash Fisik saat EOD"
    ]
];

echo json_encode($data);