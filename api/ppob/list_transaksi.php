<?php
include "../../config/koneksi.php";
header('Content-Type: application/json');

try {
    $sql = "SELECT 
                trxid, 
                billcode, 
                billamount, 
                insertdate, 
                billstatus, 
                membername, 
                keterangan 
            FROM public.pos_dsales_ppob 
            ORDER BY insertdate DESC 
            LIMIT 100";

    $stmt = $connec->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $result
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "failed",
        "message" => $e->getMessage()
    ]);
}
?>