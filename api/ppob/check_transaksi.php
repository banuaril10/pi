<?php
include "../../config/koneksi.php";
header('Content-Type: application/json');

// Validasi parameter billno
if (!isset($_GET['billno']) || empty($_GET['billno'])) {
    echo json_encode([
        "status" => "failed",
        "message" => "Parameter 'billno' wajib diisi."
    ]);
    exit;
}

$billno = $_GET['billno'];

try {
    $sql = "SELECT trxid, reqid, pos_dsales_key
            FROM public.pos_dsales_ppob 
            WHERE billno = :billno 
            LIMIT 1";

    $stmt = $connec->prepare($sql);
    $stmt->bindParam(':billno', $billno);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        echo json_encode([
            "status" => "success",
            "data" => $data
        ]);
    } else {
        echo json_encode([
            "status" => "failed",
            "message" => "Data tidak ditemukan untuk Bill No tersebut." . $sql
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "status" => "failed",
        "message" => $e->getMessage()
    ]);
}
