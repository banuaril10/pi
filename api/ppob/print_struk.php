<?php include "../../config/koneksi.php";

$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}


$id = $_GET['id'];

$sql = "SELECT pos_dsales_key, ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, pos_medc_key, pos_dcashierbalance_key, pos_mbank_key, ad_muser_key, billno, billamount, paymentmethodname, membercard, cardno, approvecode, 
edcno, bankname, serialno, billstatus, paycashgiven, paygiven, printcount, issync, donasiamount, dpp, ppn, billcode, ispromomurah, point, pointgive, membername, status_intransit, keterangan, trxid
FROM public.pos_dsales_ppob WHERE pos_dsales_key = :id";
$stmt = $connec->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$data = array(
    'pos_dsales_key' => $row['pos_dsales_key'],
    'ad_mclient_key' => $row['ad_mclient_key'],
    'ad_morg_key' => $row['ad_morg_key'],
    'isactived' => $row['isactived'],
    'insertdate' => $row['insertdate'],
    'insertby' => $row['insertby'],
    'postby' => $row['postby'],
    'postdate' => $row['postdate'],
    'pos_medc_key' => $row['pos_medc_key'],
    'pos_dcashierbalance_key' => $row['pos_dcashierbalance_key'],
    'pos_mbank_key' => $row['pos_mbank_key'],
    'ad_muser_key' => $row['ad_muser_key'],
    'billno' => $row['billno'],
    'billamount' => rupiah_pos($row['billamount']),
    'paymentmethodname' => $row['paymentmethodname'],
    'membercard' => $row['membercard'],
    'cardno' => $row['cardno'],
    'approvecode' => $row['approvecode'],
    'edcno' => $row['edcno'],
    'bankname' => $row['bankname'],
    'serialno' => $row['serialno'],
    'billstatus' => $row['billstatus'],
    'paycashgiven' => rupiah_pos($row['paycashgiven']),
    'kembalian' => rupiah_pos(($row['billamount'] - $row['paycashgiven'])),
    'paygiven' => $row['paygiven'],
    'printcount' => $row['printcount'],
    'issync' => $row['issync'],
    'donasiamount' => $row['donasiamount'],
    'dpp' => $row['dpp'],
    'ppn' => $row['ppn'],
    'billcode' => $row['billcode'],
    'ispromomurah' => $row['ispromomurah'],
    'point' => $row['point'],
    'pointgive' => $row['pointgive'],
    'membername' => $row['membername'],
    'status_intransit' => $row['status_intransit'],
    'strdate'=> date('d-m-Y', strtotime($row['insertdate'])),
    'strtime'=> date('H:i:s', strtotime($row['insertdate'])),
    'keterangan' => $row['keterangan'],
    'trxid' => $row['trxid'],
    'reqid' => $row['reqid'],

);

echo json_encode($data);






?>
