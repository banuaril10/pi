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
			<div class="card-header">
				<h4>INVENTORY DETAIL</h4>
				
				<table>
				<tr>
					<td style="background: #47b5ff3d; width: 50px"></td>
					<td> : </td>
					<td>Berhasil release</td>
				</tr>
				
				<tr>
					<td style="background: #fd5d5d61; width: 30px"></td>
					<td> : </td>
					<td>Gagal release</td>
				</tr>
				
				</table>
				
			</div>
			<div class="card-body">
			<div class="tables">
						
				<div class="table-responsive bs-example widget-shadow">		
				
				<?php 
				$cek_sync = "select count(sku) as jum from m_piline where issync = '0' and m_pi_key = '".$_GET['m_pi']."'";
		
				foreach ($connec->query($cek_sync) as $jum) {
					$jumnosync = $jum['jum'];
				} 
				if($jumnosync > 0){ ?>
					<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $_GET['m_pi']; ?>">Lanjutkan..</button>
					
				<?php }
				?>
				

					<div class="form-group">
					<p id="notif" style="color: red; font-weight: bold"></p>
					</div>
					<div class="form-inline"> 
					
					
					<div class="form-group"> 
					
					
					<input type="text" id="search" class="form-control" id="exampleInputName2" placeholder="Search" autofocus>
					<input type="hidden" id="search1">
					</div> 

					</div>
					  
			
					<table class="table table-bordered" id="example1">
						<thead>
							<tr>
								<th>No</th>
								<th>SKU</th>
								
							
								<th>Counter</th>
								<th>Qty ERP</th>
								<th>Qty Sales</th>
								<th>Qty Variant</th>
								<th>Verified Count</th>

							</tr>
						</thead>
						<tbody>
			
		<?php $list_line = "select distinct m_piline.issync, m_piline.sku ,m_piline.qtyerp, m_piline.qtysales, m_piline.qtycount, pos_mproduct.name, m_pi.status, m_piline.verifiedcount from m_pi inner join m_piline on m_pi.m_pi_key = m_piline.m_pi_key left join pos_mproduct on m_piline.sku = pos_mproduct.sku 
		where m_pi.m_pi_key = '".$_GET['m_pi']."' and m_pi.status = '3' order by issync asc";
		$no = 1;
		
		
		
		foreach ($connec->query($list_line) as $row1) {	
		$variant = ($row1['qtycount'] + $row1['qtysales']) - $row1['qtyerp'];
		
		if($row1['verifiedcount'] == ''){
			
			$vc = 0;
		}else{
			
			$vc = $row1['verifiedcount'];
		}
		
		
		if($row1['issync'] == 1){
			
			$warna = '#47b5ff3d';
			
		}else if($row1['issync'] == 0){
			
			$warna = '#fd5d5d61';
		}
		
		?>
			
							<tr>
								<td><?php echo $no; ?></td>
								<td style="background: <?php echo $warna; ?>"><?php echo $row1['sku']; ?> <br> <?php echo $row1['name']; ?></td>
	
								<td><?php echo $row1['qtycount']; ?></td>
								<td><?php echo $row1['qtyerp']; ?></td>
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

<div class="modal fade" id="exampleModal<?php echo $_GET['m_pi']; ?>" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin melakukan release items yg gantung?</h5>
								
									<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
									<button onclick="releasePI('<?php echo $_GET['m_pi']; ?>');" class="btn btn-success">YAKIN</button>
								</div>
								</div>
							</div>
							</div>


<script type="text/javascript">
$(document).ready(function () {
	// $('#example1').DataTable();
	$('select').selectize({
		sortField: 'text'
	});	
});


document.getElementById("search").addEventListener("keyup", function() {
var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  table = document.getElementById("example1");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    td1 = tr[i].getElementsByTagName("td")[2];
    if (td) {
      txtValue = td.textContent || td.innerText;
      txtValue1 = td1.textContent || td1.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(txtValue1.toUpperCase().indexOf(filter) > -1){
		tr[i].style.display = "";  
	  } else {
        tr[i].style.display = "none";
      }
    }       
  }
	
	
	
});


function releasePI(m_pi){
	var formData = new FormData();
	formData.append('m_pi', m_pi);
	$.ajax({
		url: "api/action.php?modul=inventory&act=releasegantung",
		type: "POST",
		data : formData,
		processData: false,
		contentType: false,
		beforeSend: function(){
			$('#notif').html("Proses release, jangan close halaman ini sampai selesai..");
			$(".modal").modal('hide');
		},
		success: function(dataResult){
			console.log(dataResult);
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				$('#notif').html("<font style='color: green'>"+dataResult.msg+"</font>");
				$("#example1").load(" #example1");
				
			}
			// else {
				// $('#notif').html(dataResult.msg);
			// }
			
		}
	});
	
	
}

function changeQty(sku, nama){
	var quan = document.getElementById("qtycount"+sku).value;
	
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


var input = document.getElementById("sku");

input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
	
	var sku = input.value;
	
	$.ajax({
		url: "api/action.php?modul=inventory&act=counter",
		type: "POST",
		data : {sku: sku},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);
			console.log(dataResult);
			if(dataResult.result=='0'){
				input.value = '';
				$('#notif').html(dataResult.msg);
				$('#example1').load(' #example1', function() {
					$('#search1').val(sku);
				
					filterTable();
				});
			}else if(dataResult.result=='1'){
				input.value = '';
				$('#notif').html("<font style='color: green'>"+dataResult.msg+"</font>");
				// $("#example1").load(" #example1");
				
				$('#example1').load(' #example1', function() {
					$('#search1').val(sku);
				
					filterTable();
				});
				
				
				
			}
			else {
				
				$('#notif').html("Gagal sync coba lagi nanti!");
			}
			
		}
	});
	
  }
});







function filterTable(){
var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search1");
  filter = input.value.toUpperCase();
  table = document.getElementById("example1");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    td1 = tr[i].getElementsByTagName("td")[2];
    if (td) {
      txtValue = td.textContent || td.innerText;
      txtValue1 = td1.textContent || td1.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(txtValue1.toUpperCase().indexOf(filter) > -1){
		tr[i].style.display = "";  
	  } else {
        tr[i].style.display = "none";
      }
    }       
  }
	
}

</script>
</div>
<?php include "components/fff.php"; ?>