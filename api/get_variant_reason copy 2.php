<?php
include "../config/koneksi.php";

header('Content-Type: application/json; charset=utf-8');

$data = [
    [
        "id" => 1,
        "name" => "Transaksi Gagal"
    ],
    [
        "id" => 2,
        "name" => "Belum Settlement"
    ],
    [
        "id" => 3,
        "name" => "Transaksi Pending"
    ],
    [
        "id" => 4,
        "name" => "Transaksi Reversal"
    ],
    [
        "id" => 5,
        "name" => "Selisih Pembulatan"
    ],
    [
        "id" => 6,
        "name" => "Salah Nominal di EDC"
    ],
    [
        "id" => 7,
        "name" => "Double Transaksi EDC"
    ],
    [
        "id" => 8,
        "name" => "Double Transaksi POS"
    ],
    [
        "id" => 9,
        "name" => "Gangguan EDC / Payment"
    ],
    [
        "id" => 10,
        "name" => "Lainnya – Proses Validasi"
    ],
    [
        "id" => 11,
        "name" => "Salah Metode Pembayaran antar EDC"
    ],
    [
        "id" => 12,
        "name" => "Tidak keluar struk EDC Saldo CS Terdebit"
    ],
    [
        "id" => 13,
        "name" => "Salah Metode Pembayaran Non Cash_Cash"
    ]
];

echo json_encode($data);