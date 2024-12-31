<?php include "config/koneksi.php"; ?>
<?php include "components/main.php"; ?>
<?php include "components/sidebar.php"; ?>

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

<!------ CONTENT AREA ------->
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				
			
			
			<h4>INVENTORY IMPORTER</h4>
			
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalProses">Proses Data</button>
			
			
				<form method="post" enctype="multipart/form-data" action="import.php">

                  <div class="form-group">
                    <label><b>Upload File</b></label>
                    <input type="file" name="import" id="import" class="form-control">
                  </div> 

                  <div id="process" style="display:none;">
                    <div class="progress mt-3">
                      <div class="progress-bar progress-bar-striped" role="progressbar" style=""  aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                  <button class="btn btn-primary" id="btn-form" type="submit">Import</button>
				  <!--<button type="button" class="btn btn-success" type="button" id="checkallinv">Check All</button>
				<button type="button" class="btn btn-danger" id="prosesall" onclick="prosesAll();" >Process Selected</button>-->
				
				<br>
				<select id="filename">
					<?php $fn = "select filename from inv_temp where date(tanggal) = date(now()) and status = '0' group by filename,status ";
						$no = 1;
						foreach ($connec->query($fn) as $rows) { ?>
							
							<option value="<?php echo $rows['filename']; ?>"><?php echo $rows['filename']; ?></option>
							
						<?php } ?>
						
					 </select>
				
					 <button class="btn btn-danger" onclick="prosesInv();" id="button-proses" type="button">Proses File</button>
				
				
                </form>
			
						
			<div class="modal fade" id="exampleModalProses" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin proses data?</h5>
								
									<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
									<button type="button" class="btn btn-danger" onclick="prosesData();" class="">YAKIN</button>
								</div>
								</div>
							</div>
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
					
					<input style="margin-bottom: 10px" type="text" id="sku" class="form-control" id="exampleInputName2" placeholder="SKU / Barcode International" autofocus>
					
					<input type="text" id="search" class="form-control" id="exampleInputName2" placeholder="Search">
					
					
					<input type="hidden" id="search1">
					</div> 

					</div>
					  
			
					<table class="table table-striped" id="example1">
						<thead>
							<tr>
								<th>No</th>
								<th>SKU / Barcode Int.</th>
								<th>QTY</th>
								<th>Status</th>
								<th>User Input</th>

							</tr>
						</thead>
						<tbody id="load_data">
			
						
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
getData("");
// $(window).bind('beforeunload', function(){
  // myFunction();
  // return 'Apakah kamu yakin?';
// });

// function myFunction(){

     // alert('Bye');
// }

$(document).ready(function () {
	// $('#example1').DataTable();
	$('select').selectize({
		sortField: 'text'
	});	
});


// document.getElementById("search").addEventListener("keyup", function() {
// var input, filter, table, tr, td, i, txtValue;
  // input = document.getElementById("search");
  // filter = input.value.toUpperCase();
  // table = document.getElementById("example1");
  // tr = table.getElementsByTagName("tr");
  // for (i = 0; i < tr.length; i++) {
    // td = tr[i].getElementsByTagName("td")[1];
    // td1 = tr[i].getElementsByTagName("td")[2];
    // if (td) {
      // txtValue = td.textContent || td.innerText;
      // txtValue1 = td1.textContent || td1.innerText;
      // if (txtValue.toUpperCase().indexOf(filter) > -1) {
        // tr[i].style.display = "";
      // }else if(txtValue1.toUpperCase().indexOf(filter) > -1){
		// tr[i].style.display = "";  
	  // } else {
        // tr[i].style.display = "none";
      // }
    // }       
  // }
	
	
	
// });

function changeQtyPlus(id){
	var quan = document.getElementById("qty"+id).value;
	var plus = parseInt(quan) + 1;
	document.getElementById("qty"+id).value=plus;
	changeQty(id);
}

function changeQtyMinus(id){
	var quan = document.getElementById("qty"+id).value;
	var plus = parseInt(quan) - 1;
	
	if(plus < 0){
		
		$('#notif').html("TIDAK BOLEH KURANG DARI 0");
	}else{
		
		document.getElementById("qty"+id).value=plus;
		changeQty(id);
	}
	
}

function changeQty(id){
	var quan = document.getElementById("qty"+id).value;
	$.ajax({
		url: "api/action.php?modul=inventory&act=updatecounterinvnasional&stats=0",
		type: "POST",
		data : {id: id, quan: quan},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);
			console.log(dataResult);
			if(dataResult.result=='0'){
				$('#notif').html(dataResult.msg);
			}else if(dataResult.result=='1'){
				$('#notif').html("<font style='color: green'>"+dataResult.msg+"</font>");
			}
			else {
				$('#notif').html("Gagal sync coba lagi nanti!");
			}
			
		}
	});
}

function deleteLine(m_piline_key){
	$.ajax({
		url: "api/action.php?modul=inventory&act=deletelinenasional",
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

function getData(sku){
	
	$.ajax({
		url: "api/action.php?modul=inventory&act=listinvscan&sku="+sku,
		type: "GET",
		beforeSend: function(){
			$("#overlay").fadeIn(300);
		},
		success: function(dataResult){
			$("#overlay").fadeOut(300);
			$("#load_data").html(dataResult);
		}
	});
	
}

function prosesData(){
	
	$.ajax({
		url: "api/action.php?modul=inventory&act=prosesdatanasional",
		type: "POST",
		beforeSend: function(){
			$(".modal").modal('hide');
			$("#overlay").fadeIn(300);
		},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);
			console.log(dataResult);
			if(dataResult.result=='0'){
				$('#notif').html(dataResult.msg);
				$("#overlay").fadeOut(300);
			}else if(dataResult.result=='1'){
				
				$("#overlay").fadeOut(300);
				$('#notif').html("<font style='color: green'>"+dataResult.msg+"</font>");
				$("#example1").load(" #example1");
				
				
			}
			else {
				$("#overlay").fadeOut(300);
				$('#notif').html("Gagal sync coba lagi nanti!");
			}
			
		}
	});
	
}

var input = document.getElementById("sku");
var search = document.getElementById("search");



search.addEventListener("keypress", function(event) {
	if (event.key === "Enter") {
		
		getData(search.value);
		
	}
});

input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
	
	var sku = input.value;
	var url = "api/action.php?modul=inventory&act=updatecounterinvnasional&stats=1";

	if(sku != ""){
		
		$.ajax({
		url: url,
		type: "POST",
		data : {sku: sku},
		beforeSend: function(){
			$('#notif').html("Proses mencari items..");
		},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);
			console.log(dataResult);
			if(dataResult.result=='1'){
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