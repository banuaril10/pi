<?php include "config/koneksi.php"; ?>
<?php session_start();
if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
	$org_key = $_SESSION['org_key'];
	$username = $_SESSION['username'];
}else{
	header("Location: index.php");
}

$get_nama_toko = "select * from ad_morg where postby = 'SYSTEM'";
$resultss = $connec->query($get_nama_toko);
foreach ($resultss as $r) {
	$storecode = $r["value"];	
	$org_key = $r['ad_morg_key'];
}

// Get filter from URL
$filter_category = isset($_GET['category']) ? $_GET['category'] : '';
$filter_search = isset($_GET['search']) ? $_GET['search'] : '';

// Ambil data toko
$toko = '';
$value = '';
$cek_brand = "select * from ad_morg where postby = 'SYSTEM'";
foreach ($connec->query($cek_brand) as $row) {
	$toko = $row['name'];
	$value = $row['value'];
}

// Ambil data dari API KHUSUS REJECT
$date_now = date("Y-m-d");
$json_url = "https://mkt.idolmartidolaku.com/api/get_sku_plano_reject.php?tgl=".$date_now."&toko=".$value;
$options = stream_context_create(array('http'=>
	array(
	'timeout' => 10
	)
));

$json = @file_get_contents($json_url, false, $options);
$arr_all = json_decode($json, true);

function getKategoriFromDesk($desk){
    $desk = strip_tags($desk);

    if (preg_match('/Kategori\s*:\s*(.+)/i', $desk, $match)) {
        return trim(explode("\n", $match[1])[0]);
    }

    return '';
}

// Kumpulkan kategori unik dari data API
$categories = array();

if(is_array($arr_all)){
    foreach($arr_all as $item){
        $kategori = getKategoriFromDesk($item['desk']);
        if(!empty($kategori)){
            $categories[] = $kategori;
        }
    }
    $categories = array_unique($categories);
    sort($categories);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Store Apps - Reject</title>
    <link rel="stylesheet" href="styles/css/bootstrap.css">
    <link rel="stylesheet" href="styles/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="styles/css/selectize4.css">
    <link rel="stylesheet" href="styles/css/font-awesome.css">
	
	<style>
		.selectize {
			border-color: #000;
			margin-bottom: 10px;
		}
		.filter-section {
			background: #f8f9fa;
			padding: 15px;
			border-radius: 8px;
			margin-bottom: 20px;
		}
		.status-reject {
			background-color: #ffebee !important;
			border-left: 5px solid #f44336 !important;
		}
		.badge-reject {
			background-color: #f44336;
			color: white;
			padding: 5px 15px;
			border-radius: 20px;
			font-weight: bold;
		}
		.reject-header {
			background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%) !important;
			color: #fff !important;
		}
		.file-upload-section {
			background: #f5f5f5;
			padding: 15px;
			border-radius: 8px;
			margin: 10px 0;
			border: 1px solid #ddd;
		}
		.file-upload-section .btn-upload {
			margin-top: 5px;
		}
		.file-upload-section label {
			font-weight: bold;
			color: #d32f2f;
		}
	</style>

    <link rel="stylesheet" href="assets/vendors/iconly/bold.css">
    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/css/app.css">
	<script src="styles/js/jquery-3.5.1.js"></script>	
</head>
<body>

<?php include "components/sidebar.php"; ?>

<style>
.progress {
  height: 20px;
  border-radius: 4px;
  margin: 10px 0;
  background-color: #e6e8ec;
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
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header reject-header">
				<h4><span class="badge-reject">REJECT</span> CAPTURE DISPLAY PLANOGRAM - DITOLAK</h4>
			</div>
			<div class="card-body">
			<div class="tables">			
				<div class="table-responsive bs-example widget-shadow">	
				<p id="notif1" style="color: red; font-weight: bold"></p>		
				
				<!-- FILTER SECTION -->
				<div class="filter-section">
					<form method="GET" action="">
						<div class="row align-items-end">
							<div class="col-md-4">
								<label class="form-label fw-bold">Filter Kategori</label>
								<select name="category" class="form-select">
									<option value="">Semua Kategori</option>
									<?php 
									if(!empty($categories)){
										foreach($categories as $cat): 
									?>
									<option value="<?= htmlspecialchars($cat) ?>" <?= $filter_category == $cat ? 'selected' : '' ?>>
										<?= htmlspecialchars($cat) ?>
									</option>
									<?php 
										endforeach;
									} else {
										echo '<option value="">Tidak ada kategori</option>';
									}
									?>
								</select>
							</div>
							<div class="col-md-4">
								<label class="form-label fw-bold">Cari SKU / Nama</label>
								<input type="text" name="search" class="form-control" placeholder="Cari..." value="<?= htmlspecialchars($filter_search) ?>">
							</div>
							<div class="col-md-4">
								<button type="submit" class="btn btn-danger w-100">
									<i class="bi bi-search"></i> Filter
								</button>
								<a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary w-100 mt-1">
									<i class="bi bi-arrow-counterclockwise"></i> Reset
								</a>
							</div>
						</div>
					</form>
				</div>
				
				<table class="table table-bordered table-striped" id="" style="width: 100%">
					
					<tbody>
					
					<?php 
					
					$arr = $arr_all;
					$jum = count($arr);
					$no = 1;
					
					if($jum > 0){
					foreach ($arr as $row1) {
						
						// APPLY FILTERS
						$show = true;
						
						// Filter by category
						$kategori = getKategoriFromDesk($row1['desk']);

						if($filter_category != '' && $kategori != $filter_category){
							$show = false;
						}
						
						// Filter by search
						if($filter_search != ''){
							$search_lower = strtolower($filter_search);
							$sku_lower = strtolower($row1['sku']);
							$nama_lower = strtolower($row1['nama']);
							
							// Get product name for search
							$name = "-";
							$cek_name = "select name from pos_mproduct where sku = '".$row1['sku']."'";
							foreach ($connec->query($cek_name) as $row_dis) {
								$name = $row_dis['name'];
							}
							$name_lower = strtolower($name);
							
							if(strpos($sku_lower, $search_lower) === false && 
							   strpos($nama_lower, $search_lower) === false && 
							   strpos($name_lower, $search_lower) === false){
								$show = false;
							}
						}
						
						if(!$show){
							continue;
						}
						
						$name = "-";
						$cek_name = "select name from pos_mproduct where sku = '".$row1['sku']."'";
						foreach ($connec->query($cek_name) as $row_dis) {
							$name = $row_dis['name'];
						}
						
						$img = '<img src="images/no-image.png" style="width: 200px"></img>';
						$img2 = '<img src="images/no-image.png" style="width: 200px"></img>';
						$img3 = '<img src="images/no-image.png" style="width: 200px"></img>';
						$img4 = '<img src="images/no-image.png" style="width: 200px"></img>';
						$img5 = '<img src="images/no-image.png" style="width: 200px"></img>';
						$img_sample = '<img src="images/no-image.png" style="width: 400px"></img>';

						if ($row1['image'] != "") {
							$img = $row1['image'];
						}
						
						if ($row1['image2'] != "") {
							$img2 = $row1['image2'];
						}

						if ($row1['image3'] != "") {
							$img3 = $row1['image3'];
						}

						if ($row1['image4'] != "") {
							$img4 = $row1['image4'];
						}

						if ($row1['image5'] != "") {
							$img5 = $row1['image5'];
						}
						
						$img_sample = "";
						$img_sample2 = "";
						$img_sample3 = "";
						$img_sample4 = "";
						$img_sample5 = "";
						
						if($row1['file'] != ""){
							$img_sample = '<img src="'.$row1['base_url'].$row1['file'].'" style="width: 400px"></img>';
						}
						
						if($row1['file2'] != ""){
							$img_sample2 = '<img src="'.$row1['base_url'].$row1['file2'].'" style="width: 400px"></img>';
						}
						
						if($row1['file3'] != ""){
							$img_sample3 = '<img src="'.$row1['base_url'].$row1['file3'].'" style="width: 400px"></img>';
						}
						
						if($row1['file4'] != ""){
							$img_sample4 = '<img src="'.$row1['base_url'].$row1['file4'].'" style="width: 400px"></img>';
						}
						
						if($row1['file5'] != ""){
							$img_sample5 = '<img src="'.$row1['base_url'].$row1['file5'].'" style="width: 400px"></img>';
						}
						
						$reject_notes = isset($row1['reject_notes']) ? $row1['reject_notes'] : '';
						$reject_by = isset($row1['reject_by']) ? $row1['reject_by'] : '';
						
					?>
					
			
						<tr class="status-reject">
							<td colspan="5" style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%); color: #fff; font-size: 30px; padding: 20px;">
								<center>
									<i class="bi bi-x-circle-fill" style="font-size: 40px;"></i> 
									<b><?php echo $row1['nama']; ?></b> - REJECTED
									<?php if($reject_notes != ""): ?>
									<br><span style="font-size: 18px; background: rgba(255,255,255,0.2); padding: 5px 20px; border-radius: 10px; display: inline-block; margin-top: 8px;">
										<i class="bi bi-chat-left-text"></i> Alasan: <?php echo $reject_notes; ?>
									</span>
									<?php endif; ?>
									<?php if($reject_by != ""): ?>
									<br><span style="font-size: 14px; opacity: 0.9;">
										<i class="bi bi-person"></i> Ditolak oleh: <?php echo $reject_by; ?>
									</span>
									<?php endif; ?>
								</center>
							</td>
						</tr>
		
					
					
						<tr class="status-reject">
								<td><?php echo $img_sample; ?></td>
								<td><?php echo $img_sample2; ?></td>
								<td><?php echo $img_sample3; ?></td>
								<td><?php echo $img_sample4; ?></td>
								<td><?php echo $img_sample5; ?></td>
						</tr>
						<tr class="status-reject">
							<td colspan="5" style="padding: 20px;">
							<h5><?php echo $no; ?>. Detail Produk</h5>
							<?php echo $row1['desk']; ?>
							
							<hr style="border-top: 2px dashed #f44336; margin: 20px 0;">
							
							<h5><i class="bi bi-arrow-repeat"></i> Upload Ulang (Revisi) - MAKS 5 FOTO</h5>
							<form id="file-info<?php echo $row1['id']; ?>">
							
							<center>
							<div id="file-load<?php echo $row1['id']; ?>"><?php echo $img; ?></div>
							<div id="file-load2<?php echo $row1['id']; ?>"><?php echo $img2; ?></div>
							<div id="file-load3<?php echo $row1['id']; ?>"><?php echo $img3; ?></div>
							<div id="file-load4<?php echo $row1['id']; ?>"><?php echo $img4; ?></div>
							<div id="file-load5<?php echo $row1['id']; ?>"><?php echo $img5; ?></div>
							</center>
							<br>
							
							<textarea class="form-control" id="alasan<?php echo $row1['id']; ?>" placeholder="Masukan alasan revisi jika ada.. (tidak wajib)" rows="3"><?php echo $row1['alasan']; ?></textarea>
							<br>
							
							<!-- Upload File 1 -->
							<div class="file-upload-section">
								<label><i class="bi bi-file-image"></i> File 1</label>
								<input type="file" accept=".jpg, .png, .jpeg, .gif" name="fileupload<?php echo $row1['id']; ?>" id="fileupload<?php echo $row1['id']; ?>" class="form-control" />
								<button class="btn btn-warning btn-upload" type="button" onclick="uploadImage('<?php echo $row1['id']; ?>', 'file')">
									<i class="bi bi-arrow-repeat"></i> Upload File 1
								</button>
							</div>
							
							<!-- Upload File 2 -->
							<div class="file-upload-section">
								<label><i class="bi bi-file-image"></i> File 2</label>
								<input type="file" accept=".jpg, .png, .jpeg, .gif" name="fileupload2<?php echo $row1['id']; ?>" id="fileupload2<?php echo $row1['id']; ?>" class="form-control" />
								<button class="btn btn-warning btn-upload" type="button" onclick="uploadImage('<?php echo $row1['id']; ?>', 'file2')">
									<i class="bi bi-arrow-repeat"></i> Upload File 2
								</button>
							</div>
							
							<!-- Upload File 3 -->
							<div class="file-upload-section">
								<label><i class="bi bi-file-image"></i> File 3</label>
								<input type="file" accept=".jpg, .png, .jpeg, .gif" name="fileupload3<?php echo $row1['id']; ?>" id="fileupload3<?php echo $row1['id']; ?>" class="form-control" />
								<button class="btn btn-warning btn-upload" type="button" onclick="uploadImage('<?php echo $row1['id']; ?>', 'file3')">
									<i class="bi bi-arrow-repeat"></i> Upload File 3
								</button>
							</div>
							
							<!-- Upload File 4 -->
							<div class="file-upload-section">
								<label><i class="bi bi-file-image"></i> File 4</label>
								<input type="file" accept=".jpg, .png, .jpeg, .gif" name="fileupload4<?php echo $row1['id']; ?>" id="fileupload4<?php echo $row1['id']; ?>" class="form-control" />
								<button class="btn btn-warning btn-upload" type="button" onclick="uploadImage('<?php echo $row1['id']; ?>', 'file4')">
									<i class="bi bi-arrow-repeat"></i> Upload File 4
								</button>
							</div>
							
							<!-- Upload File 5 -->
							<div class="file-upload-section">
								<label><i class="bi bi-file-image"></i> File 5</label>
								<input type="file" accept=".jpg, .png, .jpeg, .gif" name="fileupload5<?php echo $row1['id']; ?>" id="fileupload5<?php echo $row1['id']; ?>" class="form-control" />
								<button class="btn btn-warning btn-upload" type="button" onclick="uploadImage('<?php echo $row1['id']; ?>', 'file5')">
									<i class="bi bi-arrow-repeat"></i> Upload File 5
								</button>
							</div>
							
							<input type="hidden" id="sku<?php echo $row1['id']; ?>" value="<?php echo $row1['sku']; ?>">
							<input type="hidden" id="toko<?php echo $row1['id']; ?>" value="<?php echo $toko; ?>">
							
							</form>

							<div class="progress">
								<div id="progress-bar<?php echo $row1['id']; ?>" class="progress-bar bg-warning"></div>
							</div>
							
							<p id="notif<?php echo $row1['id']; ?>"></p>

							</td>
							
						</tr>
						
					<?php $no++;} 
					
					} else {
						echo '<tr><td colspan="5" class="text-center" style="padding: 50px;">
							<i class="bi bi-check-circle-fill" style="font-size: 60px; color: #4caf50;"></i>
							<h3 style="color: #4caf50; margin-top: 20px; font-weight: bold;">Tidak ada data yang Reject!</h3>
							<p style="font-size: 16px; color: #666;">Semua data sudah dalam status Approved atau Waiting Approval.</p>
							<a href="index.php" class="btn btn-primary mt-3">
								<i class="bi bi-arrow-left"></i> Kembali ke Halaman Utama
							</a>
						</td></tr>';
					}
					?>
   
   
					</tbody>
				</table>
				
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>


<script type="text/javascript">
$(document).ready( function () {
    $('#example').DataTable({
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
    });
} );


function uploadImage(id, fileType){
	// Tentukan file input berdasarkan tipe
	var fileInputId = '';
	if(fileType === 'file'){
		fileInputId = 'fileupload'+id;
	} else if(fileType === 'file2'){
		fileInputId = 'fileupload2'+id;
	} else if(fileType === 'file3'){
		fileInputId = 'fileupload3'+id;
	} else if(fileType === 'file4'){
		fileInputId = 'fileupload4'+id;
	} else if(fileType === 'file5'){
		fileInputId = 'fileupload5'+id;
	}
	
	var vidFileLength = $("#"+fileInputId)[0].files.length;
	if(vidFileLength === 0){
		alert("Silahkan pilih file foto terlebih dahulu untuk " + fileType + "!");
		$("#"+fileInputId).focus();
	}else{
		var fileupload = $('#'+fileInputId).prop('files')[0];
		
		// Validasi ukuran file (max 5MB)
		if(fileupload.size > 5 * 1024 * 1024){
			alert("Ukuran file terlalu besar! Maksimal 5MB.");
			return;
		}
		
		var sku = $("#sku"+id).val();
		var toko = $("#toko"+id).val();
		var alasan = $("#alasan"+id).val();
		
		let formData = new FormData();
		formData.append('fileupload', fileupload);
		formData.append('id', id);
		formData.append('sku', sku);
		formData.append('toko', toko);
		formData.append('alasan', alasan);
		formData.append('fileType', fileType);
		
		$.ajax({
			xhr: function() {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener("progress", function(evt) {
					if (evt.lengthComputable) {
						var percentComplete = ((evt.loaded / evt.total) * 100);
						$("#progress-bar"+id).width(percentComplete+'%');
						$("#progress-bar"+id).html(parseInt(percentComplete)+'%');
					}
				}, false);
				return xhr;
			},
			type: 'POST',
			url: "https://mkt.idolmartidolaku.com/api/upload_sku_multi_new.php",
			data: formData,
			cache: false,
			processData: false,
			contentType: false,
			beforeSend: function(){
				$("#progress-bar"+id).addClass('progress-bar-animated');
				$("#notif"+id).html("<font style='color: blue;'>Sedang mengupload "+fileType+"...</font>");
			},
			success: function (msg) {
				$("#file-load"+id).load(" #file-load"+id);
				$("#file-load2"+id).load(" #file-load2"+id);
				$("#file-load3"+id).load(" #file-load3"+id);
				$("#file-load4"+id).load(" #file-load4"+id);
				$("#file-load5"+id).load(" #file-load5"+id);
				$("#"+fileInputId).val('');
				$("#progress-bar"+id).removeClass('progress-bar-animated');
				$("#notif"+id).html("<font style='color: green; font-weight: bold;'>✓ "+fileType+" berhasil diupload! Data akan masuk ke antrian approval.</font>");
				
				// Reload setelah 3 detik
				setTimeout(function(){
					location.reload();
				}, 3000);
			},
			error: function () {
				$("#notif"+id).html("<font style='color: red; font-weight: bold;'>✗ "+fileType+" Gagal diupload. Silahkan coba lagi.</font>");
				$("#progress-bar"+id).removeClass('progress-bar-animated');
			}
		});
	}
}

function syncMaster(){
	$.ajax({
		url: "api/action.php?modul=inventory&act=sync_inv",
		type: "GET",
		beforeSend: function(){
			$('#sync').prop('disabled', true);
			$('#notif1').html("<font style='color: red'>Sedang melakukan sync, sabar ya..</font>");
			$("#overlay").fadeIn(300);
		},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				$('#notif1').html("<font style='color: green'>"+dataResult.msg+"</font>");
				$("#example").load(" #example");
				$("#overlay").fadeOut(300);
			}
			else {
				$('#notif').html(dataResult.msg);
			}
		}
	});
}

function getType(){
	var type = "";
	$.ajax({
		url: "api/action.php?modul=inventory&act=get_type",
		type: "GET",
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				localStorage.setItem("type",dataResult.type);
				type = dataResult.type;
			}
		}
	});
	return type;
}
</script>

</div>
<?php include "components/fff.php"; ?>