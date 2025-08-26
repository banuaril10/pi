<?php
include "../../config/koneksi.php";

//show reportingerror


$tanggal = $_GET['date'];

$get_nama_toko = "select * from ad_morg where postby = 'SYSTEM'";
$resultss = $connec->query($get_nama_toko);
$ad_org_id = "";
foreach ($resultss as $r) {
    $storecode = $r["value"];
    $ad_org_id = $r["ad_morg_key"];
}

function rupiah($angka)
{
    return number_format($angka, 0, ',', '.');
}

function get_sales($url, $date, $ad_org_id)
{
    $postData = array(
        "date" => $date,
        "ad_org_id" => $ad_org_id,
        "id" => "OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI"
    );
    $fields_string = http_build_query($postData);
    $curl = curl_init();
    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields_string,
        )
    );

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

// === API GET PPOB ===
$url = $base_url . "/sales_order/get_sales_ppob.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI";
$hasil = get_sales($url, $tanggal, $ad_org_id);
$j_hasil = json_decode($hasil, true);

// print_r("RAW RESPONSE:\n");
// var_dump($hasil);
// echo "\n\nJSON DECODE:\n";
// var_dump($j_hasil);
// die();

// default
$header = 0;
$header_amount = 0;

// ===== ambil data lokal dari pos_dsales_ppob =====
$statement2 = $connec->query("
    select count(*) as header, coalesce(sum(billamount), 0) as ba 
    from pos_dsales_ppob 
    where isactived = '1' and date(insertdate) = '" . $tanggal . "'
");
foreach ($statement2 as $r) {
    $header = $r['header'];
    $header_amount = $r['ba'];
}


// bandingkan local vs API
$selisih_header = $header - $j_hasil['header'];
$selisih_amount = $header_amount - $j_hasil['header_amount'];

// cek juga ke cashier balance
$cashier_amount = $j_hasil['cashier_amount'] ?? 0;
$selisih_cashier = $header_amount - $cashier_amount;

// hasil msg
if ($selisih_header != 0 || $selisih_amount != 0) {
    $msg = "<font style='color: red; font-weight: bold'> Masih ada selisih. Header: " . rupiah($selisih_header) . ", Amount: " . rupiah($selisih_amount) . "</font>";
} else {
    $msg = "<font style='color: green; font-weight: bold'> Tidak ada selisih..</font>";
}

if ($selisih_cashier != 0) {
    $msg_cashier = "<font style='color: green; font-weight: bold'> Tidak ada selisih..</font>";
} else {
    $msg_cashier = "<font style='color: green; font-weight: bold'> Tidak ada selisih..</font>";
}

// ===== OUTPUT ke tbody =====
echo "<tr style='background: #fff'>";
echo "
    <td>" . $tanggal . "</td>
    <td>" . $msg . "</td>
    <td>" . $msg_cashier . "</td>
";
echo "</tr>";
?>
