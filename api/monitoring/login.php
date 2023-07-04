

<!DOCTYPE html>
<html>
<head>
	<title>INPUT JADWAL TRAINING </title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
	<fieldset class="container">
		<legend><h2 class="judul">INPUT JADWAL TRAINING TOKO</h2></legend>

		<a href="index.php" class="button-home">Home</a>

		<a href="login.php" class="button-refresh">Refresh</a>

		<form class="form" method="post" action="">
			<p class="nama-training">
				<label for="nama-training">
					Nama Training
				</label><br>
				<input type="text" name="nama_training" placeholder="Masukan Nama Training" class="input_nama">
			</p>

			<p class="kode_toko">
				<label for="kode_toko">
					Kode Toko
				</label>

				<?php
				include('checkbox.php'); 
				?>

			</p>	

			<p class="waktu-mulai">
				<label for="jam-mulai">
					Jam Mulai Training (f:24h)
				</label><br/>
				<input type="text" name="jam_mulai" class="input_waktu" placeholder="00">
				<strong>
					:
				</strong>
				<input type="text" name="menit_mulai" class="input_waktu" placeholder="00">
			</p>

			<p class="waktu-selesai">
				<label for="jam-mulai">
					Jam Selesai Training (f:24h)
				</label><br/>
				<input type="text" name="jam_selesai" class="input_waktu" placeholder="00">
				<strong>
					:
				</strong>
				<input type="text" name="menit_selesai" class="input_waktu" placeholder="00">
			</p>

			<p>
				<label for="start_date">
					Tanggal Mulai Training
				</label><br>
				<select name="tanggal_mulai">
				<?php 
				include('tanggal.php');
				?>
				</select>
				/
				<select name="bulan_mulai">
				<?php 
				include('bulan.php');
				?>
				</select>
				/
				<input type="text" name="tahun_mulai" value="2021" readonly style="width: 50px">
			</p>

			<p>
				<label for="stop_date">
					Tanggal Selesai Training
				</label><br>
				<select name="tanggal_selesai">
				<?php 
				include('tanggal.php');
				?>
				</select>
				/
				<select name="bulan_selesai">
				<?php 
				include('bulan.php');
				?>
				</select>
				/
				<input type="text" name="tahun_selesai" value="2021" readonly style="width: 50px">
			</p>

			<input type="submit" value="Simpan" class="button">

		</form>
	</fieldset>
</body>
</html>

<?php 

require_once('api.php');
$API = new routeros_api();
$API->debug = false;


$username = 'noc';
$password = 'idol0617noc';
$waktu_mulai = $_POST['jam_mulai'].':'.$_POST['menit_mulai'].':00';
$waktu_selesai = $_POST['jam_selesai'].':'.$_POST['menit_selesai'].':00';
$start_date = $_POST['bulan_mulai'].'/'.$_POST['tanggal_mulai'].'/'.$_POST['tahun_mulai'];
$stop_date = $_POST['bulan_selesai'].'/'.$_POST['tanggal_selesai'].'/'.$_POST['tahun_selesai'];
$stop_event = '/system scheduler remove numbers=0,1,2';

foreach ($_POST['kode_toko'] as $key => $value) {
	$kode = explode('|', $value);
	$ip = '10.0.'.$kode[1].'.1';

	if ($API->connect($ip,$username,$password)) {
		$array = array(
				"name" => $_POST['nama_training'].'_mulai' ,
				"interval" => "1d",
				"start-time" => $waktu_mulai,
				"start-date" => $start_date,
				"on-event" => "net_on"
			);
		// Command Jam Training dimulai
		$API->comm("/system/scheduler/add", $array);

		// Command Jam Training dimulai
		$API->comm("/system/scheduler/add",
			array(
				"name" => $_POST['nama_training'].'_selesai' ,
				"interval" => "1d",
				"start-time" => $waktu_selesai,
				"start-date" => $start_date,
				"on-event" => "net_off"
			)
		);

		//Command Tanggal selesai Training
		$tes=$API->comm("/system/scheduler/add",
			array(
				"name" => $_POST['nama_training'].'_hapus',
				"start-time" => "20:50:00",
				"start-date" => $stop_date,
				"on-event" => $stop_event
			)
		);

		echo "<font class='notif-green'>Berhasil!!!... Toko ".$kode[0]." akan diaktifkan Wifinya tanggal ".$start_date."
		sampai ".$stop_date." pada jam ".$waktu_mulai." sampai ".$waktu_selesai."!</font><br>";

	}else{
		echo "<font class='notif-red'>Router toko ".$kode[0]." tidak terkoneksi. Harap hubungi pihak IT NOC!</font><br>";
	}

}

?>
