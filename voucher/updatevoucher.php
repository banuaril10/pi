<?php
// updatevoucher.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

include "../../config/koneksi.php";

$input = json_decode(file_get_contents('php://input'), true);

// Validasi - tanpa memberid
if (!isset($input['vouchercode']) || !isset($input['billno'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

$voucherCode = trim($input['vouchercode']);
$billNo = trim($input['billno']);
$usedAmount = floatval($input['usedamount'] ?? 0);
$currentDate = date('Y-m-d H:i:s');

try {
    // 1. Cek dan lock voucher
    $connec->beginTransaction();
    
    $sql = "SELECT * FROM pos_dvoucher 
            WHERE voucher_code = ? 
            AND status = 'NEW'
            FOR UPDATE";
    
    $stmt = $connec->prepare($sql);
    $stmt->execute([$voucherCode]);
    $voucher = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$voucher) {
        $connec->rollBack();
        echo json_encode(['success' => false, 'message' => 'Voucher tidak valid']);
        exit;
    }
    
    // 2. Update status voucher (tanpa membercard)
    $updateSql = "UPDATE pos_dvoucher 
                  SET pos_dsales_key = ?,
                      usedamount = ?,
                      useddate = ?,
                      status = 'USED'
                  WHERE pos_dvoucher_key = ? 
                  AND status = 'NEW'";
    
    $updateStmt = $connec->prepare($updateSql);
    $result = $updateStmt->execute([
        $billNo,
        $usedAmount,
        $currentDate,
        $voucher['pos_dvoucher_key']
    ]);
    
    if ($result && $updateStmt->rowCount() > 0) {
        $connec->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Voucher berhasil digunakan',
            'voucher_key' => $voucher['pos_dvoucher_key']
        ]);
    } else {
        $connec->rollBack();
        echo json_encode(['success' => false, 'message' => 'Gagal update voucher']);
    }
    
} catch (PDOException $e) {
    $connec->rollBack();
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>