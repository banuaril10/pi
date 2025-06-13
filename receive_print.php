<?php include "config/koneksi.php"; ?>
<?php include "components/main.php"; ?>
<?php include "components/sidebar.php"; ?>

<?php
$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	$idstore = $row['ad_morg_key'];
	$name = $row['name'];
}

?>

<style>
	.grid-container {
		display: grid;
		grid-template-columns: auto auto auto auto;
		background-color: #2196F3;
		padding: 10px;
	}

	.grid-item {
		background-color: rgb(255, 255, 255, 0.8);
		border: 1px solid rgb(0, 0, 0, 0.8);
		padding: 20px;
		font-size: 30px;
		text-align: center;
	}
</style>

<div id="overlay">
	<div class="cv-spinner">
		<span class="spinner"></span>
	</div>
</div>

<div id="app">
	<div id="main">
		<header class="mb-3">
			<a href="#" class="burger-btn d-block d-xl-none">
				<i class="bi bi-justify fs-3"></i>
			</a>
		</header>
		<?php include "components/hhh.php"; ?>
		<!------ CONTENT AREA ------>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h4>RECEIVE PRINT</h4>
						<div class="form-inline">
							<input id="tglAwal" type="date" class="form-control mr-2">
							<input id="tglAkhir" type="date" class="form-control mr-2">
							<button id="filterBtn" class="btn btn-primary mr-2">Filter</button>
						</div>
						<br>
						<button class='btn btn-info' onclick='printReceiveAll()'>Cetak All</button>

						<p id="notif1" style="color: red; font-weight: bold;"></p>
					</div>
					<div class="card-body">
						<table class="table table-bordered" id="example">
							<thead>
								<tr>
									<th>Document Number</th>
									<th>Driver</th>
									<th>Keycode</th>
									<th>Insert Date</th>
									<th>Is Receipt</th>
									<th>Receipt Date</th>
									<th>Receipt By</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function () {
			var table = $('#example').DataTable({
				lengthMenu: [
					[10, 25, 50, -1],
					[10, 25, 50, 'All']
				],
			});

			function loadData(tglAwal, tglAkhir) {
				var url = 'https://api.idolmartidolaku.com/apiidolmart/store/receive/get_receive.php';
				var data = {};

				data.id_master_location = <?php echo $idstore; ?>;
				if (tglAwal) data.tgl_awal = tglAwal;
				if (tglAkhir) data.tgl_akhir = tglAkhir;

				$.ajax({
					url: url,
					dataType: 'json',
					data: data,
					success: function (data) {
						table.clear();

						data.forEach(function (item) {
							table.row.add([
								item.documentno,
								item.driver,
								item.keycode,
								item.insertdate,
								item.is_receipt,
								item.receipt_date,
								item.receipt_by,
								"<button class='btn btn-info' onclick='printReceive(\"" + item.documentno + "\")'>Cetak</button>"
							]);
						});

						table.draw();

					},
					error: function (err) {
						console.error(err);
						$('#notif1').text("Gagal mengambil data.");
					}
				});
			}

			// Tombol Filter
			$('#filterBtn').on('click', function () {
				var tglAwal = $('#tglAwal').val();
				var tglAkhir = $('#tglAkhir').val();

				loadData(tglAwal, tglAkhir);
			});

			// Loading pertama
			loadData();

		});




		function printReceive(id) {
    // cari row yang sesuai
			var row =$('#example tbody tr').filter(function(){
				return $(this).find("td").first().text() == id;
			});

			var documentno = row.find("td").eq(0).text();
			var driver = row.find("td").eq(1).text();
			var keycode = row.find("td").eq(2).text();
			var insertdate = row.find("td").eq(3).text();
			var is_receipt = row.find("td").eq(4).text();
			var receipt_date = row.find("td").eq(5).text();


			var header = "Driver : " + driver + "\n" + 
				// "Keycode : " + keycode + "\n" + 
				// "Insert Date : " + insertdate + "\n" + 
				// "Is Receipt : " + is_receipt + "\n" + 
				"Receipt Date : " + receipt_date + "\n" + 
				"________________________________\n";


			// susun struknya sesuai data yang tersedia
			var struk = 
				"DOCUMENT NO : " + documentno + "\n" + 
				"________________________________\n" + 
				


			// Kirim ke server untuk dicetak
			$.post("printer/print_receive.php", {html: struk}, function(data) {
				console.log(data);
				if (data.result == 1) {
					alert("Struk berhasil dicetak!");
					console.log("Struk berhasil dicetak: " + struk);
				} else {
					alert("Struk gagal dicetak!");
				}
			}, "json").fail(function(){
				alert("Terjadi kesalahan saat mencetak.");
			});
		}


		function printReceiveAll() {
			var struk = '';
			var header = '';
		$('#example tbody tr').each(function(){
				var row = $(this);
				var documentno = row.find("td").eq(0).text();
				var driver = row.find("td").eq(1).text();
				var keycode = row.find("td").eq(2).text();
				var insertdate = row.find("td").eq(3).text();
				var is_receipt = row.find("td").eq(4).text();
				var receipt_date = row.find("td").eq(5).text();
				var receipt_by = row.find("td").eq(6).text();

				header = "STRUK PENERIMAAN BARANG \n" + 
				"Toko : <?php echo $name; ?>\n" + 
				"Receipt Date : " + receipt_date + "\n" + 
				"Receipt By : " + receipt_by + "\n" + 
				"________________________________\n";

				struk += 
					"DOC NO : " + documentno + " - " + driver + "\n";

			});
			struk += "________________________________\n";
			struk += "Print Date : <?php echo date('Y-m-d H:i:s'); ?>\n";
			struk += "STRUK INI SEBAGAI BUKTI\n";
			struk += "PENERIMAAN BARANG YANG SAH OLEH TOKO\n";

			// Kirim ke server untuk dicetak
			$.post("printer/print_receive.php", {html: header+struk}, function(data) {
				console.log(data);
				if (data.result == 1) {
					alert("Struk berhasil dicetak!");
					console.log("Struk berhasil dicetak:\n" + struk);
				} else {
					alert("Struk gagal dicetak!");
				}
			}, "json").fail(function(){
				alert("Terjadi kesalahan saat mencetak.");
			});
		}





	</script>

</div>

<?php include "components/fff.php"; ?>