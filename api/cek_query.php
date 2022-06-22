<?php
include "../config/koneksi_erp.php";
//bos online

					$sql_erp = "select * from m_storage_detail where m_locator_id ='4DC01BB67AB148C9A02C4F5DB39AF969' and m_product_id ='AB3F5A56918B4BEDAE9D2FE68296779A'";
					foreach ($connec_erp->query($sql_erp) as $row_erp) { //cek dulu apakah ada
							
							
							print_r($row_erp);
					}
			
					

    

?>