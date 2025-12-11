<?php
include "../../config/koneksi.php";

// Fungsi format rupiah
// function rupiah_pos($angka)
// {
//   $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
//   return $hasil_rupiah;
// }

// Set header untuk file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=report_sales_item_" . date('Y-m-d') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Ambil parameter tanggal
$date_awal = isset($_GET['date_awal']) ? $_GET['date_awal'] : '';
$date_akhir = isset($_GET['date_akhir']) ? $_GET['date_akhir'] : '';

// Query sesuai dengan script asli
$query = "SELECT a.sku, b.name, sum(a.qty) qty, sum(a.price * a.qty) amount 
          FROM pos_dsalesline a 
          LEFT JOIN pos_mproduct b ON a.sku = b.sku 
          WHERE a.sku != '' ";

if ($date_awal != '' && $date_akhir != '') {
  $query .= " AND DATE(a.insertdate) BETWEEN '$date_awal' AND '$date_akhir' ";
} else {
  $query .= " AND DATE(a.insertdate) = DATE(NOW()) ";
}

$query .= ' GROUP BY a.sku, b.name
            ORDER BY b.name ASC';

// Eksekusi query
$statement = $connec->query($query);

// Mulai output Excel
echo "<table border='1'>";
echo "<tr>";
echo "<th colspan='5' style='background-color: #4CAF50; color: white; font-size: 16px; height: 40px;'>REPORT SALES ITEM</th>";
echo "</tr>";
echo "<tr>";
echo "<th colspan='5' style='background-color: #f2f2f2;'>Period: " .
  ($date_awal != '' && $date_akhir != '' ? $date_awal . " to " . $date_akhir : "Today (" . date('Y-m-d') . ")") .
  "</th>";
echo "</tr>";
echo "<tr>";
echo "<th colspan='5' style='background-color: #f2f2f2;'>Export Date: " . date('Y-m-d H:i:s') . "</th>";
echo "</tr>";
echo "<tr>";
echo "<th style='background-color: #2196F3; color: white; text-align: center;'>No</th>";
echo "<th style='background-color: #2196F3; color: white; text-align: center;'>SKU</th>";
echo "<th style='background-color: #2196F3; color: white; text-align: center;'>Name</th>";
echo "<th style='background-color: #2196F3; color: white; text-align: center;'>QTY</th>";
echo "<th style='background-color: #2196F3; color: white; text-align: center;'>Amount</th>";
echo "</tr>";

$no = 1;
$total_qty = 0;
$total_amount = 0;

foreach ($statement as $r) {
  $sku = $r['sku'];
  $name = $r['name'];
  $qty = $r['qty'];
  $amount = $r['amount'];

  echo "<tr>";
  echo "<td style='text-align: center;'>" . $no++ . "</td>";
  echo "<td>" . htmlspecialchars($sku) . "</td>";
  echo "<td>" . htmlspecialchars($name) . "</td>";
  echo "<td style='text-align: right;'>" . number_format($qty, 0) . "</td>";
  echo "<td style='text-align: right;'>Rp " . number_format($amount, 0, ',', '.') . "</td>";
  echo "</tr>";

  $total_qty += $qty;
  $total_amount += $amount;
}

// Baris total
echo "<tr style='background-color: #e8f5e8; font-weight: bold;'>";
echo "<td colspan='3' align='right'><strong>TOTAL</strong></td>";
echo "<td style='text-align: right;'><strong>" . number_format($total_qty, 0) . "</strong></td>";
echo "<td style='text-align: right;'><strong>Rp " . number_format($total_amount, 0, ',', '.') . "</strong></td>";
echo "</tr>";

echo "</table>";

// Tambahkan juga versi CSV untuk format yang lebih bersih (opsional)
// header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
// header("Content-Disposition: attachment; filename=report_sales_item_" . date('Y-m-d') . ".csv");
// echo "No,SKU,Name,QTY,Amount\n";
// $no = 1;
// foreach ($statement as $r) {
//     echo $no++ . ",";
//     echo '"' . $r['sku'] . '",';
//     echo '"' . $r['name'] . '",';
//     echo $r['qty'] . ",";
//     echo $r['amount'] . "\n";
// }
?>