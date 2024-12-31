<?php include "../../config/koneksi.php";
$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	$idstore = $row['ad_morg_key'];
}
function get_sales($url)
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
$url = $base_url . '/store/restore/restore_sales.php?idstore=' . $idstore;

$hasil = get_sales($url);
$j_hasil = json_decode($hasil, true);

// print_r($j_hasil);

$s = array();


try {

	//delete pos_dsales_log where date now
	$delete_pos_dsales_log = "delete from pos_dsales_log where date(insertdate) = date(now())";
	$connec->query($delete_pos_dsales_log);

	//delete pos_dsalesline_log where date now
	$delete_pos_dsalesline_log = "delete from pos_dsalesline_log where date(insertdate) = date(now())";
	$connec->query($delete_pos_dsalesline_log);


	// pos_dshop_sales
	foreach ($j_hasil['pos_dshop_sales'] as $key => $r) {

		$qq_shop_sales = "insert into pos_dshopsales (pos_dshopsales_key, ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, pos_mshift_key, ad_muser_key, salesdate, closedate, balanceamount, salesamount, salescashamount, salesdebitamount, salescreditamount, status, actualamount, remark, issync, refundamount, discountamount, cancelcount, cancelamount, donasiamount, variantmin, variantplus, pointamount, pointdebitamout, pointcreditamount, status_intransit) 
		values (
		'" . $r['pos_dshopsales_key'] . "', 
		'" . $r['ad_mclient_key'] . "', 
		'" . $r['ad_morg_key'] . "', 
		'" . $r['isactived'] . "', 
		'" . $r['insertdate'] . "', 
		'" . $r['insertby'] . "', 
		'" . $r['postby'] . "', 
		'" . $r['postdate'] . "', 
		'" . $r['pos_mshift_key'] . "', 
		'" . $r['ad_muser_key'] . "', 
		" . (($r['salesdate'] == '') ? "NULL" : ("'" . $r['salesdate'] . "'")) . ", 
		" . (($r['closedate'] == '') ? "NULL" : ("'" . $r['closedate'] . "'")) . ", 
		" . (($r['balanceamount'] == '') ? "NULL" : ("'" . $r['balanceamount'] . "'")) . ", 
		" . (($r['salesamount'] == '') ? "NULL" : ("'" . $r['salesamount'] . "'")) . ", 
		" . (($r['salescashamount'] == '') ? "NULL" : ("'" . $r['salescashamount'] . "'")) . ", 
		" . (($r['salesdebitamount'] == '') ? "NULL" : ("'" . $r['salesdebitamount'] . "'")) . ", 
		" . (($r['salescreditamount'] == '') ? "NULL" : ("'" . $r['salescreditamount'] . "'")) . ",
		'" . $r['status'] . "',
		" . (($r['actualamount'] == '') ? "NULL" : ("'" . $r['actualamount'] . "'")) . ",
		" . (($r['remark'] == '') ? "NULL" : ("'" . $r['remark'] . "'")) . ",
		true,
		" . (($r['refundamount'] == '') ? "NULL" : ("'" . $r['refundamount'] . "'")) . ",
		" . (($r['discountamount'] == '') ? "NULL" : ("'" . $r['discountamount'] . "'")) . ",
		" . (($r['cancelcount'] == '') ? "NULL" : ("'" . $r['cancelcount'] . "'")) . ",
		" . (($r['cancelamount'] == '') ? "NULL" : ("'" . $r['cancelamount'] . "'")) . ",
		" . (($r['donasiamount'] == '') ? "NULL" : ("'" . $r['donasiamount'] . "'")) . ",
		" . (($r['variantmin'] == '') ? "NULL" : ("'" . $r['variantmin'] . "'")) . ",
		" . (($r['variantplus'] == '') ? "NULL" : ("'" . $r['variantplus'] . "'")) . ",
		" . (($r['pointamount'] == '') ? "NULL" : ("'" . $r['pointamount'] . "'")) . ",
		" . (($r['pointdebitamout'] == '') ? "NULL" : ("'" . $r['pointdebitamout'] . "'")) . ",
		" . (($r['pointcreditamount'] == '') ? "NULL" : ("'" . $r['pointcreditamount'] . "'")) . ",
		'1'
		)";

		$result = $connec->query($qq_shop_sales);

	}
	



	foreach ($j_hasil['cashier_balance'] as $key => $r) {

		$qq_cashier_balance = "insert into pos_dcashierbalance (pos_dcashierbalance_key, ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, pos_mcashier_key, ad_muser_key, pos_mshift_key, startdate, enddate, 
		balanceamount, salesamount, status, salescashamount, salesdebitamount, salescreditamount, actualamount, issync, refundamount, discountamount, cancelcount, 
		cancelamount, donasiamount, pointamount, pointdebitamout, pointcreditamount, status_intransit)
		values (
		'" . $r['pos_dcashierbalance_key'] . "', 
		'" . $r['ad_mclient_key'] . "', 
		'" . $r['ad_morg_key'] . "', 
		'" . $r['isactived'] . "', 
		'" . $r['insertdate'] . "', 
		'" . $r['insertby'] . "', 
		'" . $r['postby'] . "', 
		'" . $r['postdate'] . "', 
		'" . $r['pos_mcashier_key'] . "', 
		'" . $r['ad_muser_key'] . "', 
		'" . $r['pos_mshift_key'] . "', 
		" . (($r['startdate'] == '') ? "NULL" : ("'" . $r['startdate'] . "'")) . ", 
		" . (($r['enddate'] == '') ? "NULL" : ("'" . $r['enddate'] . "'")) . ",
		" . (($r['balanceamount'] == '') ? "NULL" : ("'" . $r['balanceamount'] . "'")) . ", 
		" . (($r['salesamount'] == '') ? "NULL" : ("'" . $r['salesamount'] . "'")) . ", 
		'" . $r['status'] . "', 
		" . (($r['salescashamount'] == '') ? "NULL" : ("'" . $r['salescashamount'] . "'")) . ",
		" . (($r['salesdebitamount'] == '') ? "NULL" : ("'" . $r['salesdebitamount'] . "'")) . ",
		" . (($r['salescreditamount'] == '') ? "NULL" : ("'" . $r['salescreditamount'] . "'")) . ",
		" . (($r['actualamount'] == '') ? "NULL" : ("'" . $r['actualamount'] . "'")) . ",
		true, 
		" . (($r['refundamount'] == '') ? "NULL" : ("'" . $r['refundamount'] . "'")) . ",
		" . (($r['discountamount'] == '') ? "NULL" : ("'" . $r['discountamount'] . "'")) . ",
		" . (($r['cancelcount'] == '') ? "NULL" : ("'" . $r['cancelcount'] . "'")) . ",
		" . (($r['cancelamount'] == '') ? "NULL" : ("'" . $r['cancelamount'] . "'")) . ",
		" . (($r['donasiamount'] == '') ? "NULL" : ("'" . $r['donasiamount'] . "'")) . ",
		" . (($r['pointamount'] == '') ? "NULL" : ("'" . $r['pointamount'] . "'")) . ",
		" . (($r['pointdebitamout'] == '') ? "NULL" : ("'" . $r['pointdebitamout'] . "'")) . ",
		" . (($r['pointcreditamount'] == '') ? "NULL" : ("'" . $r['pointcreditamount'] . "'")) . ",
		'1'
		)";

		$result = $connec->query($qq_cashier_balance);

	}
	

	// $connec->beginTransaction();

	foreach ($j_hasil['sales'] as $key => $r) {

		$qq_header = "insert into pos_dsales (pos_dsales_key, ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, pos_medc_key, pos_dcashierbalance_key, pos_mbank_key, ad_muser_key, billno, billamount, 
		paymentmethodname, membercard, cardno, approvecode, edcno, bankname, serialno, billstatus, paycashgiven, paygiven, printcount, issync, donasiamount, dpp, 
		ppn, billcode, ispromomurah, point, pointgive, membername, status_intransit) 
		values (
		'" . $r['pos_dsales_key'] . "', 
		'" . $r['ad_mclient_key'] . "', 
		'" . $r['ad_morg_key'] . "', 
		'" . $r['isactived'] . "', 
		'" . $r['insertdate'] . "', 
		'" . $r['insertby'] . "', 
		'" . $r['postby'] . "', 
		'" . $r['postdate'] . "', 
		" . (($r['pos_medc_key'] == '') ? "NULL" : ("'" . $r['pos_medc_key'] . "'")) . ", 
		'" . $r['pos_dcashierbalance_key'] . "', 
		" . (($r['pos_mbank_key'] == '') ? "NULL" : ("'" . $r['pos_mbank_key'] . "'")) . ", 
		'" . $r['ad_muser_key'] . "', 
		'" . $r['billno'] . "', 
		'" . $r['billamount'] . "', 
		'" . $r['paymentmethodname'] . "', 
		" . (($r['membercard'] == '') ? "NULL" : ("'" . $r['membercard'] . "'")) . ", 
		" . (($r['cardno'] == '') ? "NULL" : ("'" . $r['cardno'] . "'")) . ", 
		" . (($r['approvecode'] == '') ? "NULL" : ("'" . $r['approvecode'] . "'")) . ", 
		" . (($r['edcno'] == '') ? "NULL" : ("'" . $r['edcno'] . "'")) . ", 
		" . (($r['bankname'] == '') ? "NULL" : ("'" . $r['bankname'] . "'")) . ", 
		'" . $r['serialno'] . "', 
		'" . $r['billstatus'] . "', 
		" . (($r['paycashgiven'] == '') ? "NULL" : ("'" . $r['paycashgiven'] . "'")) . ", 
		" . (($r['paygiven'] == '') ? "NULL" : ("'" . $r['paygiven'] . "'")) . ",
		" . (($r['printcount'] == '') ? "NULL" : ("'" . $r['printcount'] . "'")) . ",
		true,
		" . (($r['donasiamount'] == '') ? "NULL" : ("'" . $r['donasiamount'] . "'")) . ",
		" . (($r['dpp'] == '') ? "NULL" : ("'" . $r['dpp'] . "'")) . ",
		" . (($r['ppn'] == '') ? "NULL" : ("'" . $r['ppn'] . "'")) . ",
		'" . $r['billcode'] . "', 
		false,
		" . (($r['point'] == '') ? "NULL" : ("'" . $r['point'] . "'")) . ",
		" . (($r['pointgive'] == '') ? "NULL" : ("'" . $r['pointgive'] . "'")) . ",
		" . (($r['membername'] == '') ? "NULL" : ("'" . $r['membername'] . "'")) . ", 
		'1'
		)";

		// print_r($r['line']);

		$result = $connec->query($qq_header);
		if ($result) {
			foreach ($r['line'] as $r_line) {

				$qq_line = "INSERT INTO pos_dsalesline
					(pos_dsalesline_key, ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, pos_dsales_key, billno, seqno, sku, qty, price, 
					discount, amount, issync, discountname, status_sales, status_intransit) 
					values (
					'" . $r_line['pos_dsalesline_key'] . "', 
					'" . $r_line['ad_mclient_key'] . "', 
					'" . $r_line['ad_morg_key'] . "', 
					'" . $r_line['isactived'] . "', 
					'" . $r_line['insertdate'] . "', 
					'" . $r_line['insertby'] . "', 
					'" . $r_line['postby'] . "', 
					'" . $r_line['postdate'] . "', 
					'" . $r_line['pos_dsales_key'] . "', 
					'" . $r_line['billno'] . "', 
					'" . $r_line['seqno'] . "', 
					'" . $r_line['sku'] . "', 
					'" . $r_line['qty'] . "', 
					'" . $r_line['price'] . "', 
					'" . $r_line['discount'] . "', 
					'" . $r_line['amount'] . "', 
					true, 
					'" . $r_line['discountname'] . "', 
					'" . $r_line['status_sales'] . "', 
					'1'
					)";

				$connec->query($qq_line);

			}

			// echo $qq_line;



		}
	}




	// $connec->commit();


	$json = array(
		"status" => "SUCCESS",
		"message" => "Data Inserted",
	);


	echo json_encode($json);
} catch (Exception $e) {
	$connec->rollBack();
}










?>