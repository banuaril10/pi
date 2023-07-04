<?php include "config/koneksi.php"; ?>
<?php include "components/main.php"; ?>
<?php include "components/sidebar.php"; ?>




<div id="app" onload="cekTotal();">
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
				<h4>DATA PICKUP CASH IN	<button type="button" class="btn btn-primary" onclick="focusCash();" data-bs-toggle="modal" data-bs-target="#exampleModal">+</button></h4>

				
					<!--<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal1">Approve Kepala Toko</button>-->
					
					
					
					
					<?php 
					function rupiah($angka){
	
						$hasil_rupiah = number_format($angka,0,',','.');
						return $hasil_rupiah;
 
					}
					if($_SESSION['name'] == 'Ka. Toko' || $_SESSION['name'] == 'Wk. Ka Toko'){ ?>
						
						<select id="userid" name="userid">
							<option value="all" selected>All</option>
							<?php 
							$get_user = "select userid, nama_insert, sum(cash) as totalcash from cash_in group by userid, nama_insert";	
							foreach ($connec->query($get_user) as $rr) { ?>
				
								<option value="<?php echo $rr['userid']; ?>" ><?php echo $rr['nama_insert']; ?></option>
				
							<?php } ?>
						</select>
						
						<input type="date" name="tanggal" id="tanggal" value="<?php echo date("Y-m-d"); ?>">
						<button type="button" onclick="filter();" class="btn btn-danger">Filter</button>
						<button type="button" class="btn btn-success" onclick="cetakStruk()">Cetak Struk Summary</button>
						<button type="button" class="btn btn-warning" id="generate" onclick="cetakExcel()">Generate Excel</button>
						<a id="test" onclick="showGenerate();" class="btn btn-warning" style="display: none" href="">Download</a>
						<button type="button" class="btn btn-primary" id="syncnewpos" onclick="syncNewpos()">Send to Newpos</button>
						<button type="button" class="btn btn-danger" id="syncnewpos" onclick="syncNewposAll()">Send All to Newpos</button>
						
					<?php }else{ ?>
						<input type="date" name="tanggal" id="tanggal" value="<?php echo date("Y-m-d"); ?>">
						<select id="userid" name="userid" style="display: none">
		
				
								<option value="all" selected>Semua Kasir</option>
	
						</select>
						<button type="button" onclick="filter();" class="btn btn-danger">Filter</button>
						<button type="button" class="btn btn-success" onclick="cetakStruk()">Cetak Struk Summary</button>
						<button type="button" class="btn btn-primary" id="syncnewpos" onclick="syncNewpos()">Send to Newpos</button>
						<button type="button" class="btn btn-danger" id="syncnewpos" onclick="syncNewposAll()">Send All to Newpos</button>
						
					<?php } ?>
				
					
					
				
				<font id="notif1" style="color: red; font-weight: bold"></font>	
			</div>
			
			
		
			
			
			
			<div class="card-body">
			
			
			<div class="table-responsive bs-example widget-shadow">		
			<table>
						
							<tr>
								<th>Total Cash In (Approved) : <font id="total"></font> </th>
							</tr>
						
						
					</table>
			
			</div>
			
			
			
			
			<div class="tables" id="reload">			
				<div class="table-responsive bs-example widget-shadow">	
					
				
				
				
					<table class="table table-bordered" id="example" style="width: 100%">
						<thead>
							<tr>
								
								
								<th>Insert By</th>
								<th>Cash</th>
								<th>Insert Date</th>
								<th>Status Approved</th>
								<th> Approved By</th>
								<th>Sync New Pos</th>
								<th>Setoran ke-</th>
		
		
							</tr>
						</thead>
						<tbody>
   
   
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</div>


</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div id="overlay">
			<div class="cv-spinner">
				<span class="spinner"></span>
			</div>
		</div>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Input Data Cash In</h5><br>
       
		 
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		
		
      </div>
	  
      <div class="modal-body" style="background: #cacaca">
	  
	   
	    <p id="notif" style="color: red; font-weight: bold; background: #fff; padding: 10px"></p>
		
		<div class="row-info"> 
			
			
        <input type="hidden" class="form-control" value="<?php echo $_SESSION['org_key']; ?>" id="org_key" name="org_key" placeholder="Masukan password kamu..">
        <input type="hidden" class="form-control" value="<?php echo $_SESSION['username']; ?>" id="nama_insert" name="nama_insert" placeholder="Masukan password kamu..">
	  

	
	<div class="row">
      <div class="col-25">
        <label style="color: #fff"for="fname" >Cash In</label>
      </div>
      <div class="col-75">
        <input class="form-control" type="text" id="cash" name="firstname" placeholder="10000" autofocus>
      </div>
    </div>
	<br>
			
		
			
		</div> 
		
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
        <button type="button" id="butsave" onclick="saveLokal();" class="btn btn-primary">SUBMIT</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div id="overlay">
			<div class="cv-spinner">
				<span class="spinner"></span>
			</div>
		</div>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Masukan UserID dan Password Kepala Toko</h5><br>
       
		 
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		
		
      </div>
	  
      <div class="modal-body" style="background: #cacaca">
	
	    <p id="notif2" style="color: red; font-weight: bold; background: #fff; padding: 10px">
		Perhatikan nominal pick up sebelum diapprove apakah sudah benar
		</p>
		
		<div class="row-info"> 
			

		<div class="row">
		<div class="col-25">
			<label style="color: #fff"for="fname" >Username</label>
		</div>
		<div class="col-75">
			<input class="form-control" type="text" id="username" placeholder="Masukan userid" autocomplete="off">
		</div>
		</div>
		<div class="row">
		<div class="col-25">
			<label style="color: #fff"for="fname" >Password</label>
		</div>
		<div class="col-75">
			<input class="form-control" type="password" id="password" placeholder="Masukan password" autocomplete="false" readonly onfocus="this.removeAttribute('readonly');">
		</div>
		</div>	
		
			
		</div> 
		
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
        <button type="button" id="butsave" onclick="cekCredential();" class="btn btn-primary">SUBMIT</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.2.6/jquery.inputmask.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-numeric@1.5.0/jquery.numeric.min.js"></script>


<script type="text/javascript">
cekTotal();
function filter(){
	
	//$('#example').DataTable().ajax.reload();
	loadTable();
	cekTotal();
	// $('#reload').load(" #reload");
	
}

function focusCash(){
	document.getElementById('cash').focus();
	
}

function cekTotal(){
			var userid = document.getElementById("userid").value;		
			var tanggal = document.getElementById("tanggal").value;		
			
			
		$.ajax({
			url: "api/action.php?modul=inventory&act=total_pickup&userid="+userid+"&tanggal="+tanggal,
			type: "GET",
			success: function(dataResult){
				
				
				
				// console.log(dataResult);
				var dataResult = JSON.parse(dataResult);
				document.getElementById("total").innerHTML = dataResult.total;
				
				
			}
		});
	
	
}

$("#exampleModal").on('shown.bs.modal', function () {
                $(this).find('#cash').focus();
				$("#cash").inputmask("decimal", { 
				placeholder: "0",
				digits:2,
				digitsOptional: false,
				radixPoint: ",",
				groupSeparator: ".",
				autoGroup: true,
				allowPlus: false,
				allowMinus: false,
				clearMaskOnLostFocus: false,
				removeMaskOnSubmit: true,
				autoUnmask: true,
				onUnMask: function(maskedValue, unmaskedValue) {
					var x = maskedValue.split(',');
					return x[0].replace(/\./g, '') + '.' + x[1];
				}
			
			});
			
			
			
			
			
			// var elem = document.getElementById("cash");
			// elem.addEventListener("keydown",function(event){
				// var key = event.which;
				// if((key<48 || key>57) && key != 8) event.preventDefault();
			// });

			// elem.addEventListener("keyup",function(event){
				// var value = this.value.replace(/,/g,"");
				// this.dataset.currentValue=parseInt(value);
				// var caret = value.length-1;
				// while((caret-3)>-1)
				// {
					// caret -= 3;
					// value = value.split('');
					// value.splice(caret+1,0,",");
					// value = value.join('');
				// }
				// this.value = value;
			// });
			
			
            });

// $(document).ready(function(){


			
			 
// });

function print_text(html){
	// console.log(html);
	$.ajax({
		url: "print.php",
		type: "POST",
		data : {html: html},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);

			$('#notif1').html("Proses print");
			
			
		}
	});
}

function textbyline(str,intmax,stralign){
    var strresult='';
  if (stralign=='right'){
    strresult=str.padStart(intmax);
  } else if (stralign=='center'){
    var l = str.length;
    var w2 = Math.floor(intmax / 2);
    var l2 = Math.floor(l / 2);
    var s = new Array(w2 - l2 + 1).join(" ");
    str = s + str + s;
    if (str.length < intmax)
    {
        str += new Array(intmax - str.length + 1).join(" ");
    }
    strresult=str;
  } else {
    strresult=str;
  }
  return strresult;
};

function cetakStruk(){
		
		// alert(mpi+'<br>'+rn+'<br>'+dn);		
		var number = 0;	
		
		var userid = document.getElementById("userid").value;		
		var tanggal = document.getElementById("tanggal").value;		
		//alert(userid);	
		// alert(html);
		$.ajax({
			url: "api/action.php?modul=inventory&act=cetak_pickup&userid="+userid+"&tanggal="+tanggal,
			type: "GET",
			success: function(dataResult){

				// console.log(dataResult);
				
				var dataResult = JSON.parse(dataResult);
				
				
				
				var html = 'PICKUP CASH IN\n\n';
				
			var panjangkasir = dataResult.kasir.length;
			for(let b = 0; b < dataResult.kasir.length; b++) {
				let datakasir = dataResult.kasir[b];
				
				
				html += '\nTanggal      : '+datakasir.tanggal+' \n';
				html += 'Nama Kasir   : '+datakasir.username+' \n';
				html += 'Total Pickup : '+datakasir.totalcash+' \n';
				html += 'No | '+textbyline('Jam',9,'right')+' | '+textbyline('Cash',13,'right')+' \n';
				
				var panjang = datakasir.data.length;
				var no = 1;	
				for(let i = 0; i < datakasir.data.length; i++) {
						let data = datakasir.data[i];
						var jam = data.jam;
						var cash = data.cash;
						var approvedby = data.approvedby;
						
							
							html += no+'  |  '+jam+' | ';
							html +=textbyline(''+cash+'',13,'right');
							html += "\n";

								
			
							
					no++;
				
				}
				
				html += "------------------------------\n";
				
				number++;
				
				
				if(number == panjangkasir){
							
								html+='\n';
								html+='\n';
								html+='\n';
								html+='\n';
								html+='\n';
								html+='\n';
								print_text(html);
								console.log(html);
							}
				
			}
			
				
			}
		});

				
}

function showGenerate(){
	document.getElementById("test").style.display = 'none';
	document.getElementById("generate").style.display = '';
}
	


function syncNewpos(){
	var userid = document.getElementById("userid").value;		
	var tanggal = document.getElementById("tanggal").value;		
	
	
			$.ajax({
			url: "api/action.php?modul=inventory&act=send_newpos&userid="+userid+"&tanggal="+tanggal,
			type: "GET",
			beforeSend: function(){
					$('#notif1').html("Proses input cashin..");
					$("#overlay").fadeIn(300);　
				},
			success: function(dataResult){

				console.log(dataResult);
				var dataResult = JSON.parse(dataResult);
				if(dataResult.result=='1'){
						$('#notif1').html('<font style="color: green">'+dataResult.msg+'</font>');
						$("#overlay").fadeOut(300);　
						loadTable();
						cekTotal();
						
					}
					else {
						$("#overlay").fadeOut(300);　
						$('#notif1').html(dataResult.msg);
						loadTable();
						cekTotal();
					}
				
				
				
				// console.log(dataResult);
				
			}
		});
	
	
	
}

function syncNewposAll(){
			$.ajax({
			url: "api/action.php?modul=inventory&act=send_newposall",
			type: "GET",
			beforeSend: function(){
					$('#notif1').html("Proses input cashin..");
					$("#overlay").fadeIn(300);　
				},
			success: function(dataResult){

				console.log(dataResult);
				var dataResult = JSON.parse(dataResult);
				if(dataResult.result=='1'){
						$('#notif1').html('<font style="color: green">'+dataResult.msg+'</font>');
						$("#overlay").fadeOut(300);　
						loadTable();
						cekTotal();
						
					}
					else {
						$("#overlay").fadeOut(300);　
						$('#notif1').html(dataResult.msg);
						loadTable();
						cekTotal();
					}
				
				
				
				// console.log(dataResult);
				
			}
		});
	
	
	
}

function cetakExcel(){
		
		// alert(mpi+'<br>'+rn+'<br>'+dn);		
		var number = 0;	
		
		var userid = document.getElementById("userid").value;		
		var tanggal = document.getElementById("tanggal").value;		
		//alert(userid);	
		// alert(html);
		$.ajax({
			url: "api/action.php?modul=inventory&act=cetak_excel&userid="+userid+"&tanggal="+tanggal,
			type: "GET",
			success: function(dataResult){

				// console.log(dataResult);
				
				var dataResult = JSON.parse(dataResult);
				
				
				
					testJson = dataResult;


					testTypes = {
						"insertby": "String",
						"cash": "String",
						"insertdate": "String",
						"status": "String",
						"approvedby": "String",
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
					
					download(jsonToSsXml(testJson), 'Laporan Cash In '+tanggal+'.xls', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				
					
					
				
			}
		});

				
}







// function cetakExcel(){
	

	
//}



function cetakStrukDetail(id){
		
		// alert(mpi+'<br>'+rn+'<br>'+dn);		
		var number = 0;	
		var no = 1;	
				
		
		// alert(html);
		$.ajax({
			url: "api/action.php?modul=inventory&act=cetak_pickupdetail&id="+id,
			type: "GET",
			success: function(dataResult){

				// console.log(dataResult);
				
				var dataResult = JSON.parse(dataResult);
				
				var html = 'PICKUP CASH IN (SETORAN KE-'+dataResult.setoran+')\n\n';
				html += 'Tanggal     : '+dataResult.tanggal+' \n';
				html += 'Nama Kasir  : '+dataResult.username+' \n';
				html += 'Approved By : '+dataResult.approvedby+' \n\n';
				html += 'No | '+textbyline('Jam',9,'right')+' | '+textbyline('Cash',13,'right')+' \n';
				
				var panjang = dataResult.data.length;
		
				for(let i = 0; i < dataResult.data.length; i++) {
						let data = dataResult.data[i];
						var jam = data.jam;
						var cash = data.cash;
						
							
							html += no+'  |  '+jam+' | ';
							html +=textbyline(''+cash+'',13,'right');
							html += "\n";

								
			
							number++;
							no++;
							
							
							
							if(number == panjang){
								
								html+='\n Ttd Ka. Toko / Wkl Ka. Toko';
								html+='\n';
								html+='\n';
								html+='\n';
								html+='\n';
								html+='___________________________';
							
								html+='\n';
								html+='\n';
								html+='\n';
								html+='\n';
								html+='\n';
								html+='\n';
								print_text(html);
								console.log(html);
							}
					
				
				}
			}
		});

				
}




	function loadTable(){
			var userid = document.getElementById("userid").value;		
			var tanggal = document.getElementById("tanggal").value;		
			
			
			
			
           $('.table').DataTable({
              "processing": true,
              "serverSide": true,
			  "destroy" : true,
              "ajax":{
                       "url": "api/action.php?modul=inventory&act=api_cashin&userid="+userid+"&tanggal="+tanggal,
                       "dataType": "json",
                       "type": "POST"
                     },
              "columns": [
                  // { "data": "no" },
                  { "data": "nama_insert" },
                  { "data": "cash" },
                  { "data": "insertdate" },
                  { "data": "status" },
                  { "data": "approvedby" },
                  { "data": "syncnewpos" },
				  { "data": "setoran" }
                  // { "data": "price" },
                  // { "data": "price_discount" },
              ]  
 
          });
		
		
	}
			

  $(function(){
			var userid = document.getElementById("userid").value;		
			var tanggal = document.getElementById("tanggal").value;		
			
           $('.table').DataTable({
              "processing": true,
              "serverSide": true,
			  "destroy" : true,
              "ajax":{
                       "url": "api/action.php?modul=inventory&act=api_cashin&userid="+userid+"&tanggal="+tanggal,
                       "dataType": "json",
                       "type": "POST"
                     },
              "columns": [
                  // { "data": "no" },
                  { "data": "nama_insert" },
                  { "data": "cash" },
                  { "data": "insertdate" },
                  { "data": "status" },
				  { "data": "approvedby" },
				  { "data": "syncnewpos" },
				  { "data": "setoran" }
                  // { "data": "price" },
                  // { "data": "price_discount" },
              ]  
 
          });
        });
	
	document.getElementById("cash").autofocus;
	
	
	$("#cash").keyup(function(event) {
    if (event.keyCode === 13) {
       saveLokal();
    }
});
	
	// document.getElementById("cash").addEventListener("keypress", function(event) {
	// if (event.key === "Enter") {
		// saveLokal();
		// alert("su");
	// }
	// });
	
	function saveLokal(){
		// document.getElementById("num").dataset.currentValue
		var cash = document.getElementById("cash").value;
		// alert(cash);
		var nama_insert = document.getElementById("nama_insert").value;
		var org_key = document.getElementById("org_key").value;
		// alert(cash);
		
		$.ajax({
				url: "api/action.php?modul=inventory&act=input_cashin",
				type: "POST",
				data: {
					cash: cash			
				},
				cache: false,
				beforeSend: function(){
					$('#notif').html("Proses input cashin..");
				},
				success: function(dataResult){
					console.log(dataResult);
					var dataResult = JSON.parse(dataResult);
					if(dataResult.result=='1'){
						$('#notif').html('<font style="color: green">'+dataResult.msg+'</font>');
						$("#overlay").fadeOut(300);　
						
						
						
						location.reload();
						
						
					}
					else {
						$("#overlay").fadeOut(300);　
						$('#notif').html(dataResult.msg);
					}
					
				}
			});

	}
	
	
	
	function cekCredential(){
		
		var username = document.getElementById("username").value;
		var password = document.getElementById("password").value;
		
		
		$.ajax({
				url: "api/action.php?modul=inventory&act=cek_credentials",
				type: "POST",
				data: {
					username: username,
					password: password		
				},
				cache: false,
				beforeSend: function(){
					$('#notif2').html("Proses credentials..");
				},
				success: function(dataResult){
					console.log(dataResult);
					var dataResult = JSON.parse(dataResult);
					if(dataResult.result=='1'){
						$('#notif2').html('<font style="color: green">'+dataResult.msg+'</font>');
						$("#overlay").fadeOut(300);　
						
						
						location.reload();
						
						
					}
					else {
						$("#overlay").fadeOut(300);　
						$('#notif2').html(dataResult.msg);
					}
					
				}
			});

	}
	
	function cekCredentialDetail(idcashin){
		
		var username = document.getElementById("username"+idcashin).value;
		var password = document.getElementById("password"+idcashin).value;
		
		
		$.ajax({
				url: "api/action.php?modul=inventory&act=cek_credentials_detail&id="+idcashin,
				type: "POST",
				data: {
					username: username,
					password: password		
				},
				cache: false,
				beforeSend: function(){
					$('#notif'+idcashin).html("Proses credentials..");
				},
				success: function(dataResult){
					console.log(dataResult);
					var dataResult = JSON.parse(dataResult);
					if(dataResult.result=='1'){
						$('#notif'+idcashin).html('<font style="color: green">'+dataResult.msg+'</font>');
						$("#overlay").fadeOut(300);　
						loadTable();
						cekTotal();
						cetakStrukDetail(idcashin);
					
						$('.modal').modal('hide');
						// $('#example').load(' #example');
						$('#example').DataTable().ajax.reload();
						// location.reload();
						
						
					}
					else {
						$("#overlay").fadeOut(300);　
						$('#notif'+idcashin).html(dataResult.msg);
					}
					
				}
			});

	}
	
	
	
	// document.getElementById("password").addEventListener("keypress", function(event) {
	// if (event.key === "Enter") {
		// cekCredential()
		
	// }
	// });
	
</script>
</div>
<?php include "components/fff.php"; ?>