<?php include "../../config/koneksi.php";
//get

// SELECT pos_dshopsales_key, ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, pos_mshift_key, ad_muser_key, salesdate, closedate, balanceamount, salesamount, salescashamount, salesdebitamount, salescreditamount, status, actualamount, remark, issync, refundamount, discountamount, cancelcount, cancelamount, donasiamount, variantmin, variantplus, pointamount, pointdebitamout, pointcreditamount, status_intransit
// FROM public.pos_dshopsales;


$query = "SELECT * FROM pos_dshopsales WHERE isactived = '1' order by date(insertdate) desc";

$json = array();
$no = 1;
$statement = $connec->query($query);
foreach ($statement as $r) {
    $sales_date = $r['salesdate'];
    $close_date = $r['closedate'];

    
    // $sales = $r['salesamount'];
    

    $discount = $r['discountamount'];
    $refund = $r['refundamount'];

    $net_sales = $r['salesamount'];
    $sales = $r['salesamount'] + $discount;


    $sales_non_cash = $r['salesdebitamount'] + $r['salescreditamount'];
    // $cash_in_sistem = $r['actualamount'];
    $cash_in_sistem = $net_sales - $sales_non_cash;
    // $cash_in_drawer = $r['balanceamount'];
    $cash_in_drawer = $r['actualamount'];
    $variant = $r['variantmin'] + $r['variantplus'];
    $infaq = $r['donasiamount'];
    $remark = $r['remark'];
    $closed_by = $r['postby'];

    $json[] = array(
        "no" => $no,
        "sales_date" => date_format_pos($sales_date),
        "close_time" => time_format_pos($close_date),
        "sales" => rupiah_pos($sales),
        "discount" => rupiah_pos($discount),
        "refund" => rupiah_pos($refund),
        "net_sales" => rupiah_pos($net_sales),
        "sales_non_cash" => rupiah_pos($sales_non_cash),
        "cash_in_sistem" => rupiah_pos($cash_in_sistem),
        "cash_in_drawer" => rupiah_pos($cash_in_drawer),
        "variant" => rupiah_pos($variant),
        "infaq" => rupiah_pos($infaq),
        "remark" => $remark,
        "closed_by" => $closed_by,
    );

    $no++;
}


$json_string = json_encode($json);
//return json

echo $json_string;

?>