<?php include "../../config/koneksi.php";

$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
    $value = $row['value'];
}

    // var objtransaksi = {
    //   metode: metode,
    //   nopelanggan: nopelanggan,
    //   nominal: nominal,
    //   edcdebitname: edcdebitname,
    //   bankdebitname: bankdebitname,
    // };
$type = $_POST['type'];
$metode = $_POST['metode'];
$nopelanggan = $_POST['nopelanggan'];
$nominal = $_POST['nominal'];
$amount = $_POST['amount'];
$edcdebitname = $_POST['edcdebitname'];
$bankdebitname = $_POST['bankdebitname'];
$trxid = $_POST['trxid'];
$reqid = $_POST['reqid'];
$msg = $_POST['msg'];

//INSERT INTO public.pos_dsales_ppob
// (pos_dsales_key, ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, pos_medc_key, pos_dcashierbalance_key, pos_mbank_key, ad_muser_key, billno, billamount, paymentmethodname, membercard, cardno, approvecode, edcno, bankname, serialno, billstatus, paycashgiven, paygiven, printcount, issync, donasiamount, dpp, ppn, billcode, ispromomurah, point, pointgive, membername, status_intransit)
// VALUES(get_uuid(), '', '', '', '', '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', 0, '', 0, 0, 0, false, 0, 0, 0, '', false, 0, 0, '', '');

//insert with prepare

$uuid = str_replace('-','', get_uuid_php());
$serialno = 1;


try {

    $sql = "INSERT INTO public.pos_dsales_ppob (
    pos_dsales_key, ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, 
    pos_medc_key, pos_dcashierbalance_key, pos_mbank_key, ad_muser_key, billno, billamount, 
    paymentmethodname, membercard, cardno, approvecode, edcno, bankname, serialno, billstatus, 
    paycashgiven, paygiven, printcount, issync, donasiamount, dpp, ppn, billcode, ispromomurah, 
    point, pointgive, membername, status_intransit, keterangan, trxid, reqid
) VALUES (
    :uuid, :idstore, :idstore, '1', 'now()', 'admin', 'admin', 'now()', 
    :edcdebitname, 0, :bankdebitname, 0, :billno, :amount, :metode, 
    0, 0, 0, 0, 0, :serialno, 0, :nominal, 0, 0, false, 0, 0, 0, 0, false, 0, 0, :membername, 0, :keterangan, :trxid, :reqid
)";


//get last serialno
$sqlserial = "SELECT serialno FROM public.pos_dsales_ppob WHERE ad_morg_key = :idstore ORDER BY serialno DESC LIMIT 1";
$stmtserial = $connec->prepare($sqlserial);
$stmtserial->bindValue(':idstore', $idstore, PDO::PARAM_INT);
$stmtserial->execute();
$rowserial = $stmtserial->fetch(PDO::FETCH_ASSOC);
$serialno = $rowserial['serialno'] + 1;

$ymd = date('ymd');
$billno = $value.'-PPOB-'.$ymd.'-'.$serialno;


if($metode == '1'){
    $metode = 'CASH';
    $edcdebitname = null;
    $bankdebitname = null;
}

if($metode == '2'){
    $metode = 'DEBIT';
}

if($metode == '3'){
    $metode = 'CREDIT';
}

    $stmt = $connec->prepare($sql);

    // Bind values to placeholders
    $stmt->bindValue(':idstore', $idstore, PDO::PARAM_INT);
    $stmt->bindValue(':uuid', $uuid, PDO::PARAM_STR);
    $stmt->bindValue(':edcdebitname', $edcdebitname, PDO::PARAM_STR);
    $stmt->bindValue(':bankdebitname', $bankdebitname, PDO::PARAM_STR);
    $stmt->bindValue(':nominal', $nominal, PDO::PARAM_INT);
    $stmt->bindValue(':amount', $amount, PDO::PARAM_INT);
    $stmt->bindValue(':metode', $metode, PDO::PARAM_STR);
    $stmt->bindValue(':membername', $nopelanggan, PDO::PARAM_STR);
    $stmt->bindValue(':serialno', $serialno, PDO::PARAM_INT);
    $stmt->bindValue(':billno', $billno, PDO::PARAM_STR);
    $stmt->bindValue(':trxid', $trxid, PDO::PARAM_STR);
    $stmt->bindValue(':reqid', $reqid, PDO::PARAM_STR);
    $stmt->bindValue(':keterangan', $msg, PDO::PARAM_STR);

    // Execute the statement
    if ($stmt->execute()) {
       $json = array(
           "id" => $uuid,
           "status" => "success",
           "message" => "Insert success"
       );
    } else {
        $json = array(
            "status" => "failed",
            "message" => "Insert failed",
            "error" => $stmt->errorInfo()
        );
    }
} catch (PDOException $e) {
    $json = array(
        "status" => "failed",
        "message" => $e->getMessage()
    );
}

echo json_encode($json);







?>
