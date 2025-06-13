<?php
include "../../config/koneksi.php";

$discountname = $_GET['discountname'];

$sql = "SELECT sku, maxqty 
        FROM pos_mproductdiscount_bundling 
        WHERE discountname ILIKE :discountname";
$stmt = $connec->prepare($sql);
$stmt->bindParam(':discountname', $discountname);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['data' => $result]);
?>