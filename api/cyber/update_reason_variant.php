<?php
include "../../config/koneksi.php";

/* ===============================
   AMBIL DATA POST
================================ */
$pos_dcashierbalance_key = $_POST['pos_dcashierbalance_key'] ?? '';
$reason  = $_POST['reason'] ?? '';
$variant = $_POST['variant'] ?? '';

/* ===============================
   VALIDASI
================================ */
if ($pos_dcashierbalance_key == '' || trim($reason) == '') {
    echo json_encode([
        'result' => '0',
        'msg'    => 'Data tidak lengkap'
    ]);
    exit;
}

/* ===============================
   UPDATE DATA
================================ */
$sql = "
    UPDATE pos_dcashierbalance
    SET 
        notes = :reason
    WHERE pos_dcashierbalance_key = :key
";

$stmt = $connec->prepare($sql);
$stmt->bindParam(':reason', $reason);
$stmt->bindParam(':key', $pos_dcashierbalance_key);

if ($stmt->execute()) {
    $json = [
        'result' => '1',
        'msg'    => 'Alasan selisih kas berhasil disimpan',
        'key'    => $pos_dcashierbalance_key
    ];
} else {
    $json = [
        'result' => '0',
        'msg'    => 'Gagal menyimpan alasan selisih'
    ];
}

echo json_encode($json);
