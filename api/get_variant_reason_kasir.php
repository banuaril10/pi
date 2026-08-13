<?php
include "../config/koneksi.php";

$data = [
    [
        "id" => 1,
        "name" => "Uang modal terhitung Cash Drawer saat EOP"
    ],
    [
        "id" => 2,
        "name" => "Salah input nominal cash fisik saat EOD"
    ],
    [
        "id" => 3,
        "name" => "Salah kembalian"
    ],
    [
        "id" => 4,
        "name" => "Salah akun user saat tarik laporan per kasir"
    ],
    [
        "id" => 5,
        "name" => "Salah metode transaksi cash ke non cash"
    ],
    [
        "id" => 6,
        "name" => "Tidak transaksi di POS"
    ]
];

echo json_encode($data);