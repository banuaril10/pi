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
				<h4>CAPTURE DISPLAY PLANOGRAM</h4>
			</div>
			<div class="card-body">
			<div class="tables">			
				<div class="table-responsive bs-example widget-shadow">	
				<p id="notif1" style="color: red; font-weight: bold"></p>		
				
				<?php 
				$toko = '';
				$cek_brand = "select * from ad_morg where postby = 'SYSTEM'";
				foreach ($connec->query($cek_brand) as $row) {
					
					$toko = $row['name'];
					$value = $row['value'];
					
				}
				
				
				?>
				
				
					<table class="table table-bordered table-striped" id="" style="width: 100%">
						
						<tbody>
						
						<?php 
						
						
						
						function get_data_image($sku, $tgl, $toko){
							$postData = array("sku" => $sku,"tgl" => $tgl,"toko" => $toko);				    
							// $postData = array('sku' => '456','tgl' => '2023-10-10','toko' => 'BOSOL-ONLINE SHOP');				    
							$fields_string = http_build_query($postData);
							$curl = curl_init();
						
							curl_setopt_array($curl, array(
							CURLOPT_URL => "https://mkt.idolmartidolaku.com/api/image_sku.php",
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => '',
							CURLOPT_MAXREDIRS => 10,
							CURLOPT_TIMEOUT => 0,
							CURLOPT_FOLLOWLOCATION => true,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => 'POST',
							CURLOPT_POSTFIELDS => $fields_string,
							));
							
							$response = curl_exec($curl);
							
							curl_close($curl);
							return $response;
					
					
						}
						
						$date_now = date("Y-m-d");
						// $date_now = '2023-10-10';
						
						$json_url = "https://mkt.idolmartidolaku.com/api/get_sku_plano.php?tgl=".$date_now."&toko=".$value;
						$options = stream_context_create(array('http'=>
							array(
							'timeout' => 10 //10 seconds
							)
						));
						
						$json = file_get_contents($json_url, false, $options);
						
						$arr = json_decode($json, true);
						$jum = count($arr);
					
					
						// print_r($arr);
					
						
						$s = array();
						if($jum > 0){
						$no = 1;
						foreach ($arr as $row1) {
							$name = "-";
							$cek_name = "select name from pos_mproduct where sku = '".$row1['sku']."'";
							foreach ($connec->query($cek_name) as $row_dis) {
								
								$name = $row_dis['name'];
							}
							
							$json1 = get_data_image($row1['sku'], $date_now, $toko);
							$arr1 = json_decode($json1, true);
							$jum1 = count($arr1);
							
							// print_r($json1);
							
							$img = '<img src="images/no-image.png" style="width: 200px"></img>';
							$img_sample = '<img src="images/no-image.png" style="width: 400px"></img>';
							if($jum1 > 0){
								foreach ($arr1 as $row_img) {
									$img = $row_img['image'];
									
								}
								
							}
							
							$img_sample = "";
							$img_sample2 = "";
							$img_sample3 = "";
							if($row1['file'] != ""){
								$img_sample = '<img src="'.$row1['base_url'].$row1['file'].'" style="width: 400px"></img>';
								
								
							}
							
							if($row1['file2'] != ""){
								$img_sample2 = '<img src="'.$row1['base_url'].$row1['file2'].'" style="width: 400px"></img>';
							}
							
							if($row1['file3'] != ""){
								$img_sample3 = '<img src="'.$row1['base_url'].$row1['file3'].'" style="width: 400px"></img>';
							}
							
							
							
						?>
						
				
							<tr>
								<td colspan="3" style="background-color: #629584; color: #fff; font-size: 35px"><center>Contoh Foto <b><?php echo $row1['nama']; ?></center></b></td>
							</tr>
			
						
						
							<tr>
									<td><?php echo $img_sample; ?></td>
									<td><?php echo $img_sample2; ?></td>
									<td><?php echo $img_sample3; ?></td>
							</tr>
							<tr>
								<td colspan="3">
								<?php echo $no; ?>. <?php echo $row1['desk']; ?>
								
								<form id="file-info<?php echo $row1['id']; ?>">
								
								<center>
								
								
								<div id="file-load<?php echo $row1['id']; ?>"><?php echo $img; ?></div>
								
								</center>
								<br>
								<br>
								
								<textarea class="form-control" id="alasan<?php echo $row1['id']; ?>" placeholder="Masukan alasan jika ada.. (tidak wajib)"><?php echo $row1['alasan']; ?></textarea>
								<br>
								<input type="file" accept=".jpg, .png, .jpeg, .gif" name="fileupload<?php echo $row1['id']; ?>" id="fileupload<?php echo $row1['id']; ?>" class="form-control" />
								<br>
								<input type="hidden" id="sku<?php echo $row1['id']; ?>" value="<?php echo $row1['sku']; ?>">
								<input type="hidden" id="toko<?php echo $row1['id']; ?>" value="<?php echo $toko; ?>">
								<button class="btn btn-primary" type="button" onclick="uploadImage('<?php echo $row1['id']; ?>');" >Upload</button>
								
								</form>

								<div class="progress">
									<div id="progress-bar<?php echo $row1['id']; ?>" class="progress-bar"></div>
								</div>
								
								<p id="notif<?php echo $row1['id']; ?>"></p>

								</td>
								
							</tr>
							
							
							
							
						<?php $no++;} 
						
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


function uploadImage(id){
	
			var vidFileLength = $("#fileupload"+id)[0].files.length;
			if(vidFileLength === 0){
				// var fileupload = $('#fileuploads'+id).prop('files')[0];
				alert("File belum dipilih");
			}else{
				// alert("ada");
				var fileupload = $('#fileupload'+id).prop('files')[0];
				
				var sku = $("#sku"+id).val();
				var toko = $("#toko"+id).val();
				var alasan = $("#alasan"+id).val();
				
				
				let formData = new FormData();
				formData.append('fileupload', fileupload);
				formData.append('id', id);
				formData.append('sku', sku);
				formData.append('toko', toko);
				formData.append('alasan', alasan);
				
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
					url: "https://mkt.idolmartidolaku.com/api/upload_sku.php",
					data: formData,
					cache: false,
					processData: false,
					contentType: false,
					success: function (msg) {
						$("#file-load"+id).load(" #file-load"+id);
						$("#fileupload"+id).val('');
						
					},
					error: function () {
						$("#notif"+id).html("<font style='color: red'>File Gagal diupload</font>");
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