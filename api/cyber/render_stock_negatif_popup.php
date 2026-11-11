<?php
header('Content-Type: application/json');
header('Cache-Control: no-store');

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
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($body === false || $code < 200 || $code >= 300) return [];

    $json = json_decode($body, true);
    if (!is_array($json)) return [];

    $items = (isset($json['data']) && is_array($json['data'])) ? $json['data'] : $json;

    $out = [];
    foreach ($items as $it) {
        $out[] = [
            'itemsid'  => $it['itemsid']  ?? ($it['item_id']   ?? ''),
            'itemname' => $it['itemname'] ?? ($it['item_name'] ?? ''),
            'stock'    => $it['stock']    ?? ($it['qty']       ?? 0),
        ];
    }
    return $out;
}

$idstore = $_GET['idstore'] ?? '';
if ($idstore === '') { http_response_code(400); echo json_encode(['error' => 'idstore kosong']); exit; }

$items = fetch_stock_negatif($idstore);

ob_start();
?>

<!-- HEADER: flex-shrink: 0 → selalu tampil di atas, gak ikut scroll -->
<div style="
    background: linear-gradient(135deg,#d9534f,#c9302c);
    color:#fff; padding: 18px 24px;
    flex-shrink: 0;
">
    <div style="font-size:20px; font-weight:700;">⚠️ Peringatan Stok Negatif</div>
    <div style="font-size:13px; opacity:.9; margin-top:2px;">
        Terdapat <?= count($items) ?> item dengan stok minus. Mohon segera ditindaklanjuti.
    </div>
</div>

<!-- SCROLL AREA: 3 properti ini WAJIB ada barengan -->
<div style="
    flex: 1 1 auto;
    overflow-y: auto;
    min-height: 0;
">
    <table style="width:100%; border-collapse: collapse; font-size:13px;">
        <thead style="position: sticky; top:0; background:#f7f7f7; z-index:1;">
            <tr>
                <th style="padding:10px; border-bottom:2px solid #ddd; width:50px;">No</th>
                <th style="padding:10px; border-bottom:2px solid #ddd; text-align:left;">Item ID</th>
                <th style="padding:10px; border-bottom:2px solid #ddd; text-align:left;">Nama Item</th>
                <th style="padding:10px; border-bottom:2px solid #ddd; text-align:right; width:80px;">Stok</th>
                <th style="padding:10px;border-bottom:2px solid #ddd;text-align:left;">
    Document Outstanding
</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($items) === 0): ?>
            <tr><td colspan="4" style="padding:24px; text-align:center; color:#666;">Tidak ada item stok negatif.</td></tr>
        <?php else: ?>
            <?php foreach ($items as $i => $it): ?>
            <tr>
                <td style="text-align:center; padding:10px; border-bottom:1px solid #eee;"><?= $i+1 ?></td>
                <td style="padding:10px; border-bottom:1px solid #eee;"><?= htmlspecialchars($it['itemsid']) ?></td>
                <td style="padding:10px; border-bottom:1px solid #eee;"><?= htmlspecialchars($it['itemname']) ?></td>
                <td style="text-align:right; color:#c00; font-weight:700; padding:10px; border-bottom:1px solid #eee;"><?= htmlspecialchars($it['stock']) ?></td>
                <td style="padding:10px; border-bottom:1px solid #eee; text-align:left;">
                    <?= htmlspecialchars($it['outstanding_documents_text'] ?? '') ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- FOOTER: flex-shrink: 0 → selalu tampil di bawah -->
<div style="
    padding: 16px 24px; border-top: 1px solid #eee;
    display:flex; justify-content:flex-end; gap:10px; background:#fafafa;
    flex-shrink: 0;
">
    <button id="ns-ack-btn" style="
        background:#5cb85c; color:#fff; border:none;
        padding: 10px 22px; border-radius:10px; font-weight:600;
        cursor:pointer; font-size:14px;
    " <?= count($items) === 0 ? 'disabled' : '' ?>>Print</button>
</div>

<?php
$html = ob_get_clean();
echo json_encode(['html' => $html, 'count' => count($items)]);