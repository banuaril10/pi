<?php include "../../config/koneksi.php";
ini_set('max_execution_time', '300');
$connec->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}
function get($url)
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
$url = $base_url . '/store/items/get_items.php?idstore=' . $idstore;

$hasil = get($url);
$j_hasil = json_decode($hasil, true);

$s = array();

$arr_insert = array();
$arr_update = array();

try {

    foreach ($j_hasil as $key => $value) {

        $itemkey = $value['itemkey'];
        $id = $value['id'];
        $sku = $value['sku'];
        $barcode = $value['barcode'];
        $shortcut = $value['shortcut'];
        $name = str_replace("'", "''", $value['name']);
        $idcat = $value['idcat'];
        $idsubcat = $value['idsubcat'];
        $idsubitem = $value['idsubitem'];
        $panjang = $value['panjang'];
        $lebar = $value['lebar'];
        $tinggi = $value['tinggi'];
        $berat = $value['berat'];
        $imageurl = $value['imageurl'];
        $insertdate = $value['insertdate'];
        $updatedate = $value['updatedate'];
        $tag = $value['tag'];
        $category = $value['category'];
        $subcategory = $value['subcategory'];
        $subitem = $value['subitem'];
        $isactived = $value['isactived'];

        if ($idcat == "") {
            $idcat = 0;
        }

        if ($idsubcat == "") {
            $idsubcat = 0;
        }

        if ($idsubitem == "") {
            $idsubitem = 0;
        }

        $check = "SELECT count(*) jum FROM pos_mproduct WHERE m_product_id = '".$id."'";
        $stmt_check = $connec->query($check);
    

        foreach ($stmt_check as $r) {
           if($r['jum'] > 0){
                $connec->query("UPDATE pos_mproduct SET ad_mclient_key = '" . $ad_mclient_key . "', ad_morg_key = '" . $idstore . "', isactived = '" . $isactived . "',
                postby = 'SYSTEM', postdate = '" . date("Y-m-d H:i:s") . "', m_product_id = '" . $id . "', m_product_category_id = '" . $idcat . "', sku = '" . $sku . "',
                name = '" . $name . "', shortcut = '" . $shortcut . "', barcode = '" . $barcode . "', tag = '" . $tag . "', idcat = '" . $idcat . "', idsubcat = 
                '" . $idsubcat . "', idsubitem = '" . $idsubitem . "' WHERE m_product_id = '" . $id . "'");
            }else{

                $arr_insert[] = "('".$ad_mclient_key."', '".$idstore."', 
                '".$isactived."', '".date("Y-m-d H:i:s")."', 'SYSTEM', 'SYSTEM', '".date("Y-m-d H:i:s")."', '".$id."', '".$idcat."', '".$sku."',
                '".$name."', '', 0, 0, '".$shortcut."', '".$barcode."', '".$tag."', '".$idcat."', '".$idsubcat."', '".$idsubitem."')";

           }
        }
    }

        if(count($arr_insert) > 0){
            $values = implode(", ", $arr_insert);
            $insert = "insert into pos_mproduct (ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, m_product_id, m_product_category_id, sku, 
            name, description, price, stockqty, shortcut, barcode, tag, idcat, idsubcat, idsubitem)
            VALUES " . $values . ";";
            $connec->query($insert);
        }

        // $connec->beginTransaction();
        // if(count($arr_update) > 0){
        //     // $values = implode(";", $arr_update);
        //     // $stmt = $connec->prepare($values);
        //     // $stmt->execute();
        //     foreach($arr_update as $update){
        //        $connec->exec($update);
        //     }
        // }
        // $connec->commit();



    
        

    // if ($s == null) {
    //     $json = array(
    //         "status" => "FAILED",
    //         "message" => "Data Not Found",
    //     );
    //     echo json_encode($json);
    //     die();
    // }

    // $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // if ($result) {
        $json = array(
            "status" => "OK",
            "message" => "Data Inserted",
        );
    // } else {
    //     $json = array(
    //         "status" => "FAILED",
    //         "message" => "Data Not Inserted",
    //     );
    // }
    echo json_encode($json);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}



?>