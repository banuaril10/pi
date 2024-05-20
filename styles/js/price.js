document.getElementById("btn-cetak-tinta").addEventListener("click", function() { //cetak besar ini yg dipake
				var array = [];
				var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');
				var brand = document.getElementById('brand').value;
				
				
				if(brand == 'IDOLMART'){
					
					brand = brand+" UNIK ";
					
				}
				
				
				for (var i = 0; i < checkboxes.length; i++) {
					array.push(checkboxes[i].value)
				}
				let text = "<table><tr>";
				// let text = "<div style='position: relative; width: 920px; height: 135px; padding-top: 25px; border: 1px solid #000;'>";
				
				var x = 1;
				for (let i = 0; i < array.length; i++) {
						var res = array[i].split("|");
						
					
						var sku = res[0];
						var name = res[1];
						var normal = res[2];
						var dates = res[3];
						var rack = res[4];
						var shortcut = res[5];
						var afterdiscount = res[6];
						var tag = res[7];
						var kodetoko_tgl = res[8];
						var barcode = res[9];
						
						if(shortcut === 'undefined' || shortcut === 'null' || shortcut === ''){
							
							var sc = '';
						}else{
							var sc = '/'+shortcut;
						}
						
						if(x==5){
							var x = 1;
						}

						var lengthh = name.length;
						var panjangharga = parseInt(normal);
	
						
						var sizeprice = "49px";
						var margin_bot = "0";
						if(panjangharga > 999999){
							sizeprice = "40px";
							margin_bot = "9px";
						}

						var newStr = "";
						
						if(kodetoko_tgl != ""){
							newStr = kodetoko_tgl;
						}
						
						if(rack != ""){
							newStr += "/"+rack;
						}
						
						if(tag != ""){
							newStr += "/"+tag;
						}
						
						if(sku != ""){
							newStr += "<br>"+sku;
						}
						
						if(barcode != ""){
							newStr += "/"+barcode;
						}

						
							text += "<td style='border: 0.5px solid #000'><div style='margin:5px 5px 0 5px; color: black; width: 177px; height: 121px; font-family: Calibri; '><div style='height:30px; text-align: left; font-size: 10px'><b>"+name.toUpperCase()+"</b></div><label style='color: #000; margin: -10px 0 "+margin_bot+" 0; float: right; font-size: "+sizeprice+"'><label style='font-size: 10px'><b>Rp </b></label><b>"+formatRupiah(normal, '')+"</b></label><hr style='border-top: solid 1px #000 !important; background-color:black; border:none; height:1px; margin:5px 0 5px 0; width: 100%'><label style='text-align: left; font-size: 10px; width: 100%'>"+newStr+"</label></div></td>";
							
							// text += "<td style='border: 0.5px solid #000'><div style='margin:5px 5px 0 5px; color: black; width: 177px; height: 121px; font-family: Calibri; '><div style='height:30px; text-align: left; font-size: 10px'><b>"+res[1].toUpperCase()+"</b></div><label style='margin: -10px 0 0 0; float: right; font-size: "+sizeprice+"'><label style='font-size: 10px'><b>Rp </b></label><b>"+formatRupiah(res[2], '')+"</b></label><label style='text-align: left; font-size: 7px; width: 100%'>"+newStr+"</label><center><hr style='border-top: solid 1px #000 !important; background-color:black; border:none; height:1px; margin:5px 0 5px 0;'><label style='text-align: center; font-size: 8px; margin-top: 10px'>"+brand+" MURAH DAN LENGKAP</label></center></div></td>";
							
						
						
							if((i+1)%4==0 && i!==0){
							
								text += "</tr><tr>";
							}
							x++;

					}
			
				text += "</table>";
					

				  var mywindow = window.open('', 'my div', 'height=600,width=800');
							/*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
							mywindow.document.write('<style>@media print{@page {size: potrait; width: 216mm;height: 280mm;margin-top: 15;margin-right: 2;margin-left: 2; padding: 0;} margin: 0; padding: 0;} table { page-break-inside:auto }tr{ page-break-inside:avoid; page-break-after:auto }</style>');
							mywindow.document.write(text);

					
							mywindow.print();
							// mywindow.close();
					
							return true;
			});