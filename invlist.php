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
				
				
				<?php 
				$sql_list = "select m_pi_key, name ,insertdate, rack_name from m_pi where status = '1' and m_pi_key = '".$_GET['m_pi']."' order by insertdate desc"; 
				foreach ($connec->query($sql_list) as $tot) { ?>
						<h4>INVENTORY COUNTING <?php echo $tot['rack_name']; ?></h4>
						
					
					<!--DOCUMENT NO : <b><?php echo $tot['name']; ?> -->
				<?php } ?>
				
			<button type="button" class="btn btn-warning" id="generate" onclick="cetakExcel()">Generate Excel</button>
			<a id="test" onclick="showGenerate();" class="btn btn-warning" style="display: none" href="">Download</a>
			</div>
			<div class="card-body">
			<div class="tables">
						
				<div class="table-responsive bs-example widget-shadow">				
				
					<div class="form-group">
					<p id="notif" style="color: red; font-weight: bold"></p>
					</div>
					<div class="form-inline"> 

					<div class="form-group"> 
					
					<input style="margin-bottom: 10px" type="text" id="sku" class="form-control" id="exampleInputName2" placeholder="SKU / Barcode International" autofocus>
					
					<input type="text" id="search" class="form-control" id="exampleInputName2" placeholder="Search">
					<input type="hidden" id="search1">
					</div> 

					</div>
					  
			
					<table class="table table-bordered" id="example1">
						<thead>
							<tr>
								<th>No</th>
								<th>SKU (Barcode Int.)</th>
							
								<th>Counter</th>

							</tr>
						</thead>
						<tbody>
			
		<?php $list_line = "select distinct m_piline.m_piline_key, m_piline.barcode, m_piline.sku ,m_piline.qtyerp, m_piline.qtycount, pos_mproduct.name, m_pi.status from m_pi inner join m_piline on m_pi.m_pi_key = m_piline.m_pi_key left join pos_mproduct on m_piline.sku = pos_mproduct.sku
		where m_pi.m_pi_key = '".$_GET['m_pi']."' and m_pi.status = '1' order by qtyerp desc";
		$no = 1;
		foreach ($connec->query($list_line) as $row1) {	
		$barcode = "";
		if(!empty($row1['barcode']) || $row1['barcode'] != ""){
			
			$barcode = "(".$row1['barcode'].")";
		}
		
		
		?>
			
							<tr>
								<td><?php echo $no; ?></td>
								<td><button type="button" style="display: inline-block; background: red; color: white" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row1['m_piline_key']; ?>"><i class="fa fa-times"></i></button><br><font style="font-weight: bold"><?php echo $row1['sku']; ?> <?php echo $barcode; ?></font><br> <font style="color: green;font-weight: bold"><?php echo $row1['name']; ?></font></td>
	
								<td>
								
								<div class="form-inline"> 
								<input type="number" onchange="changeQty('<?php echo $row1['sku']; ?>', '<?php echo $row1['name']; ?>', '<?php echo $_GET['m_pi']; ?>');" id="qtycount<?php echo $row1['sku']; ?>" class="form-control" value="<?php echo $row1['qtycount']; ?>"> <br>
								
								
								
								
									<button type="button" style="display: inline-block; background: blue; color: white" onclick="changeQtyPlus('<?php echo $row1['sku']; ?>', '<?php echo $row1['name']; ?>', '<?php echo $_GET['m_pi']; ?>');" class=""><i class="fa fa-plus"></i></button>
									&nbsp
									<button type="button" style="display: inline-block; background: #ba3737; color: white" onclick="changeQtyMinus('<?php echo $row1['sku']; ?>', '<?php echo $row1['name']; ?>', '<?php echo $_GET['m_pi']; ?>');" class=""><i class="fa fa-minus"></i></button>
									
									
										
										
								</div>		
										
								
								</td>

							</tr>
							
							<div class="modal fade" id="exampleModal<?php echo $row1['m_piline_key']; ?>" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin delete line?</h5>
								
									<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									SKU : <b><?php echo $row1['sku']; ?></b><br>
									Nama : <b><?php echo $row1['name']; ?></b>
									
									
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
									<button type="button" class="btn btn-danger" onclick="deleteLine('<?php echo $row1['m_piline_key']; ?>');" class="">YAKIN</button>
								</div>
								</div>
							</div>
							</div>
			
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
<input type="hidden" id="kat" value="<?php echo $_GET['kat']; ?>">
<input type="hidden" id="m_pi" value="<?php echo $_GET['m_pi']; ?>">




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

// function syncErp(mpi){
	
	
	// $.ajax({
		// url: "api/action.php?modul=inventory&act=sync_erp",
		// type: "POST",
		// data : {mpi: mpi},
		// beforeSend: function(){
			// $('#notif'+mpi).html("Sistem sedang melakukan sync, jangan refresh halaman sebelum selesai..");
		// },
		// success: function(dataResult){
			// var dataResult = JSON.parse(dataResult);
			// console.log(dataResult);
			// if(dataResult.result=='2'){
				// $('#notif'+mpi).html(dataResult.msg);
				// $("#example").load(" #example");
			// }else if(dataResult.result=='1'){
				// $('#notif'+mpi).html("<font style='color: green'>"+dataResult.msg+"</font>");
				// $("#example1"+mpi).load(" #example1"+mpi);
			// }
			// else {
				// $('#notif'+mpi).html("Gagal sync coba lagi nanti!");
			// }
			
		// }
	// });
	
	
// }


function changeQtyPlus(sku, nama, mpi){
	var quan = document.getElementById("qtycount"+sku).value;
	var plus = parseInt(quan) + 1;
	document.getElementById("qtycount"+sku).value=plus;
	changeQty(sku, nama, mpi);
}

function changeQtyMinus(sku, nama, mpi){
	var quan = document.getElementById("qtycount"+sku).value;
	var plus = parseInt(quan) - 1;
	
	if(plus < 0){
		
		$('#notif').html("TIDAK BOLEH KURANG DARI 0");
	}else{
		
		document.getElementById("qtycount"+sku).value=plus;
		changeQty(sku, nama, mpi);
	}
	
}

function changeQty(sku, nama, mpi){
	var quan = document.getElementById("qtycount"+sku).value;
	$.ajax({
		url: "api/action.php?modul=inventory&act=updatecounter&mpi="+mpi,
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
				// $("#example1").load(" #example1");
			}
			else {
				$('#notif').html("Gagal sync coba lagi nanti!");
			}
			
		}
	});
}

function deleteLine(m_piline_key){
	$.ajax({
		url: "api/action.php?modul=inventory&act=deleteline",
		type: "POST",
		data : {m_piline_key: m_piline_key},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);
			console.log(dataResult);
			if(dataResult.result=='0'){
				$('#notif').html(dataResult.msg);
				// $("#example").load(" #example");
			}else if(dataResult.result=='1'){
				$('#notif').html("<font style='color: green'>"+dataResult.msg+"</font>");
				$("#example1").load(" #example1");
				$(".modal").modal('hide');
			}
			else {
				$('#notif').html("Gagal sync coba lagi nanti!");
			}
			
		}
	});
	
	
}


var input = document.getElementById("sku");
var kat = document.getElementById("kat");
var m_pi = document.getElementById("m_pi");

input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
	
	var sku = input.value;
	var kategori = kat.value;
	var m_pi_key = m_pi.value;
	
	if(kategori == 3){
		
		var url = "api/action.php?modul=inventory&act=counteritems&mpi="+m_pi_key;
	}else{
		
		var url = "api/action.php?modul=inventory&act=counter&mpi="+m_pi_key;
	}
	
	if(sku != ""){
		
		$.ajax({
		url: url,
		type: "POST",
		data : {sku: sku, kat: kategori},
		beforeSend: function(){
			$('#notif').html("Proses mencari items..");
		},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);
			console.log(dataResult);
			if(dataResult.result=='2'){
				input.value = '';
				$('#notif').html(dataResult.msg+' <button onclick="lanjutInput('+sku+', \''+ m_pi_key + '\', '+kategori+');">Yes</button>');
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
				
				
				
			}else if(dataResult.result=='0'){
				input.value = '';
				$('#notif').html("<font style='color: red'>"+dataResult.msg+"</font>");
			}
			else {
				input.value = '';
				$('#notif').html("Gagal sync coba lagi nanti!");
			}
			
		}
	});
		
	}else{
		
		$('#notif').html("tidak boleh kosong!");
		
	}
	
	
	
	
  }
});




function lanjutInput(sku, m_pi_key, kat){
	// alert(sku+' '+m_pi_key+' '+kat);
	
	if(sku != ""){
		var url = "api/action.php?modul=inventory&act=counteritems&mpi="+m_pi_key;
		$.ajax({
		url: url,
		type: "POST",
		data : {sku: sku, kat: kat},
		success: function(dataResult){
			console.log(dataResult);
			var dataResult = JSON.parse(dataResult);
			
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
		
		
	}else{
		
		$('#notif').html("tidak boleh kosong!");
		
	}
	
	
	
}


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




function cetakExcel(){
		
		// alert(mpi+'<br>'+rn+'<br>'+dn);		
		var number = 0;	
		//alert(userid);	
		// alert(html);
		$.ajax({
			url: "api/action.php?modul=inventory&act=cetak_excel_stock&m_pi=<?php echo $_GET['m_pi']; ?>",
			type: "GET",
			success: function(dataResult){

				// console.log(dataResult);
				
				var dataResult = JSON.parse(dataResult);
				
				
				
					testJson = dataResult;


					testTypes = {
						"sku": "String",
						"barcode_international": "String",
						"namaitem": "String",
						"stock": "String",
					};
					
					emitXmlHeader = function () {
						var headerRow =  '<ss:Row>\n';
						for (var colName in testTypes) {
							headerRow += '  <ss:Cell>\n';
							headerRow += '    <ss:Data ss:Type="String">';
							headerRow += colName + '</ss:Data>\n';
							headerRow += '  </ss:Cell>\n';        
						}
						headerRow += '</ss:Row>\n';    
						return '<?xml version="1.0"?>\n' +
							'<ss:Workbook xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">\n' +
							'<ss:Worksheet ss:Name="Sheet1">\n' +
							'<ss:Table>\n\n' + headerRow;
					};
					
					emitXmlFooter = function() {
						return '\n</ss:Table>\n' +
							'</ss:Worksheet>\n' +
							'</ss:Workbook>\n';
					};
					
					jsonToSsXml = function (jsonObject) {
						var row;
						var col;
						var xml;
						var data = typeof jsonObject != "object" ? JSON.parse(jsonObject) : jsonObject;
						
						xml = emitXmlHeader();
					
						for (row = 0; row < data.length; row++) {
							xml += '<ss:Row>\n';
						
							for (col in data[row]) {
								xml += '  <ss:Cell>\n';
								xml += '    <ss:Data ss:Type="' + testTypes[col]  + '">';
								xml += data[row][col] + '</ss:Data>\n';
								xml += '  </ss:Cell>\n';
							}
					
							xml += '</ss:Row>\n';
						}
						
						xml += emitXmlFooter();
						return xml;  
					};
					
					console.log(jsonToSsXml(testJson));
					
					download = function (content, filename, contentType) {
						if (!contentType) contentType = 'application/octet-stream';
						var a = document.getElementById('test');
						var blob = new Blob([content], {
							'type': contentType
						});
						a.href = window.URL.createObjectURL(blob);
						a.download = filename;
						document.getElementById("test").style.display = '';
						document.getElementById("generate").style.display = 'none';
					};
					
					download(jsonToSsXml(testJson), 'Laporan Stock Toko.xls', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				
					
					
				
			}
		});

				
}







</script>
</div>
<?php include "components/fff.php"; ?>