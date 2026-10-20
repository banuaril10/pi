<?php include "../../config/koneksi.php";

function get_category($url) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'GET',
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

$url    = $base_url . '/store/target_perjam/master_perjam.php';
$hasil  = get_category($url);
$j_hasil = json_decode($hasil, true);

header('Content-type: application/json');

if (!is_array($j_hasil) || empty($j_hasil)) {
    echo json_encode([
        'status'  => 'FAILED',
        'message' => 'Data kosong / gagal fetch API',
        'data'    => [],
    ]);
    exit;
}

// Output bersih: array of {jam}
$clean = [];
foreach ($j_hasil as $row) {
    $jam = trim((string)($row['jam'] ?? ''));
    if ($jam === '') continue;
    $clean[] = ['jam' => $jam];
}

echo json_encode([
    'status'  => 'OK',
    'message' => 'Data loaded',
    'total'   => count($clean),
    'data'    => $clean,
]);