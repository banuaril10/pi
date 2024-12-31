<?php include "../../config/koneksi.php";
//get total product

$has = $_POST['has'];
$domain = $_POST['domain'];
$locationid = $_POST['locationid'];

function get_category($url)
{
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
            CURLOPT_CUSTOMREQUEST => 'GET',
        )
    );
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}


$jum = 0;
$check = $connec->query("select count(ad_morg_key) jum from ad_morg where ad_morg_key = '" . $locationid . "'");
foreach ($check as $row) {
    $jum = $row["jum"];
}

if ($jum == 0) {
    $url = $base_url . '/store/register/get_location.php?idstore=' . $locationid;

    $hasil = get_category($url);
    $j_hasil = json_decode($hasil, true);

    $truncate = "TRUNCATE TABLE ad_morg";
    $statement = $connec->prepare($truncate);
    $statement->execute();

    // print_r($j_hasil[0]['id_master_location']);

    $s = array();

    $ad_morg_key = $j_hasil[0]['id_master_location'];


    if ($ad_morg_key == '') {
        $json = array(
            "status" => "FAILED",
            "message" => "Toko belum didaftarkan..",
        );
        echo json_encode($json);
        die();
    }

    $isactived = 'Y';
    $insertdate = date('Y-m-d H:i:s');
    $insertby = 'SYSTEM';
    $postby = 'SYSTEM';
    $postdate = date('Y-m-d H:i:s');
    $name = $j_hasil[0]['location_name'];
    $description = 'PKP';
    $value = $j_hasil[0]['location'];
    $ppn = '1';
    $address1 = $j_hasil[0]['address1'];
    $address2 = $j_hasil[0]['address2'];
    $address3 = $j_hasil[0]['address3'];
    $isqty = $j_hasil[0]['isqty'];
    $note1 = $j_hasil[0]['note1'];
    $note2 = $j_hasil[0]['note2'];
    $note3 = $j_hasil[0]['note3'];
    $orgtype = 'oracle';

    if ($isqty == 0) {
        $s[] = "('$ad_morg_key', '$ad_mclient_key', '$isactived', '$insertdate', '$insertby', '$postby', '$postdate', '$name', '$description', '$value', 
            '$ppn', '$address1', '$address2', '$address3', false, '$note1', '$note2', '$note3')";
    } else {
        $s[] = "('$ad_morg_key', '$ad_mclient_key', '$isactived', '$insertdate', '$insertby', '$postby', '$postdate', '$name', '$description', '$value', 
            '$ppn', '$address1', '$address2', '$address3', true, '$note1', '$note2', '$note3')";
    }
    // echo $ad_morg_key;


    //truncate


    $values = implode(", ", $s);
    $insert = "INSERT INTO ad_morg (ad_morg_key, ad_mclient_key, isactived, insertdate, insertby, postby, postdate,
    name, description, value, ppn, address1, address2, address3, isqty, note1, note2, note3) VALUES $values";

    // echo $insert;

    $statement = $connec->prepare($insert);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {

        $connec->prepare("delete from pos_mproductcategory where ad_morg_key != '" . $locationid . "' ")->execute();
        $connec->prepare("delete from pos_mproductcategorysub where ad_morg_key != '" . $locationid . "' ")->execute();
        $connec->prepare("delete from pos_mproductdiscount where ad_morg_key != '" . $locationid . "' ")->execute();
        $connec->prepare("delete from pos_mproductdiscountmember where ad_morg_key != '" . $locationid . "' ")->execute();
        $connec->prepare("delete from pos_mproductdiscountmurah where ad_morg_key != '" . $locationid . "' ")->execute();
        $connec->prepare("delete from pos_mproduct where ad_morg_key != '" . $locationid . "' ")->execute();
        $connec->prepare("delete from pos_mbank where ad_morg_key != '" . $locationid . "' ")->execute();
        $connec->prepare("delete from pos_medc where ad_morg_key != '" . $locationid . "' ")->execute();
        $connec->prepare("delete from pos_msupervisor where ad_morg_key != '" . $locationid . "' ")->execute();
        $connec->prepare("delete from inv_dpinventoryline where ad_morg_key != '" . $locationid . "' ")->execute();
        $connec->prepare("delete from inv_dpinventory where ad_morg_key != '" . $locationid . "' ")->execute();
        $connec->prepare("delete from inv_mproduct where ad_morg_key != '" . $locationid . "' ")->execute();
        $connec->prepare("delete from inv_mproductcategory where ad_morg_key != '" . $locationid . "' ")->execute();
        $connec->prepare("delete from pos_dsync where ad_morg_key != '" . $locationid . "' ")->execute();
        $connec->prepare("delete from ad_muser where ad_morg_key != '" . $locationid . "' ")->execute();

        $connec->prepare("INSERT INTO ad_muser
        (ad_muser_key, ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, ad_slanguage_key, ad_mrole_key, userid, username, avatar, 
        userpwd, fixedheader, fixednav, fixedribbon, fixedfooter, menutop, skin, theme, direction, themetype, gradient, decoration, bgposition, layout, email, phone, 
        description, status)
        VALUES('A151399D4D584B42BA5EF782BCECB34A', '" . $ad_mclient_key . "', '" . $locationid . "', '1', '" . date('Y-m-d H:i:s') . "', 'Administrator', 'Administrator', 
        '" . date('Y-m-d H:i:s') . "', '83B183DC512A4B1E9CD7E37EBD86F308', 'C00D0FB2D6F24C15ADCCBCDD4ADC0A60', 'pos', 'pos', 'profile_A151399D4D584B42BA5EF782BCECB34A.gif',
         '8252b14572f9575795082c43d3448c9051992e834c22872c878420e0676684ed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
          NULL, '1')")->execute();

        $json = array(
            "status" => "OK",
            "message" => "Data Inserted",
        );
    } else {
        $json = array(
            "status" => "FAILED",
            "message" => "Data Not Inserted, Query = " . $insert,
        );
    }
} else {

    //cek if user pos not existing
    $check = $connec->query("select count(*) jum from ad_muser where userid = 'pos' and ad_morg_key = '" . $locationid . "'");
    foreach ($check as $row) {
        $jum = $row["jum"];
    }

    if ($jum == 0) {

        $connec->prepare("INSERT INTO ad_muser
        (ad_muser_key, ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, ad_slanguage_key, ad_mrole_key, userid, username, avatar, 
        userpwd, fixedheader, fixednav, fixedribbon, fixedfooter, menutop, skin, theme, direction, themetype, gradient, decoration, bgposition, layout, email, phone, 
        description, status)
        VALUES('A151399D4D584B42BA5EF782BCECB34A', '" . $ad_mclient_key . "', '" . $locationid . "', '1', '" . date('Y-m-d H:i:s') . "', 'Administrator', 'Administrator', 
        '" . date('Y-m-d H:i:s') . "', '83B183DC512A4B1E9CD7E37EBD86F308', 'C00D0FB2D6F24C15ADCCBCDD4ADC0A60', 'pos', 'pos', 'profile_A151399D4D584B42BA5EF782BCECB34A.gif',
         '8252b14572f9575795082c43d3448c9051992e834c22872c878420e0676684ed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
          NULL, '1')")->execute();
    }

    $json = array(
        "status" => "OK",
        "message" => "Data Already Exist",
    );
}

echo json_encode($json);
?>