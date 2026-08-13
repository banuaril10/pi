<?php
include "../config/koneksi.php";

header('Content-Type: application/json; charset=utf-8');

$data = [
    [
        "id" => 1,
        "name" => "Telat Settlement"
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
        "name" => "Proses Investigasi"
    ],
    [
        "id" => 5,
        "name" => "Tidak Keluar Struk, Saldo Customer Terdebet Double"
    ],
    [
        "id" => 6,
        "name" => "Salah Metode Transaksi Non Cash ke Cash"
    ],
    [
        "id" => 7,
        "name" => "Trouble Jaringan"
    ]
];

echo json_encode($data);