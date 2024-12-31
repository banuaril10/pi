<?php include "../../config/koneksi.php";


function sync_approval($url, $m_pi)
{

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url.'?m_pi=' . $m_pi,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;

}


$url = $base_url . '/store/pi/cek_status.php';


$yd = date('Y-m-d', strtotime(date('Y-m-d') . "-1 days"));
$cek = $connec->query("select * from m_pi where date(insertdate) between '" . $yd . "' and '" . date('Y-m-d') . "' and status = '3'");
$count = $cek->rowCount();
$no = 0;

if ($count > 0) {
    foreach ($cek as $ra) {
        // print_r($ra);
        $hasil = sync_approval($url, $ra['m_pi_key']);
        $j_hasil = json_decode($hasil, true);
        // print_r($j_hasil);
        $mpk = $j_hasil['m_pi_key'];
        $status = $j_hasil['status'];

        if ($status == '1') {
            $update = $connec->query("update m_pi set status = '2' where m_pi_key = '" . $mpk . "'");
            if ($update) {
                $connec->query("update m_piline set issync = '0' where m_pi_key = '" . $mpk . "'");
                $no = $no + 1;
            }
        }

        if ($status == '3') {
            $connec->query("update m_pi set status = '4' where m_pi_key = '" . $mpk . "'");
        }

        if ($no == 0) {

            $json = array('result' => '1', 'msg' => 'Belum ada perubahan');
        } else {

            $json = array('result' => '1', 'msg' => 'Berhasil sync ' . $no . ' header');
        }
    }

} else {

    $json = array('result' => '1', 'msg' => 'Tidak ada list waiting approval');
}



$json_string = json_encode($json);
echo $json_string;