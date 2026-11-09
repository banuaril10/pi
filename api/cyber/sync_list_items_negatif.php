<?php include "../../config/koneksi.php";
$tanggal = isset($_GET['date']) ? $_GET['date'] : 'now';

// Ambil idstore aktif (sama seperti contoh)
$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);
$idstore = null;
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}

if (!$idstore) {
    echo json_encode(['status' => 'FAILED', 'message' => 'idstore tidak ditemukan']);
    exit;
}

// Fungsi push (sama pola dengan push_to_header)
function push_to_server($url, $payload, $idstore)
{
    $postData = array(
        "items"   => $payload,
        "idstore" => $idstore
    );
    $fields_string = http_build_query($postData);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'POST',
        CURLOPT_POSTFIELDS     => $fields_string,
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

// Ambil data yang belum terkirim (status_intransit null)
if ($tanggal != "now") {
    $list_items = "select * from list_items_negatif 
                   where date(created_at) = '" . $tanggal . "' 
                   and status_intransit is null";
} else {
    $list_items = "select * from list_items_negatif 
                   where status_intransit is null 
                   and date(created_at) = date(now())";
}

$jj_items = array();

foreach ($connec->query($list_items) as $row) {
    $jj_items[] = array(
        "id"               => $row['id'],
        "id_location"      => $idstore,
        "sku"              => $row['sku'],
        "item_name"        => $row['item_name'],
        "stock"            => $row['stock'],
        "created_at"       => $row['created_at'],
        "updated_at"       => $row['updated_at'],
        "status_intransit" => $row['status_intransit'],
    );
}

if (!empty($jj_items)) {
    $url = $base_url . "/sales_order/sync_list_items_negatif.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI";
    $array_items = array("items" => $jj_items);
    $array_items_json = json_encode($array_items);

    $hasil = push_to_server($url, $array_items_json, $idstore);
    $j_hasil = json_decode($hasil, true);

    if (!empty($j_hasil) && isset($j_hasil['inserted_ids'])) {
        // Update status_intransit = '1' untuk id yang berhasil di server
        foreach ($j_hasil['inserted_ids'] as $id) {
            $stmt = $connec->prepare(
                "UPDATE list_items_negatif 
                 SET status_intransit = '1', updated_at = now() 
                 WHERE id = :id"
            );
            $stmt->execute([':id' => $id]);
        }
    }

    echo json_encode([
        'status'        => 'OK',
        'total_sent'    => count($jj_items),
        'total_updated' => isset($j_hasil['inserted_ids']) ? count($j_hasil['inserted_ids']) : 0,
        'server_resp'   => $j_hasil,
    ]);
} else {
    echo json_encode(['status' => 'OK', 'message' => 'Tidak ada data untuk disinkron']);
}