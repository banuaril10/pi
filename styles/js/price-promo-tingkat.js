document.getElementById("btn-cetak-tinta-promo").addEventListener("click", function() {

				
				//cetak besar
				var array = [];
				var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');
				
				for (var i = 0; i < checkboxes.length; i++) {
					array.push(checkboxes[i].value)
				}
				let text = "<table><tr>";
				// let text = "<div style='position: relative; width: 920px; height: 135px; padding-top: 25px; border: 1px solid #000;'>";
				
				var x = 1;
				for (let i = 0; i < array.length; i++) {
				var harga = "";
				var res = array[i].split("|");
				
				var sku = res[0];
				var name = res[1];
				var price1 = res[2];
				var price2 = res[3];
				var price3 = res[4];
				var tgl = res[5];
				var rack = res[6];
				var todate = res[7];
				var price = res[8];
				
				var minbuy1 = res[9];
				var minbuy2 = res[10];

				// alert(price);
						if(x==5){
							var x = 1;
						}
						
						// var lengthh = res[1].length;
						// var panjangharga = parseInt(res[6]);
						
						var br = "<br>";
						
						
						
						// var sizeprice = "20px";

						
						if(rack != ""){
							var rack = sku+"/"+rack;
							
							
						}else{
							
							var rack = sku+"/NO_RACK";
						}
						
						// harga += "<label style='width: 100%; margin: -10px 0 0 0; font-size: 17px'><br>";
						harga += "<table border='0' cellspacing='0' cellpadding='0' style='width: 100%;border-spacing: 0;border-collapse: collapse; margin-top:10px'>";
						
						if(price != '0'){
							// harga += "<label style='font-size: 10px'>Beli 1 </label>   <div style='float: right !important'><label style='font-size: 8px'><b>Rp </b></label> <b>"+formatRupiah(price, '')+"</b></div>   <br>";
							harga += "<tr style='font-size: 10px'><td>Beli 1 </td>   <td style='text-align: right'><label style='font-size: 8px'><b>Rp </b></label> <b style='font-size: 17px'>"+formatRupiah(price, '')+"</b>/pcs</td></tr> ";
							
						}
						
						if(price1 != '0'){
							// harga += "<label style='font-size: 10px'>Beli 3 </label>  <div style='float: right !important'><label style='font-size: 8px'><b>Rp </b></label><b>"+formatRupiah(price1, '')+"</b></div><br>";
							harga += "<tr style='font-size: 10px'><td>Beli "+minbuy1+" </td>   <td style='text-align: right'><label style='font-size: 8px'><b>Rp </b></label> <b style='font-size: 17px'>"+formatRupiah(price1, '')+"</b>/pcs</td></tr> ";
							
						}
						
						if(price2 != '0'){
							// harga += "<label style='font-size: 10px'>Beli 12 </label>  <div style='float: right !important'><label style='font-size: 8px'><b>Rp </b></label><b>"+formatRupiah(price2, '')+"</b></div><br>";
							harga += "<tr style='font-size: 10px'><td>Beli "+minbuy2+" </td>   <td style='text-align: right'><label style='font-size: 8px'><b>Rp </b></label> <b style='font-size: 17px'>"+formatRupiah(price2, '')+"</b>/pcs</td></tr> ";
							
						}
						
						// if(price3 != '0'){
							// harga += "<label style='font-size: 10px'>Beli 12 </label><label style='font-size: 8px'><b>Rp </b></label><b>"+formatRupiah(price3, '')+"</b><br>";
							
						// }
						
						
						harga += "</table>";
						// harga += "</label>";
						
						var newStr = rack.replace('-', '_');
						
						text += "<td style='border: 0.5px solid #000'><div style='margin:5px 5px 0 5px; color: black; width: 177px; height: 121px; font-family: Calibri; '><div style='height:22px; text-align: left; font-size: 10px'><b>"+name.toUpperCase()+"</b></div><label style='float: right !important; font-size: 8px;'> s.d. "+todate+"</label>"+harga+"<label style='text-align: left; font-size: 8px; width: 100%'>"+newStr+"</label><center><hr style='border-top: solid 1px #000 !important; background-color:black; border:none; height:1px; margin:1.5px 0 0 0;'><label style='text-align: center; font-size: 8px; margin-top: -10px'>HARGA KEJUTAN</label></center></div></td>";
						
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