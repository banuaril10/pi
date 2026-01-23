<?php
include "config/koneksi.php";
include "components/main.php";
include "components/sidebar.php";

// $id_location = $_SESSION['id_location'];

$date_awal  = $_GET['date_awal'] ?? date('Y-m-d');
$date_akhir = $_GET['date_akhir'] ?? date('Y-m-d');


				$sqll = "select ad_morg_key,name from ad_morg";
				$results = $connec->query($sqll);
				foreach ($results as $r) {
					$id_location = $r["ad_morg_key"];	
					$name = $r["name"];	
				}

/* =========================
   QUERY SUMMARY (PDO)
========================= */

// LIMIT VOUCHER
$stmtLimit = $connec->prepare("
	SELECT COALESCE(SUM(limit_voucher),0) AS limit_voucher
	FROM pos_mvoucher_rule
	WHERE id_location = :id_location
	AND isactived = '1'
");
$stmtLimit->execute(['id_location' => $id_location]);
$limit = (float)$stmtLimit->fetch(PDO::FETCH_ASSOC)['limit_voucher'];

// VOUCHER TERPAKAI
$stmtUsed = $connec->prepare("
	SELECT COALESCE(count(voucher_amount),0) AS total
	FROM pos_dvoucher
	WHERE id_location = :id_location
	AND status = 'USED'
	AND useddate::date BETWEEN :date_awal AND :date_akhir
");
$stmtUsed->execute([
	'id_location' => $id_location,
	'date_awal'   => $date_awal,
	'date_akhir'  => $date_akhir
]);
$used = (float)$stmtUsed->fetch(PDO::FETCH_ASSOC)['total'];

$remaining = $limit - $used;
?>

<div id="app">
<div id="main">

<header class="mb-3">
	<a href="#" class="burger-btn d-block d-xl-none">
		<i class="bi bi-justify fs-3"></i>
	</a>
</header>

<?php include "components/hhh.php"; ?>

<!-- SUMMARY -->
<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-body text-center">
				<h6>Limit Voucher</h6>
				<h3><?= number_format($limit,0) ?></h3>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="card">
			<div class="card-body text-center">
				<h6>Voucher Terpakai</h6>
				<h3><?= number_format($used,0) ?></h3>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="card">
			<div class="card-body text-center">
				<h6>Sisa Limit</h6>
				<h3 style="color:<?= ($remaining <= $limit*0.2 ? 'red':'green') ?>">
					<?= number_format($remaining,0) ?>
				</h3>
			</div>
		</div>
	</div>
</div>

<!-- FILTER -->
<div class="card">
	<div class="card-body">
		<form method="GET">
			<div class="row">
				<div class="col-md-3">
					<label>From Date</label>
					<input type="date" name="date_awal" class="form-control"
						value="<?= $date_awal ?>">
				</div>
				<div class="col-md-3">
					<label>To Date</label>
					<input type="date" name="date_akhir" class="form-control"
						value="<?= $date_akhir ?>">
				</div>
				<div class="col-md-3" style="padding-top:28px">
					<button class="btn btn-primary">
						<i class="bi bi-search"></i> Search
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- TABLE -->
<div class="card">
	<div class="card-header">
		<h4>Detail Pemakaian Voucher</h4>
	</div>
	<div class="card-body">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>No</th>
					<th>Kode Voucher</th>
					<th>Nilai Voucher</th>
					<th>Tanggal Pakai</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$stmt = $connec->prepare("
				SELECT voucher_code, voucher_amount, useddate
				FROM pos_dvoucher
				WHERE id_location = :id_location
				AND status = 'USED'
				AND useddate::date BETWEEN :date_awal AND :date_akhir
				ORDER BY useddate DESC
			");
			$stmt->execute([
				'id_location' => $id_location,
				'date_awal'   => $date_awal,
				'date_akhir'  => $date_akhir
			]);

			$no = 1;
			while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
				echo "<tr>
					<td>{$no}</td>
					<td>{$r['voucher_code']}</td>
					<td>".number_format($r['voucher_amount'],0)."</td>
					<td>".date('d-m-Y H:i', strtotime($r['useddate']))."</td>
				</tr>";
				$no++;
			}
			?>
			</tbody>
		</table>
	</div>
</div>

</div>
</div>

<?php include "components/fff.php"; ?>
