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

// Ambil data dari API untuk diambil kategorinya
$date_now = date("Y-m-d");
$json_url = "https://mkt.idolmartidolaku.com/api/get_sku_plano_multi.php?tgl=".$date_now."&toko=".$value;
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
// Kumpulkan kategori unik dari field desk API
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
    <title>Store Apps</title>
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
			<div class="card-header">
				<h4>CAPTURE DISPLAY PLANOGRAM<br>MAKS 3 FOTO PER DISPLAY UNTUK YG LEBIH DARI 1 RACK, JIKA HANYA 1 RACK KOSONGKAN SAJA YG LAINNYA</h4>
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
								<button type="submit" class="btn btn-primary w-100">
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
					
					$arr = $arr_all; // reuse from above
					
					$jum = count($arr);
				
					$s = array();
					if($jum > 0){
					$no = 1;
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

						$img_sample = "";
						$img_sample2 = "";
						$img_sample3 = "";
						$img_sample4 = "";
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
						
					?>
					
			
						<tr>
							<td colspan="4" style="background-color: #629584; color: #fff; font-size: 35px"><center>Contoh Foto <b><?php echo $row1['nama']; ?></center></b></td>
						</tr>
		
					
					
						<tr>
								<td><?php echo $img_sample; ?></td>
								<td><?php echo $img_sample2; ?></td>
								<td><?php echo $img_sample3; ?></td>
								<td><?php echo $img_sample4; ?></td>
						</tr>
						<tr>
							<td colspan="4">
							<?php echo $no; ?>. <?php echo $row1['desk']; ?>
							
							<form id="file-info<?php echo $row1['id']; ?>">
							
							<center>
							
							
							<div id="file-load<?php echo $row1['id']; ?>"><?php echo $img; ?></div>
							<div id="file-load2<?php echo $row1['id']; ?>"><?php echo $img2; ?></div>
							<div id="file-load3<?php echo $row1['id']; ?>"><?php echo $img3; ?></div>
							
							</center>
							<br>
							<br>
							
							<textarea class="form-control" id="alasan<?php echo $row1['id']; ?>" placeholder="Masukan alasan jika ada.. (tidak wajib)"><?php echo $row1['alasan']; ?></textarea>
							<br>
							<input type="file" accept=".jpg, .png, .jpeg, .gif" name="fileupload<?php echo $row1['id']; ?>" id="fileupload<?php echo $row1['id']; ?>" class="form-control" />
							<br>
							<input type="hidden" id="sku<?php echo $row1['id']; ?>" value="<?php echo $row1['sku']; ?>">
							<input type="hidden" id="toko<?php echo $row1['id']; ?>" value="<?php echo $toko; ?>">
							<button class="btn btn-primary" type="button" onclick="uploadImage('<?php echo $row1['id']; ?>', 'file')" >Upload File</button>
							
							<br><br>
							
							<!-- Upload untuk file2 -->
							<input type="file" accept=".jpg, .png, .jpeg, .gif" name="fileupload2<?php echo $row1['id']; ?>" id="fileupload2<?php echo $row1['id']; ?>" class="form-control" />
							<br>
							<button class="btn btn-success" type="button" onclick="uploadImage('<?php echo $row1['id']; ?>', 'file2')" >Upload File 2</button>
							
							<br><br>
							
							<!-- Upload untuk file3 -->
							<input type="file" accept=".jpg, .png, .jpeg, .gif" name="fileupload3<?php echo $row1['id']; ?>" id="fileupload3<?php echo $row1['id']; ?>" class="form-control" />
							<br>
							<button class="btn btn-warning" type="button" onclick="uploadImage('<?php echo $row1['id']; ?>', 'file3')" >Upload File 3</button>
							
							</form>

							<div class="progress">
								<div id="progress-bar<?php echo $row1['id']; ?>" class="progress-bar"></div>
							</div>
							
							<p id="notif<?php echo $row1['id']; ?>"></p>

							</td>
							
						</tr>
						
						
						
						
					<?php $no++;} 
					
					} else {
						echo '<tr><td colspan="4" class="text-center">Tidak ada data planogram untuk hari ini.</td></tr>';
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
	}
	
	var vidFileLength = $("#"+fileInputId)[0].files.length;
	if(vidFileLength === 0){
		alert("File belum dipilih");
	}else{
		var fileupload = $('#'+fileInputId).prop('files')[0];
		
		var sku = $("#sku"+id).val();
		var toko = $("#toko"+id).val();
		var alasan = $("#alasan"+id).val();
		
		let formData = new FormData();
		formData.append('fileupload', fileupload);
		formData.append('id', id);
		formData.append('sku', sku);
		formData.append('toko', toko);
		formData.append('alasan', alasan);
		formData.append('fileType', fileType); // Kirim tipe file ke server
		
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
			url: "https://mkt.idolmartidolaku.com/api/upload_sku_multi.php",
			data: formData,
			cache: false,
			processData: false,
			contentType: false,
			success: function (msg) {
				$("#file-load"+id).load(" #file-load"+id);
				$("#file-load2"+id).load(" #file-load2"+id);
				$("#file-load3"+id).load(" #file-load3"+id);
				$("#"+fileInputId).val('');
				$("#notif"+id).html("<font style='color: green'>File "+fileType+" berhasil diupload</font>");
				// Refresh halaman setelah 2 detik
				setTimeout(function(){
					location.reload();
				}, 2000);
			},
			error: function () {
				$("#notif"+id).html("<font style='color: red'>File "+fileType+" Gagal diupload</font>");
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
			// console.log(dataResult);
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