<?php
// include "../../config/koneksi.php";

// $ll = "select * from ad_morg where isactived = 'Y'";
// $query = $connec->query($ll);

// while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
//     $idstore = $row['ad_morg_key'];
// }
// function get_category($url)
// {
//     $curl = curl_init();
//     curl_setopt_array(
//         $curl,
//         array(
//             CURLOPT_URL => $url,
//             CURLOPT_RETURNTRANSFER => true,
//             CURLOPT_ENCODING => '',
//             CURLOPT_MAXREDIRS => 10,
//             CURLOPT_TIMEOUT => 0,
//             CURLOPT_FOLLOWLOCATION => true,
//             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//             CURLOPT_CUSTOMREQUEST => 'GET',
//         )
//     );

//     $response = curl_exec($curl);
//     curl_close($curl);
//     return $response;
// }
// // $url = $base_url.'/store/users/get_users_it.php';
// // https://pi.idolmartidolaku.com/api/get_users_it.php
// $url = 'https://pi.idolmartidolaku.com/api/get_users_it.php';


// // echo $idstore;

// $hasil = get_category($url);
// $j_hasil = json_decode($hasil, true);

// $json = array(
//     "status" => "OK",
//     "data" => $j_hasil
// );

// echo json_encode($json);




header('Content-Type: application/json');

$data = [
    [
        "id_user" => "C3DA7B7F76AC430897531621291542A4",
        "fullname" => "Testing IN - 01",
        "username" => "test-user-01",
        "phone" => "10100110",
        "is_active" => "1"
    ],
    [
        "id_user" => "E3BF8D790EE24E7FBA8300D239791380",
        "fullname" => "Tommy Zulkhaidir",
        "username" => "11166",
        "phone" => "081319010432",
        "is_active" => "1"
    ],
    [
        "id_user" => "7ad5141cd9ac424483a1687d4d4b8663",
        "fullname" => "Banu Ari Ramadhan",
        "username" => "banu",
        "phone" => "088211832975",
        "is_active" => "1"
    ],
    [
        "id_user" => "E05BD54B73A74BC5919B2A7541063627",
        "fullname" => "Yusup Alfarizi",
        "username" => "171751",
        "phone" => "087720929268",
        "is_active" => "1"
    ],
    [
        "id_user" => "b07e559075a241df8973ea5bfc9574fc",
        "fullname" => "Fiqi Ubay",
        "username" => "pique",
        "phone" => "085842977337",
        "is_active" => "1"
    ],
    [
        "id_user" => "b07e559075a241df8973ea5bfc9574fc",
        "fullname" => "Nurmanhal",
        "username" => "agil",
        "phone" => "085778201406",
        "is_active" => "1"
    ],
    [
        "id_user" => "b07e559075a241df8973ea5bfc9574fc",
        "fullname" => "Ihsan Nasrullah",
        "username" => "ihsan",
        "phone" => "081906850097",
        "is_active" => "1"
    ],
    [
        "id_user" => "b07e559075a241df8973ea5bfc9574fc",
        "fullname" => "Irfan Kartap",
        "username" => "irfan",
        "phone" => "081314194991",
        "is_active" => "1"
    ]
];

echo json_encode([
    "status" => "OK",
    "data" => $data
]);


?>