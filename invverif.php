<?php include "config/koneksi.php"; ?>
<?php include "components/main.php"; ?>
<?php include "components/sidebar.php"; ?>
<div id="app">
<div id="main">
<header class="mb-3">
	<a href="#" class="burger-btn d-block d-xl-none">
		<i class="bi bi-justify fs-3"></i>
	</a>
</header>
<?php include "components/hhh.php"; ?>

<!------ CONTENT AREA ------->
<div class="row">
	<div class="col-12">
		<div class="card">
			<div style="font-size: 15px" class="card-header">
				<font><b>INVENTORY VERIFICATION</b></font><br>
				<?php 
				function rupiah($angka){
	
							$hasil_rupiah = "Rp " . number_format($angka,0,',','.');
							return $hasil_rupiah;
 
						}
				$sql_list = "select m_pi_key, name ,insertdate, rack_name from m_pi where status = '2' and m_pi_key = '".$_GET['m_pi']."' order by insertdate desc"; 
				foreach ($connec->query($sql_list) as $tot) { ?>
						<font>RACK : <b><?php echo $tot['rack_name']; ?></b></font><br>
						<font>DOCUMENT NO : <b><?php echo $tot['name']; ?></b></font><br>
					
					
				<?php } ?>
				
				<div id="info">
				<?php $sql_amount = "select SUM(CASE WHEN issync=1 THEN 1 ELSE 0 END) jumsync,  sum(qtysalesout * price) hargagantung,  sum(qtyerp * price) hargaerp, sum(qtycount * price) hargafisik, count(sku) jumline
						from m_piline where m_pi_key = '".$_GET['m_pi']."'";
						foreach ($connec->query($sql_amount) as $tot) {
							
							$qtyerp = $tot['hargaerp'] - $tot['hargagantung'];
							$qtycount = $tot['hargafisik'];

							$jumline = $tot['jumline'];
							$jumsync = $tot['jumsync'];
							$selisih = $qtycount - $qtyerp;
							echo "<font>SELISIH : <b>".rupiah($selisih)."</b></font>";
						}
						
				?>
				<table>
				<tr>
					<td style="width: 40px; background-color: #ffa597"></td>
					<td> : </td>
					<td>Sudah verifikasi</td>
				
				</tr>
				</table>
				
				</div>
				
				
			</div>
			<div class="card-body">
			<div class="tables">
						
				<div class="table-responsive bs-example widget-shadow">				

				

					<div class="form-group">
					<p id="notif" style="color: red; font-weight: bold"></p>
					</div>
					<div class="form-inline"> 
					
					
					<div class="form-group"> 
					
					
					<input type="text" id="search" class="form-control" id="exampleInputName2" placeholder="Search" autofocus>
					<input type="hidden" id="search1">
					</div> 

					</div>
					  
			
					<table class="table table-bordered " id="example1" style="font-size: 13px">
						<thead>
							
								
						</thead>
						<tbody>
			
		<?php $list_line = "select m_piline.sku ,m_piline.qtyerp, m_piline.qtysales, m_piline.qtycount, m_piline.qtysalesout, pos_mproduct.name, m_pi.status, m_piline.verifiedcount from m_pi inner join m_piline on m_pi.m_pi_key = m_piline.m_pi_key left join pos_mproduct on m_piline.sku = pos_mproduct.sku 
		where m_pi.m_pi_key = '".$_GET['m_pi']."' and m_pi.status = '2' and m_piline.qtycount != (m_piline.qtyerp - m_piline.qtysalesout) order by qtyerp desc";
		$no = 1;
		foreach ($connec->query($list_line) as $row1) {	
		$variant = ($row1['qtycount'] + $row1['qtysales']) - ($row1['qtyerp'] - $row1['qtysalesout']);
		$qtyerpreal = $row1['qtyerp'] - $row1['qtysalesout'];
		if($row1['verifiedcount'] == ''){
			
			$vc = 0;
		}else{
			
			$vc = $row1['verifiedcount'];
		}
		
		
		if($vc > 0){
			
			$color = 'style="background-color: #ffa597"';
			
		}else{
			$color = '';
			
		}
		
		?>
		
		
							
				
							<tr class="header" style="background: #e1e5fa">
				
								<td colspan="5"><?php echo $row1['sku']; ?> (<?php echo $row1['name']; ?>)</td>
							</tr>
	
							<tr class="header1" style="background: #e1e5fa">
								<td>Counter</td>
								<td>ERP</td>
								<td>Sales</td>
								<td>Variant</td>
								<td>Verified</td>
								
								

							</tr>
							<tr class="header2" <?php echo $color; ?>>
	
								<td>
								
								<div class="form-inline"> 
								<input type="number" id="qtycount<?php echo $row1['sku']; ?>" class="form-control" value="<?php echo $row1['qtycount']; ?>"> 
								<button type="button" style="background: green; color: white" onclick="changeQty('<?php echo $row1['sku']; ?>', '<?php echo $row1['name']; ?>');" class="">Verifikasi</button>
										
								</div>		
										
								
								</td>
								<td><?php echo $qtyerpreal; ?></td>
								<td><?php echo $row1['qtysales']; ?></td>
								<td><?php echo $variant; ?></td>
								<td><?php echo $vc; ?></td>
							</tr>
					
		<?php $no++;} ?>
			
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
$(window).bind('beforeunload', function(){
  myFunction();
  return 'Apakah kamu yakin?';
});

function myFunction(){
     // Write your business logic here
     alert('Bye');
}

$(document).ready(function () {
	// $('#example1').DataTable();
	$('select').selectize({
		sortField: 'text'
	});	
});

window.onbeforeunload = function () {
    return 'Are you sure? Your work will be lost. ';
};

document.getElementById("search").addEventListener("change", function() {
var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  table = document.getElementById("example1");
  tr = table.getElementsByClassName("header");
  trr = table.getElementsByClassName("header1");
  trrr = table.getElementsByClassName("header2");
   // tr.style.display = "none";
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
  
    if (td) {
      txtValue = td.textContent || td.innerText;
     
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        trr[i].style.display = "";
        trrr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
        trr[i].style.display = "none";
        trrr[i].style.display = "none";
      }
    }       
  }
	
	
	
});


document.getElementById("search").addEventListener("keyup", function() {
var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  table = document.getElementById("example1");
  tr = table.getElementsByClassName("header");
  trr = table.getElementsByClassName("header1");
  trrr = table.getElementsByClassName("header2");
   // tr.style.display = "none";
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
  
    if (td) {
      txtValue = td.textContent || td.innerText;
     
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        trr[i].style.display = "";
        trrr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
        trr[i].style.display = "none";
        trrr[i].style.display = "none";
      }
    }       
  }
	
	
	
});

function syncErp(mpi){
	
	
	$.ajax({
		url: "api/action.php?modul=inventory&act=sync_erp",
		type: "POST",
		data : {mpi: mpi},
		beforeSend: function(){
			$('#notif'+mpi).html("Sistem sedang melakukan sync, jangan refresh halaman sebelum selesai..");
		},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);
			console.log(dataResult);
			if(dataResult.result=='2'){
				$('#notif'+mpi).html(dataResult.msg);
				$("#example").load(" #example");
			}else if(dataResult.result=='1'){
				$('#notif'+mpi).html("<font style='color: green'>"+dataResult.msg+"</font>");
				$("#example1"+mpi).load(" #example1"+mpi);
			}
			else {
				$('#notif'+mpi).html("Gagal sync coba lagi nanti!");
			}
			
		}
	});
	
	
}



function changeQty(sku, nama){
	var quan = document.getElementById("qtycount"+sku).value;
	var search = document.getElementById("search");
	
	if(parseInt(quan) >= 0){
		$.ajax({
		url: "api/action.php?modul=inventory&act=updateverifikasi",
		type: "POST",
		data : {sku: sku, quan: quan, nama: nama},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);
			console.log(dataResult);
			if(dataResult.result=='0'){
				$('#notif').html(dataResult.msg);
				// $("#example").load(" #example");
			}else if(dataResult.result=='1'){
				
				
				
				$('#notif').html("<font style='color: green'>"+dataResult.msg+"</font>");
				$("#example1").load(" #example1");
				$("#info").load(location.href + " #info");
				search.value = '';
				$('#search1').val(sku);
				filterTable(sku);
			}
			else {
				$('#notif').html("Gagal sync coba lagi nanti!");
			}
			
		}
	});
		
	}else{
		$('#notif').html("Quantity tidak boleh kurang dari 0");
		
		
	}
	
	
}








function filterTable(sku){
	var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search1");
  filter = input.value.toUpperCase();
  table = document.getElementById("example1");
  tr = table.getElementsByClassName("header");
  trr = table.getElementsByClassName("header1");
  trrr = table.getElementsByClassName("header2");
   // tr.style.display = "none";
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
  
    if (td) {
      txtValue = td.textContent || td.innerText;
     
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        trr[i].style.display = "";
        trrr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
        trr[i].style.display = "none";
        trrr[i].style.display = "none";
      }
    }       
  }
	
}

</script>







</div>
<?php include "components/fff.php"; ?>