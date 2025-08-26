<?php
include "../../config/koneksi.php";

$tanggalHariIni = date('Y-m-d');

// Query cek status
$sql = "
    SELECT 1
    FROM pos_dshopsales
    WHERE status = 'DONE'
      AND salesdate::date = :today
    LIMIT 1";

$stmt = $connec->prepare($sql);
$stmt->execute(['today' => $tanggalHariIni]);
$data = $stmt->fetch();

echo $data ? "1" : "0";

?>