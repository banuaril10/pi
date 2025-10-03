<?php
include "../config/koneksi.php";

function rupiah($angka)
{
  return number_format($angka, 0, ',', '.');
}

$billno = $_GET['billno'];
$sku = $_GET['sku'];

$info = "Info Promo..";
$nama_product = "";
$discount_name = "";

// Ambil info produk
$product = "select * from pos_mproduct where sku = '" . $sku . "'";
$cp = $connec->query($product);
foreach ($cp as $r1) {
  $nama_product = $r1['name'];
  $price = $r1['price'];
}

// Promo reguler
$cek_reguler = "select discount, discountname 
                from pos_mproductdiscount 
                where sku = '" . $sku . "' 
                and DATE(now()) between fromdate and todate";
$cr = $connec->query($cek_reguler);
foreach ($cr as $r1) {
  $diskon = $price - $r1['discount'];
  $discount_name .= 'Diskon <font style="color: red">' . rupiah($r1['discount']) . '</font> 
                       Menjadi <font style="color: red">' . rupiah($diskon) . '</font><br>';
}

// Promo grosir
$cek_grosir = "select discount, discountname, minbuy 
               from pos_mproductdiscountgrosir_new 
               where sku = '" . $sku . "' 
               and DATE(now()) between fromdate and todate 
               and minbuy > 1 
               order by minbuy asc";
$cv = $connec->query($cek_grosir);
foreach ($cv as $r1) {
  $diskon = $price - $r1['discount'];
  $discount_name .= 'Beli ' . $r1['minbuy'] . ', Diskon <font style="color: red">' . rupiah($r1['discount']) . '</font> 
                       Menjadi <font style="color: red">' . rupiah($diskon) . '</font><br>';
}

// Promo bundling
$cek_bundling = "SELECT b.bundling_code, b.minbuy
                 FROM pos_mproductdiscount_bundling a
                 JOIN pos_mproductdiscount_bundling_header b 
                    ON a.discountname = b.bundling_code
                 WHERE a.sku = '" . $sku . "'
                   AND DATE(now()) BETWEEN a.fromdate AND a.todate
                 LIMIT 1";

$cb = $connec->query($cek_bundling);
foreach ($cb as $b1) {
  $bundling_code = $b1['bundling_code'];
  $minbuy = $b1['minbuy'];

  $discount_name .= '<b>Promo Bundling:</b><br>Minimal beli <b>' . $minbuy . '</b> item:<br>';

  // Ambil hanya potongan harga dari semua item di bundling ini
  $list_items = "SELECT discount
                   FROM pos_mproductdiscount_bundling 
                   WHERE discountname = '" . $bundling_code . "'
				   and sku = '" . $sku . "'
                     AND DATE(now()) BETWEEN fromdate AND todate";

  $li = $connec->query($list_items);
  foreach ($li as $itm) {
    if ($itm['discount'] > 0) {
      $discount_name .= '- Potongan ' . rupiah($itm['discount']) . '<br>';
    }
  }
}


// Promo combo (A + B bayar harga termahal)
$cek_combo = "SELECT h.promo_name, h.min_items, h.max_items
              FROM pos_mcombo_promo_detail d
              JOIN pos_mcombo_promo_header h 
                ON d.pos_mcombo_promo_header_key = h.pos_mcombo_promo_header_key
              WHERE d.sku = '" . $sku . "'
                AND h.isactived = '1'
                AND DATE(now()) BETWEEN h.fromdate AND h.todate
              LIMIT 1";

$cc = $connec->query($cek_combo);
foreach ($cc as $c1) {
  $promo_name = $c1['promo_name'];
  $min_items = $c1['min_items'];
  $max_items = $c1['max_items'];

  $discount_name .= '<b style="color:blue">' . $promo_name . ':</b><br>
                     Beli <b>' . $min_items . '</b> item (combo), cukup bayar harga 
                     <b style="color:red">termahal</b>.<br>';

  // Ambil daftar item SKU yg masuk combo
  $combo_items = "SELECT p.sku, m.name, pmd.price
                  FROM pos_mcombo_promo_detail d
                  JOIN pos_mproduct m ON d.sku = m.sku
                  LEFT JOIN (
                      SELECT sku, price FROM pos_mproduct
                  ) pmd ON d.sku = pmd.sku
                  WHERE d.pos_mcombo_promo_header_key = (
                      SELECT pos_mcombo_promo_header_key
                      FROM pos_mcombo_promo_detail d2
                      JOIN pos_mcombo_promo_header h2 
                        ON d2.pos_mcombo_promo_header_key = h2.pos_mcombo_promo_header_key
                      WHERE d2.sku = '" . $sku . "'
                        AND h2.isactived = '1'
                        AND DATE(now()) BETWEEN h2.fromdate AND h2.todate
                      LIMIT 1
                  )";

  $ci = $connec->query($combo_items);
  foreach ($ci as $itm) {
    $discount_name .= '- <span style="color:green">' . $itm['name'] . '</span> 
                        (Harga: <span style="color:red">' . rupiah($itm['price']) . '</span>)<br>';
  }
}




$info = $nama_product . '<br>' . $discount_name;

echo $info;
?>