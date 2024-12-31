<?php include "../../config/koneksi.php";
//get
$query = "SELECT rack FROM pos_mproduct group by rack order by rack asc";

$json = array();
$no = 1;
$statement = $connec->query($query);
foreach ($statement as $r) {
    $json[] = array(
        "rack" => $r['rack'],
    );

    $no++;
}


$json_string = json_encode($json);
//return json
echo $json_string;

?>