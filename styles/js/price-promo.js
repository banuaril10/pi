			document.getElementById("btn-cetak-tinta").addEventListener("click", function() { //cetak besar
				var array = [];
				var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');
				
				for (var i = 0; i < checkboxes.length; i++) {
					array.push(checkboxes[i].value)
				}
				let text = "<table><tr>";
				// let text = "<div style='position: relative; width: 920px; height: 135px; padding-top: 25px; border: 1px solid #000;'>";
				
				var x = 1;
				for (let i = 0; i < array.length; i++) {
				var res = array[i].split("|");
						
						if(res[5] === 'undefined' || res[5] === 'null' || res[5] === ''){
							
							var sc = '';
						}else{
							var sc = ' / '+res[5];
						}
						
						// if(i == 0){
							// text += "";
						// }
						if(x==5){
							var x = 1;
						}
						
						var lengthh = res[1].length;
						var panjangharga = parseInt(res[6]);
						
						var br = "<br>";
						
						
						
						var sizeprice = "45px";
						if(lengthh > 33){
							
							 sizeprice = "45px";
						}
						
						if(panjangharga > 999999){
							sizeprice = "40px";
							
						}
						var barcode = res[9];
						if(res[4] != ""){
							var rack = res[8]+"/"+res[4]+"/"+res[7];
							
							
						}else{
							
							var rack = res[8]+"/NO_RACK/"+res[7];
							
						}
						var rack2 = res[0]+"/"+barcode;
						
						
						
						var newStr = rack.replace('-', '_')+"<br>"+rack2.replace('-', '_');
						// text += "<td style='border: 0.5px solid #000;'><div style='margin:5px 5px 0 10px; text-align:left; display: inline-block; color: black; width: 171px; height: 121px; font-family: Calibri'><table>"+
						// "<tr><td style='padding:0; border: 1px'><label style='text-align: right; font-size: 14px'><b>"+res[1].toUpperCase()+"</b></label></td></tr>"+
						// "<tr><td style='padding:0'><label style='text-align: left; font-size: 8px'><b>Rp </b></label><label style='text-align: left; font-size: 15px; text-decoration: line-through;'><b>"+formatRupiah(res[2], '')+"</b></label></td></tr>"+
						// "<tr><td style='padding:0'><label style='text-align: left; font-size: 14px'><b>Rp </b></label><label style='text-align: left; font-size: 30px;'><b>"+formatRupiah(res[6], '')+"</b></label></td></tr>"+
						// "<tr><td><label style='text-align: left; font-size: 14px'>"+res[0]+""+sc+"</label></td></tr>"+
						// "<tr><td><label style='text-align: left; font-size: 14px'>"+res[3]+"</label> / <label style='text-align: left; font-size: 14px'>"+res[4].toUpperCase()+"</label></td></tr>"+
						// "<tr><td><hr><label>HARGA KEJUTAN</label></td></tr></table></div></td>";
						
						text += "<td style='border: 0.5px solid #000'><div style='margin:5px 5px 0 5px; color: black; width: 177px; height: 121px; font-family: Calibri; '><div style='height:25px; text-align: left; font-size: 10px'><b>"+res[1].toUpperCase()+"</b></div><label style='text-align: left; font-size: 10px'><b>Rp </b></label><label style='text-align: left; font-size: 18px; text-decoration: line-through;'><b>"+formatRupiah(res[2], '')+"</b></label><label style='float: right !important; font-size: 10px;'> s.d. "+res[7]+"</label><label style='margin: -10px 0 0 0; float: right; font-size: "+sizeprice+"'><label style='font-size: 10px'><b>Rp </b></label><b>"+formatRupiah(res[6], '')+"</b></label> &nbsp &nbsp &nbsp &nbsp &nbsp <br><br><br><hr style='width: 100%;border-top: solid 1px #000 !important; background-color:black; border:none; height:1px; margin:1.5px 0 0 0;'><label style='text-align: center; font-size: 10px; margin-top: -10px'>"+newStr+"</label></div></td>";
						
						// text += "<td style='border: 0.5px solid #000'><div style='margin:5px 5px 0 5px; color: black; width: 177px; height: 121px; font-family: Calibri; '><div style='height:25px; text-align: left; font-size: 10px'><b>"+res[1].toUpperCase()+"</b></div><label style='text-align: left; font-size: 10px'><b>Rp </b></label><label style='text-align: left; font-size: 18px; text-decoration: line-through;'><b>"+formatRupiah(res[2], '')+"</b></label><label style='float: right !important; font-size: 10px;'> s.d. "+res[7]+"</label><label style='margin: -10px 0 0 0; float: right; font-size: "+sizeprice+"'><label style='font-size: 10px'><b>Rp </b></label><b>"+formatRupiah(res[6], '')+"</b></label> &nbsp &nbsp &nbsp &nbsp &nbsp <br><br><hr style='width: 100%; border-top: solid 1px #000 !important; background-color:black; border:none; height:1px; margin:1.5px 0 0 0;'><label style='font-size: 10px'><b>Rp </b></label><b>"+formatRupiah(res[6], '')+"</b></label> &nbsp &nbsp &nbsp &nbsp &nbsp <br><br><label style='text-align: left; font-size: 10px; width: 100%'>"+newStr+"</label></div></td>";
						
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