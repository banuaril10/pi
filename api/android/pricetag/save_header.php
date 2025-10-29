<?php
include "../../../config/koneksi.php";

header('Content-Type: application/json');

$header_number = $_POST['header_number'] ?? '';
$created_by = $_POST['created_by'] ?? '';

$stmt = $connec->prepare("INSERT INTO price_tag_headers(header_number, created_by) VALUES(:hn, :cb) RETURNING id");
$stmt->execute(['hn' => $header_number, 'cb' => $created_by]);
$row = $stmt->fetch();

echo json_encode(['success' => true, 'header_id' => $row['id']]);
?>