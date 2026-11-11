
<?php
include "../../config/koneksi.php";

if (!isset($connec) || !($connec instanceof PDO)) {
    http_response_code(500);
    exit('Koneksi database tidak tersedia / bukan PDO');
}

// ============================================================
// Fetch API stok negatif + outstanding documents
// ============================================================
function fetch_stock_negatif($idstore) {
    $idstore = trim((string)$idstore);
    if ($idstore === '') return [];

    $url = "https://intransit.idolmartidolaku.com/apiidolmart/store/items/get_stock_negatif.php?idstore="
         . urlencode($idstore);

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER     => ['Accept: application/json'],
    ]);

    $body = curl_exec($ch);
    $err  = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($body === false || $code < 200 || $code >= 300) {
        error_log("[NS] API error HTTP $code: $err");
        return [];
    }

    $json = json_decode($body, true);

    if (!is_array($json)) {
        error_log("[NS] JSON tidak valid");
        return [];
    }

    $items = (isset($json['data']) && is_array($json['data']))
        ? $json['data']
        : $json;

    $out = [];

    foreach ($items as $it) {
        $out[] = [
            'itemsid' => $it['itemsid'] ?? ($it['item_id'] ?? ''),
            'itemname' => $it['itemname'] ?? ($it['item_name'] ?? ''),
            'stock' => $it['stock'] ?? ($it['qty'] ?? 0),
            'outstanding_documents' => $it['outstanding_documents'] ?? [],
            'outstanding_documents_text' => $it['outstanding_documents_text'] ?? ''
        ];
    }

    return $out;
}

// ============================================================
// Simpan snapshot stok saja (tidak menyimpan outstanding)
// ============================================================
function replace_snapshot(PDO $connec, array $items): bool {
    try {
        $connec->beginTransaction();

        $connec->exec("DELETE FROM public.list_items_negatif");

        if (!empty($items)) {
            $sql = "INSERT INTO public.list_items_negatif
                        (id, sku, item_name, stock, created_at, updated_at)
                    VALUES
                        (:id, :sku, :item_name, :stock, now(), now())";

            $st = $connec->prepare($sql);

            foreach ($items as $idx => $it) {
                $itemsid = (string)($it['itemsid'] ?? '');

                $rowId = $itemsid !== '' ? $itemsid : ('row' . $idx);

                if (strlen($rowId) > 50) {
                    $rowId = substr($rowId, 0, 50);
                }

                $st->execute([
                    ':id' => $rowId,
                    ':sku' => substr($itemsid, 0, 50),
                    ':item_name' => mb_substr(
                        (string)($it['itemname'] ?? ''),
                        0,
                        255
                    ),
                    ':stock' => (int)($it['stock'] ?? 0),
                ]);
            }
        }

        $connec->commit();
        return true;

    } catch (Throwable $e) {
        if ($connec->inTransaction()) {
            $connec->rollBack();
        }

        error_log('[NS] replace snapshot gagal: ' . $e->getMessage());
        return false;
    }
}

// ============================================================
// Load snapshot dari DB
// ============================================================
function load_snapshot(PDO $connec): array {
    $st = $connec->query("
        SELECT
            sku AS itemsid,
            item_name AS itemname,
            stock
        FROM public.list_items_negatif
        ORDER BY sku
    ");

    return $st->fetchAll(PDO::FETCH_ASSOC);
}

// ============================================================
// Main
// ============================================================
$idstore     = trim((string)($_GET['idstore'] ?? ''));
$shopName    = $_GET['shopname'] ?? '';
$cashierName = $_GET['cashier'] ?? '';
$isReprint   = !empty($_GET['reprint']);

$warnMessage = '';

if ($isReprint) {

    // REPRINT: stok tetap dari snapshot database
    $items = load_snapshot($connec);

    if (empty($items)) {
        $warnMessage = 'Snapshot lokal kosong — belum pernah ada cetakan pertama.';
    }

} else {

    // CETAK PERTAMA: ambil stok dari API
    $items = fetch_stock_negatif($idstore);

    if (!replace_snapshot($connec, $items)) {
        $warnMessage = 'Snapshot gagal disimpan.';
    }
}

// ============================================================
// Ambil outstanding dari API UNTUK CETAK PERTAMA DAN REPRINT
// ============================================================
$apiItems = fetch_stock_negatif($idstore);

// Mapping outstanding berdasarkan SKU
$outstandingMap = [];

foreach ($apiItems as $apiItem) {
    $sku = trim((string)($apiItem['itemsid'] ?? ''));

    if ($sku === '') continue;

    $outstandingMap[$sku] = [
        'outstanding_documents' =>
            $apiItem['outstanding_documents'] ?? [],

        'outstanding_documents_text' =>
            $apiItem['outstanding_documents_text'] ?? ''
    ];
}

// Gabungkan outstanding API ke data yang akan dicetak
foreach ($items as &$item) {
    $sku = trim((string)($item['itemsid'] ?? ''));

    $item['outstanding_documents'] =
        $outstandingMap[$sku]['outstanding_documents'] ?? [];

    $item['outstanding_documents_text'] =
        $outstandingMap[$sku]['outstanding_documents_text'] ?? '';
}
unset($item);

$now = new DateTime();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Laporan Stok Negatif</title>

<style>
    body {
        font-family: Arial;
        font-size: 10px;
        margin: 20px;
    }

    h2 {
        text-align: center;
        margin-bottom: 5px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td, th {
        border: 1px solid #999;
        padding: 6px;
    }

    th {
        background: #eee;
    }

    .warn {
        background: #fff3cd;
        border: 1px solid #ffc107;
        padding: 8px;
        margin-bottom: 10px;
    }

    .meta td {
        border: none;
    }

    @page {
        size: A4 portrait;
        margin: 10mm;
    }

    @media print {
        body {
            margin: 0;
        }

        .no-print {
            display: none;
        }

        .warn {
            display: none;
        }
    }
</style>
</head>

<body>

<?php if ($warnMessage !== ''): ?>
    <div class="warn no-print">
        <?= htmlspecialchars($warnMessage) ?>
    </div>
<?php endif; ?>

<h2>
    LAPORAN STOK NEGATIF<?= $isReprint ? ' (REPRINT)' : '' ?>
</h2>

<div style="text-align:center">
    <?= htmlspecialchars($shopName) ?>
</div>

<table class="meta" style="width:auto;margin-bottom:15px">
    <tr>
        <td>Tanggal</td>
        <td>: <?= $now->format('d F Y') ?></td>
    </tr>
    <tr>
        <td>Jam</td>
        <td>: <?= $now->format('H:i:s') ?></td>
    </tr>
    <tr>
        <td>Kasir</td>
        <td>: <?= htmlspecialchars($cashierName) ?></td>
    </tr>
    <tr>
        <td>Total Item</td>
        <td>: <?= count($items) ?></td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th width="40">No</th>
            <th>Item ID</th>
            <th>Nama Items</th>
            <th width="70">Stok</th>
            <th>Document Outstanding</th>
        </tr>
    </thead>

    <tbody>

    <?php if (empty($items)): ?>

        <tr>
            <td colspan="5" style="text-align:center">
                Tidak ada data.
            </td>
        </tr>

    <?php else: ?>

        <?php foreach ($items as $i => $it): ?>

            <tr>
                <td><?= $i + 1 ?></td>

                <td>
                    <?= htmlspecialchars((string)$it['itemsid']) ?>
                </td>

                <td>
                    <?= htmlspecialchars((string)$it['itemname']) ?>
                </td>

                <td style="text-align:right">
                    <?= (int)$it['stock'] ?>
                </td>

                <td>
                    <?= nl2br(htmlspecialchars(
                        (string)($it['outstanding_documents_text'] ?? ''),
                        ENT_QUOTES,
                        'UTF-8'
                    )) ?>
                </td>
            </tr>

        <?php endforeach; ?>

    <?php endif; ?>

    </tbody>
</table>

<br><br><br>

<div style="display:flex;justify-content:space-between;margin-top:20px">
    <div>Dicetak: <?= $now->format('H:i') ?> WIB</div>
    <div>Tanda tangan: ____________________</div>
</div>

<script>
window.onload = () => window.focus();
</script>

</body>
</html>
