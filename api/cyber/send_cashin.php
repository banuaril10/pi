<?php include "../../config/koneksi.php";

function push_cashin($url, $data)
{
    $postData = array(
        "data" => $data
    );
    $fields_string = http_build_query($postData);

    $curl = curl_init();

    curl_setopt_array($curl, array(
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

$jj_header = array();


$list_header = "SELECT cashinid, org_key, userid, nama_insert, cash, insertdate, status, approvedby, syncnewpos, setoran FROM public.cash_in where syncnewpos = '0' and status = '1'";


foreach ($connec->query($list_header) as $row1) {
    $jj_header[] = array(
        "cashinid" => $row1['cashinid'],
        "org_key" => $row1['org_key'],
        "userid" => $row1['userid'],
        "nama_insert" => $row1['nama_insert'],
        "cash" => $row1['cash'],
        "insertdate" => $row1['insertdate'],
        "status" => $row1['status'],
        "approvedby" => $row1['approvedby'],
        "syncnewpos" => $row1['syncnewpos'],
        "setoran" => $row1['setoran']
    );
}

// print_r($jj_header);


if (!empty($jj_header)) {
    $url = $base_url . "/cash_in/send_cashin.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI";
    $array_header = array("data" => $jj_header);
    $array_header_json = json_encode($array_header);
    $hasil_header = push_cashin($url, $array_header_json);
    $j_hasil_header = json_decode($hasil_header, true);

    if (!empty($j_hasil_header)) {
        foreach ($j_hasil_header as $r) {
            $statement1 = $connec->query("update cash_in set syncnewpos = '1' where cashinid = '" . $r . "'");
        }
    }


    $jumlah = count($j_hasil_header);

    echo "Berhasil Sync ".$jumlah." Data";
}else {
    echo "Sudah Sync Semua";
}





