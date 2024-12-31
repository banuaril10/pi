<?php include "../../config/koneksi.php";
//get
$query = "SELECT * FROM pos_dcashierbalance WHERE isactived = '1' and status = 'DONE' order by date(insertdate) desc";

$json = array();
$no = 1;
$statement = $connec->query($query);
foreach ($statement as $r) {

// Start Date	Close Date	Cashier Name	Balance	 Sales	Discount	Refund	Net Sales	Sales Non Cash	Cash in System	Cash In Drawer	Infaq	Variant	Status

    $cashier_name = '';
    //get cashier name from ad_muser_key
    $query_cashier = "SELECT * FROM ad_muser WHERE ad_muser_key = '".$r['ad_muser_key']."' ";
    $statement_cashier = $connec->query($query_cashier);
    foreach ($statement_cashier as $r_cashier) {
        $cashier_name = $r_cashier['username'];
    }



    $start_date = $r['startdate'];
    $close_date = $r['enddate'];
    $balance = $r['balanceamount'];
    // $sales = $r['salesamount'];
    $discount = $r['discountamount'];
    $refund = $r['refundamount'];
    $net_sales = $r['salesamount'];
    // $sales = $r['salesamount'] + $discount + $refund;
    $sales = $r['salesamount'] + $discount;
    // $net_sales = $sales - $discount - $refund;
    $sales_non_cash = $r['salesdebitamount'] + $r['salescreditamount'];
    // $cash_in_sistem = $r['actualamount'];
    $cash_in_sistem = $net_sales - $sales_non_cash;
    // $cash_in_drawer = $r['balanceamount'];
    $cash_in_drawer = $r['actualamount'];
    $variant = $r['actualamount'] - ($r['salesamount'] - $r['salesdebitamount'] - $r['salescreditamount']);
    $infaq = $r['donasiamount'];
    $status = $r['status'];



    $json[] = array(
        "no" => $no,
        "start_date" => date_format_pos($start_date),
        "close_date" => date_format_pos($close_date),
        "cashier_name" => $cashier_name,
        "balance" => rupiah_pos($balance),
        "balance_1" => $balance,
        "sales" => rupiah_pos($sales),
        "sales_1" => $sales,
        "discount" => rupiah_pos($discount),
        "discount_1" => $discount,
        "refund" => rupiah_pos($refund),
        "refund_1" => $refund,
        "net_sales" => rupiah_pos($net_sales),
        "net_sales_1" => $net_sales,
        "sales_non_cash" => rupiah_pos($sales_non_cash),
        "sales_non_cash_1" => $sales_non_cash,
        "cash_in_sistem" => rupiah_pos($cash_in_sistem),
        "cash_in_sistem_1" => $cash_in_sistem,
        "cash_in_drawer" => rupiah_pos($cash_in_drawer),
        "cash_in_drawer_1" => $cash_in_drawer,
        "variant" => rupiah_pos($variant),
        "variant_1" => $variant,
        "infaq" => rupiah_pos($infaq),
        "infaq_1" => $infaq,
        "status" => $status,
    );

    $no++;
}


$json_string = json_encode($json);
echo $json_string;

?>