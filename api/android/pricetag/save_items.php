<?php
include "../../../config/koneksi.php";

header('Content-Type: application/json');

$header_id = $_POST['header_id'] ?? 0;
$sku = $_POST['sku'] ?? '';

$stmt = $connec->prepare("INSERT INTO price_tag_items(header_id, sku) VALUES(:hid, :sku)");
$stmt->execute(['hid' => $header_id, 'sku' => $sku]);

echo json_encode(['success' => true]);

?>