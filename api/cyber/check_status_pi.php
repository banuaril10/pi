<?php include "../../config/koneksi.php";
$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}

$m_pi = $_GET['m_pi'];

//update status m_pi
$sql = "update m_pi set status = '3' where m_pi_key = '" . $m_pi . "'";

echo $sql;

// $statement = $connec->prepare($sql);
// $statement->execute();





