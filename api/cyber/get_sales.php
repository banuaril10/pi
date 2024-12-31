<?php include "../../config/koneksi.php";
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
    $hasil_rupiah = number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
}

function get_sales($url, $date, $ad_org_id)
{
    $postData = array(
        "date" => $date,
        "ad_org_id" => $ad_org_id,
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

$url = $base_url . "/sales_order/get_sales.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI";
$hasil = get_sales($url, $tanggal, $ad_org_id);



$j_hasil = json_decode($hasil, true);

$header = 0;
$line = 0;

//get count header and line
$statement2 = $connec->query("select count(*) as header, sum(billamount) as ba from pos_dsales 
where isactived = '1' and date(insertdate) = '" . $tanggal . "'");
foreach ($statement2 as $r) {
    $header = $r['header'];
    $header_amount = $r['ba'];
}

$statement3 = $connec->query("select count(*) as line, sum(amount) as la from pos_dsalesline 
where date(insertdate) = '" . $tanggal . "'");
foreach ($statement3 as $r) {
    $line = $r['line'];
    $line_amount = $r['la'];
}

$selisih = $header_amount - $j_hasil['header_amount'];
$selisih_line = $line_amount - $j_hasil['line_amount'];

$gtk_amount = 0;
$gtk = $connec->query("select salesamount from pos_dshopsales where date(salesdate) = '" . $tanggal . "' and status_intransit = '1'");
foreach ($gtk as $r) {
    $gtk_amount = $r['salesamount'];
}

$selisih_gtk = $gtk_amount - $j_hasil['line_amount'];

if($selisih_gtk != 0){
    $msg_gtk = "<font style='color: red; font-weight: bold'> Masih ada selisih : " . rupiah($selisih_gtk) . "</font>";
}else{
    $msg_gtk = "<font style='color: green; font-weight: bold'> Tidak ada selisih..</font>";
}

if ($selisih != 0 || $selisih_line != 0) {
    $msg = "<font style='color: red; font-weight: bold'> Masih ada selisih.. , Header : " . $selisih . ", Line : " . $selisih_line . "</font>";
} else {
    $msg = "<font style='color: green; font-weight: bold'> Tidak ada selisih..</font>";
}

echo "<table border='1'>";
echo "<tr>
        <th>Tgl Sales Order</th>
        <th>Lokal vs InTransit</th>
        <th>Status Tutup Harian</th>
        </tr>";
echo "<tr style='background: #fff'>";
echo "
        <td>" . $tanggal . "</td>
        <td>" . $msg . "</td>
        <td>" . $msg_gtk . "</td>
        
        ";
echo "</tr>";
echo "</table>";


// <tr>
// <td>" . $header . "</td>
// <td>" . rupiah($header_amount) . "</td>
// <td>" . $line . "</td>
// <td>" . rupiah($line_amount) . "</td>
// <td>" . $j_hasil['header'] . "</td>
// <td>" . rupiah($j_hasil['header_amount']) . "</td>
// <td>" . $j_hasil['line'] . "</td>
// <td>" . rupiah($j_hasil['line_amount']) . "</td>
// <td>" . rupiah($j_hasil['cashier_amount']) . "</td>
// </tr>
// <tr>
// <th style='background-color: rgb(244, 44, 44)' colspan='4'>SELISIH HEADER</th>
// <th style='background-color: rgb(244, 44, 44)' colspan='4'>SELISIH LINE</th>
// </tr>
// echo
//     "
//         <tr>
//         <td colspan='4'>" . rupiah($selisih) . "</td>
//         <td colspan='4'>" . rupiah($selisih_line) . "</td>
//         </tr>
//         ";


