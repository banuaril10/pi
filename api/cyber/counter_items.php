<?php include "../../config/koneksi.php";
$sku = $_POST['sku'];
$mpi = $_GET['mpi'];

$sql = "select m_piline.qtycount, pos_mproduct.name from m_piline left join pos_mproduct on m_piline.sku = pos_mproduct.sku where (m_piline.sku ='" . $sku . "' or m_piline.barcode ='" . $sku . "')
		and m_piline.m_pi_key = '" . $mpi . "' and date(m_piline.insertdate) = '" . date('Y-m-d') . "'";
$result = $connec->query($sql);
$count = $result->rowCount();

if ($count > 0) {
    foreach ($result as $r) {
        $qtyon = $r['qtycount'];
        $pn = $r['name'];

        $lastqty = $qtyon + 1;


        if ($insertfrom == 'M') {

            $connec->query("update m_pi set insertfrommobile = 'Y' where m_pi_key = '" . $mpi . "'");
        } else if ($insertfrom == 'W') {
            $connec->query("update m_pi set insertfromweb = 'Y' where m_pi_key = '" . $mpi . "'");


        }

        if ($sku != "") {


            $statement1 = $connec->query("update m_piline set qtycount = '" . $lastqty . "' where (sku = '" . $sku . "' or barcode = '" . $sku . "') and date(insertdate) = '" . date('Y-m-d') . "'");
        } else {

            $json = array('result' => '0', 'msg' => 'SKU tidak boleh kosong');
        }



        if ($statement1) {
            $json = array('result' => '1', 'msg' => $sku . ' (' . $pn . '), QUANTITY = <font style="color: red">' . $lastqty . '</font>');
        } else {
            $json = array('result' => '0', 'msg' => 'Gagal ,coba lagi nanti');

        }
    }

} else {

    $count1 = 0;
    if ($sku != "") {

        $ceksku = "select m_product_id, sku, name, coalesce(price, 0) from pos_mproduct where (sku ='" . $sku . "' or barcode = '" . $sku . "')";
        $cs = $connec->query($ceksku);
        $count1 = $cs->rowCount();

    }

    if ($count1 > 0) {

        foreach ($cs as $mpii) {
            $m_pro_id = $mpii['m_product_id'];
            $name = $mpii['name'];
            $sku = $mpii['sku'];
            // $price = $mpii['price'];

        }

        if ($insertfrom == 'M') {

            $connec->query("update m_pi set insertfrommobile = 'Y' where m_pi_key = '" . $mpi . "'");
        } else if ($insertfrom == 'W') {
            $connec->query("update m_pi set insertfromweb = 'Y' where m_pi_key = '" . $mpi . "'");


        }

        //cek di maaster product

        $getmpi = "select * from m_pi where m_pi_key ='" . $mpi . "'";
        $gm = $connec->query($getmpi);

        $sql_sales = "select case when sum(qty) is null THEN '0' ELSE sum(qty) END as qtysales from pos_dsalesline where date(insertdate)=date(now()) and sku='" . $sku . "'";

        $rsa = $connec->query($sql_sales);

        foreach ($rsa as $rsa1) {

            $qtysales = $rsa1['qtysales'];
        }
        foreach ($gm as $rr) {

            $hasil = get_data_erp($rr['m_locator_id'], $m_pro_id, $org_key, $ss); //php curl
            $j_hasil = json_decode($hasil, true);

            $qtyon = $j_hasil['qtyon'];
            $price = $j_hasil['price'];
            $pricebuy = $j_hasil['pricebuy'];
            $statuss = $j_hasil['statuss'];
            $qtyout = $j_hasil['qtyout'];
            $statusss = $j_hasil['statusss'];
            $barcode = $j_hasil['barcode'];

            $cek_count = "select qtycount from m_piline where sku = '" . $sku . "' and date(insertdate) = '" . date('Y-m-d') . "'";
            $rsac = $connec->query($cek_count);
            $ccc = $rsac->rowCount();

            if ($ccc > 0) {
                foreach ($rsac as $rrr) {

                    $qtycount = $rrr['qtycount'] + 1;
                }

            } else {
                $qtycount = 1;

            }

            $statement1 = $connec->query("insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate,m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1, barcode, hargabeli) 
					VALUES ('" . $rr['m_pi_key'] . "','" . $org_key . "','1','" . date('Y-m-d H:i:s') . "','" . $username . "', '" . date('Y-m-d H:i:s') . "','" . $rr['m_locator_id'] . "','" . $m_pro_id . "', '" . $sku . "', '" . $qtyon . "', '" . $qtycount . "', '" . $qtysales . "', '" . $price . "', '" . $statuss . "', '" . $qtyout . "', '" . $statusss . "', '" . $barcode . "','" . $pricebuy . "')");


            if ($statement1) {
                $connec->query("update pos_mproduct set isactived = 0 where sku = '" . $sku . "'");
                $json = array('result' => '1', 'msg' => $sku . ' (' . $name . '), QUANTITY = <font style="color: red">' . $qtycount . '</font>');
            }

        }

    } else {

        $json = array('result' => '0', 'msg' => 'ITEMS TIDAK ADA DI MASTER PRODUCT');
    }


}
$json_string = json_encode($json);
echo $json_string;




