<?php
// api/sync_ppob.php (LOKAL)
include "../../config/koneksi.php";
header("Content-Type: application/json");


error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // pastikan error mode
    $connec->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ambil idstore (org aktif)
    $idstore = $connec->query("SELECT ad_morg_key FROM ad_morg WHERE isactived = 'Y' LIMIT 1")->fetchColumn();
    if (!$idstore) {
        echo json_encode(["status" => "FAILED", "message" => "Tidak menemukan ad_morg aktif."]);
        exit;
    }

    // ambil tanggal (opsional)
    $date = $_POST['date'] ?? $_GET['date'] ?? null;
    $lastsync = $date ? ($date . " 00:00:00") : '';

    // ambil dari server
    $url = $base_url.'/store/ppob/get_sales_ppob.php?idstore=' . urlencode($idstore);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 120,
    ]);
    $response = curl_exec($ch);

    // print_r($response);


    if ($response === false) {
        throw new Exception("cURL Error: " . curl_error($ch));
    }
    curl_close($ch);

    $rows = json_decode($response, true);

    // echo $url;
    // print_r($rows);

    if (!is_array($rows)) {
        // jika server balikin objek error
        throw new Exception("Format server tidak valid: " . substr($response, 0, 300));
    }

    // siapkan UPSERT
    $sqlUpsert = "
        INSERT INTO pos_dsales_ppob (
            pos_dsales_key, ad_mclient_key, ad_morg_key, isactived, insertdate, insertby,
            postby, postdate, pos_medc_key, pos_dcashierbalance_key, pos_mbank_key, ad_muser_key,
            billno, billamount, paymentmethodname, membercard, cardno, approvecode, edcno, bankname,
            serialno, billstatus, paycashgiven, paygiven, printcount, issync, donasiamount, dpp, ppn,
            billcode, ispromomurah, point, pointgive, membername, status_intransit, keterangan, trxid, reqid
        ) VALUES (
            :pos_dsales_key, :ad_mclient_key, :ad_morg_key, :isactived, :insertdate, :insertby,
            :postby, :postdate, :pos_medc_key, :pos_dcashierbalance_key, :pos_mbank_key, :ad_muser_key,
            :billno, :billamount, :paymentmethodname, :membercard, :cardno, :approvecode, :edcno, :bankname,
            :serialno, :billstatus, :paycashgiven, :paygiven, :printcount, TRUE, :donasiamount, :dpp, :ppn,
            :billcode, :ispromomurah, :point, :pointgive, :membername, :status_intransit, :keterangan, :trxid, :reqid
        )
        ON CONFLICT (pos_dsales_key) DO UPDATE SET
            ad_mclient_key = EXCLUDED.ad_mclient_key,
            ad_morg_key = EXCLUDED.ad_morg_key,
            isactived = EXCLUDED.isactived,
            insertdate = EXCLUDED.insertdate,
            insertby = EXCLUDED.insertby,
            postby = EXCLUDED.postby,
            postdate = EXCLUDED.postdate,
            pos_medc_key = EXCLUDED.pos_medc_key,
            pos_dcashierbalance_key = EXCLUDED.pos_dcashierbalance_key,
            pos_mbank_key = EXCLUDED.pos_mbank_key,
            ad_muser_key = EXCLUDED.ad_muser_key,
            billno = EXCLUDED.billno,
            billamount = EXCLUDED.billamount,
            paymentmethodname = EXCLUDED.paymentmethodname,
            membercard = EXCLUDED.membercard,
            cardno = EXCLUDED.cardno,
            approvecode = EXCLUDED.approvecode,
            edcno = EXCLUDED.edcno,
            bankname = EXCLUDED.bankname,
            serialno = EXCLUDED.serialno,
            billstatus = EXCLUDED.billstatus,
            paycashgiven = EXCLUDED.paycashgiven,
            paygiven = EXCLUDED.paygiven,
            printcount = EXCLUDED.printcount,
            issync = TRUE,
            donasiamount = EXCLUDED.donasiamount,
            dpp = EXCLUDED.dpp,
            ppn = EXCLUDED.ppn,
            billcode = EXCLUDED.billcode,
            ispromomurah = EXCLUDED.ispromomurah,
            point = EXCLUDED.point,
            pointgive = EXCLUDED.pointgive,
            membername = EXCLUDED.membername,
            status_intransit = EXCLUDED.status_intransit,
            keterangan = EXCLUDED.keterangan,
            trxid = EXCLUDED.trxid,
            reqid = EXCLUDED.reqid
    ";

    $stmtUpsert = $connec->prepare($sqlUpsert);

    $inserted = 0;
    $updated = 0;

    $connec->beginTransaction();

    foreach ($rows as $r) {
        $key = $r['pos_dsales_key'] ?? null;
        if (!$key)
            continue;

        // cek sebelum UPSERT
        $chk = $connec->prepare("SELECT 1 FROM pos_dsales_ppob WHERE pos_dsales_key = :k");
        $chk->execute([':k' => $key]);
        $exists = $chk->fetchColumn();

        // jalankan UPSERT
        $stmtUpsert->execute([
            ':pos_dsales_key' => $key,
            ':ad_mclient_key' => $r['ad_mclient_key'] ?? null,
            ':ad_morg_key' => $r['ad_morg_key'] ?? $idstore,
            ':isactived' => $r['isactived'] ?? 'Y',
            ':insertdate' => $r['insertdate'] ?? null,
            ':insertby' => $r['insertby'] ?? 'SYSTEM',
            ':postby' => $r['postby'] ?? 'SYSTEM',
            ':postdate' => $r['postdate'] ?? null,
            ':pos_medc_key' => $r['pos_medc_key'] ?? null,
            ':pos_dcashierbalance_key' => $r['pos_dcashierbalance_key'] ?? null,
            ':pos_mbank_key' => $r['pos_mbank_key'] ?? null,
            ':ad_muser_key' => $r['ad_muser_key'] ?? null,
            ':billno' => $r['billno'] ?? null,
            ':billamount' => $r['billamount'] ?? 0,
            ':paymentmethodname' => $r['paymentmethodname'] ?? null,
            ':membercard' => $r['membercard'] ?? null,
            ':cardno' => $r['cardno'] ?? null,
            ':approvecode' => $r['approvecode'] ?? null,
            ':edcno' => $r['edcno'] ?? null,
            ':bankname' => $r['bankname'] ?? null,
            ':serialno' => $r['serialno'] ?? null,
            ':billstatus' => $r['billstatus'] ?? null,
            ':paycashgiven' => $r['paycashgiven'] ?? 0,
            ':paygiven' => $r['paygiven'] ?? 0,
            ':printcount' => $r['printcount'] ?? 0,
            ':donasiamount' => $r['donasiamount'] ?? 0,
            ':dpp' => $r['dpp'] ?? 0,
            ':ppn' => $r['ppn'] ?? 0,
            ':billcode' => $r['billcode'] ?? null,
            ':ispromomurah' => isset($r['ispromomurah']) ? (int) !!$r['ispromomurah'] : 0,
            ':point' => $r['point'] ?? 0,
            ':pointgive' => $r['pointgive'] ?? 0,
            ':membername' => $r['membername'] ?? null,
            ':status_intransit' => $r['status_intransit'] ?? null,
            ':keterangan' => $r['keterangan'] ?? null,
            ':trxid' => $r['trxid'] ?? null,
            ':reqid' => $r['reqid'] ?? null,
        ]);

        if ($exists) {
            $updated++;
        } else {
            $inserted++;
        }
    }


    $connec->commit();

    echo json_encode([
        "status" => "OK",
        "received" => count($rows),
        "inserted" => $inserted,
        "updated" => $updated
    ]);
} catch (Throwable $e) {
    if ($connec && $connec->inTransaction()) {
        $connec->rollBack();
    }
    http_response_code(500);
    echo json_encode(["status" => "FAILED", "message" => $e->getMessage()]);
}
