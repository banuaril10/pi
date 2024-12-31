<?php include "../config/koneksi.php"; 

$cmd_grosir = [
"CREATE OR REPLACE FUNCTION public.proc_pos_dtempsalesline_update(p_pos_dtempsalesline_key character varying DEFAULT NULL::character varying, p_qty character varying DEFAULT NULL::character varying, p_memberid character varying DEFAULT NULL::character varying, p_promoid character varying DEFAULT NULL::character varying, OUT o_data json, OUT o_message character varying)
 RETURNS record
 LANGUAGE plpgsql
AS $function$
  declare   v_pos_mcashier_key varchar(32);
 			v_total numeric;
	    	v_strtotal varchar;
	        v_maxqty numeric;
	        v_sku varchar;
	        v_discount_name varchar(75);
	        v_discount_1 numeric;
	        
BEGIN 

if exists(select 1 from pos_dtempsalesline where pos_dtempsalesline_key=p_pos_dtempsalesline_key and isrefund=true) then
   o_message='REFUND QTY TIDAK BISA DI EDIT !!';
else
	if exists(select 1 from pos_dtempsalesline where pos_dtempsalesline_key=p_pos_dtempsalesline_key) then
	  
	
	
	   if (p_memberid<>'' or p_promoid <>'') then
	   
	   
			 if exists(select 1 from pos_mproductdiscountmember a inner join pos_dtempsalesline b on a.sku=b.sku where pos_dtempsalesline_key=p_pos_dtempsalesline_key) then
			    select into v_maxqty a.maxqty 
			    from pos_mproductdiscountmember a inner join pos_dtempsalesline b on a.sku=b.sku where pos_dtempsalesline_key=p_pos_dtempsalesline_key;
			 else
			    
			   if exists (select 1 from pos_dtempsalesline a inner join pos_mproductdiscount b on a.discountname=b.discountname where pos_dtempsalesline_key=p_pos_dtempsalesline_key) then 
			       o_message='Tebus Murah tidak boleh di Edit !'; 
			   
			   end if;
			    
			   v_maxqty=0;
			 end if;
			
			
			
			
  		else 
     		v_maxqty=0;
		end if;
	
    if not exists (select 1 from pos_dtempsalesline where pos_dtempsalesline_key=p_pos_dtempsalesline_key and coalesce(memberid,'') not like '' and discount > 0) then 
	    	
	    if (cast(p_qty as numeric) <= v_maxqty) or (v_maxqty=0) then
	    
		
			
		
		
		      if not exists(select 1 from pos_dtempsalesline where pos_dtempsalesline_key=p_pos_dtempsalesline_key and coalesce(ispromomurah,false)=true) then
				
				        if exists(select 1 from ad_morg where isqty=true ) then
					          
								v_discount_name = 'a';
								select sku, discount into v_sku, v_discount_1 from pos_dtempsalesline where pos_dtempsalesline_key=p_pos_dtempsalesline_key;				
								if exists (select 1 from pos_mproductdiscountgrosir_new where sku like v_sku and DATE(now()) between fromdate and todate) then
				
										select discount, discountname into v_discount_1, v_discount_name  from pos_mproductdiscountgrosir_new where sku like v_sku and DATE(now()) between fromdate and todate and 
										cast(p_qty as numeric) >= minbuy order by minbuy desc limit 1;
								end if;
							  
							  
					         
					          if exists(select 1 from pos_mproduct where sku like v_sku and (stockqty - cast(p_qty as numeric)) < 0 and not left(sku,3)='839' and not sku='8320100000139') then
				              	o_message='Tidak Ada Stok atau Stok tidak Cukup !!';
				              else
				             	update pos_dtempsalesline set
								 qty=cast(p_qty as numeric),
								 discount=COALESCE(cast(v_discount_1 as numeric), 0),
								 discountname=v_discount_name
								where pos_dtempsalesline_key=p_pos_dtempsalesline_key and coalesce(ispromomurah,false)=false;
								o_message='Success';			             
				             end if;
				      			      
				        else
						
								v_discount_name = 'a';
								select sku, discount into v_sku, v_discount_1  from pos_dtempsalesline where pos_dtempsalesline_key=p_pos_dtempsalesline_key;				
								if exists (select 1 from pos_mproductdiscountgrosir_new where sku like v_sku and DATE(now()) between fromdate and todate) then
					
										select discount, discountname into v_discount_1, v_discount_name  from pos_mproductdiscountgrosir_new where sku like v_sku and DATE(now()) between fromdate and todate and 
										cast(p_qty as numeric) >= minbuy order by minbuy desc limit 1;
								end if;
						
						
						
						
						       update pos_dtempsalesline set
								 qty=cast(p_qty as numeric),
								 discount=COALESCE(cast(v_discount_1 as numeric), 0),
								 discountname=v_discount_name
								where pos_dtempsalesline_key=p_pos_dtempsalesline_key and coalesce(ispromomurah,false)=false;
								o_message='Success';
					    end if;
					 
		      else	      
		        	o_message='Tebus Murah tidak boleh di Edit !';
		      end if;
		     
		       
	    else
	    
	    	o_message='Total Pembelian Lebih dari Limit';
	    end if;
	   
	   else
	     o_message='Maaf..Item Discount Tidak Bisa di Edit';
	   end if;
	   
	else
	   o_message='Fail';
	end if;
	
	
	select pos_mcashier_key into v_pos_mcashier_key from pos_dtempsalesline where pos_dtempsalesline_key=p_pos_dtempsalesline_key;
	
  select into v_total sum(qty*(price-coalesce(discount,0))) from pos_dtempsalesline where pos_mcashier_key=v_pos_mcashier_key;
	 v_strtotal='Rp. '||to_char(v_total, 'FM999,999,999');
	
	execute 'select array_to_json(array_agg(row_to_json(t)))
	      from (
	  		Select $1 as lasttempvalue,$2 as lasttempstring
	  		) t '
	      INTO o_data
	      using v_total,v_strtotal;
 end if;
END;
$function$
;



CREATE OR REPLACE FUNCTION public.proc_pos_dtempsalesline_insert(p_ad_mclient_key character varying DEFAULT NULL::character varying, p_ad_morg_key character varying DEFAULT NULL::character varying, p_pos_mcashier_key character varying DEFAULT NULL::character varying, p_billno character varying DEFAULT NULL::character varying, p_sku character varying DEFAULT NULL::character varying, p_qty character varying DEFAULT NULL::character varying, p_price character varying DEFAULT NULL::character varying, p_discount character varying DEFAULT NULL::character varying, p_discountname character varying DEFAULT NULL::character varying, p_memberid character varying DEFAULT NULL::character varying, p_postby character varying DEFAULT NULL::character varying, p_ad_muser_key character varying DEFAULT NULL::character varying, OUT o_data json, OUT o_message character varying)
 RETURNS record
 LANGUAGE plpgsql
AS $function$
declare v_seqno integer;
		v_pos_mshop_key varchar(36);
	    v_total numeric;
	    v_strtotal varchar;
	    v_maxqty numeric;
	    v_qty numeric;
	   
	    v_totalamount numeric;
	    v_ispromo bool;
	    v_limitamount numeric;
	    v_ismurah bool;
	    v_pricediscount numeric;
	  
	    v_membername varchar;
	    v_memberid varchar;
	    v_membercardno varchar;
	    v_memberpoint numeric;
	    v_isbirthday bool;
	    v_qtysale numeric;
	    v_isbuyget bool;
	    v_qtybuy int;
		
	    v_discount_1 int;
		v_grosir bool;
		v_discount_name varchar;
BEGIN 
	
if cast(replace(p_price,',','') as numeric) <> 0 then	
	if p_qty='' then 
		p_qty='1';
	end if;
		
   if exists (select 1 from pos_mproduct where isactived='0' and sku like p_sku) then 
		   o_message='Item ini Sedang di Inventory !';
	  else
	  
	  
	  
			select into v_seqno count(*) from pos_dtempsalesline where billno like p_billno;
			if v_seqno=0 then 
			  		v_seqno=1;
			  	else
			  	   v_seqno=v_seqno+1;
			 end if;
		

			if exists(select 1 from pos_mproduct where sku like p_sku and isnosale=true) then
			   p_price=0;
			end if;
		
		
		
		    select maxqty into v_maxqty from pos_mproductdiscount where sku like p_sku and discountname like p_discountname;
			
    
			 if exists(select 1 from pos_mmember where (memberid = p_memberid or membercardno = p_memberid or nohp = p_memberid) and p_memberid <>'' ) then  
					
				     select name,memberid,membercardno,point,
				     case when to_char(dateofbirth,'DDMMYY') like to_char(now(),'DDMMYY') then true else false end
				     into v_membername,v_memberid,v_membercardno,v_memberpoint,v_isbirthday
				     from pos_mmember where (memberid = p_memberid or membercardno = p_memberid or nohp = p_memberid) and p_memberid <>'';
				    

				     if not exists (select 1 from pos_dtempbuyget where billno like p_billno and skubuy like p_sku) then
					    if exists(select 1 from pos_mproductbuyget where skubuy = p_sku and DATE(now()) between fromdate and todate) then 
					       select qtybuy into v_qtybuy from pos_mproductbuyget where skubuy = p_sku and DATE(now()) between fromdate and todate;
					       if exists(select 1 from pos_dtempsalesline where sku = p_sku and (qty + cast(p_qty as numeric)) >= v_qtybuy ) then				         
					               v_isbuyget=true;
						           INSERT INTO public.pos_dtempbuyget
									(ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, billno,skubuy, skuget,qtyget, price,discountname,status)
									select ad_mclient_key, ad_morg_key, isactived, now(), p_postby, p_postby, now(), p_billno,p_sku, skuget,qtyget, priceget,discountname,'WAIT'  from pos_mproductbuyget 
								   where skubuy = p_sku and DATE(now()) between fromdate and todate;	
							else 
							    if ( cast(p_qty as numeric) >= v_qtybuy) then
							          v_isbuyget=true;
						           INSERT INTO public.pos_dtempbuyget
									(ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, billno,skubuy, skuget,qtyget, price,discountname,status)
									select ad_mclient_key, ad_morg_key, isactived, now(), p_postby, p_postby, now(), p_billno,p_sku, skuget,qtyget, priceget,discountname,'WAIT' from pos_mproductbuyget 							
									where skubuy = p_sku and typepromo like 'Reguler' and DATE(now()) between fromdate and todate;
							
							      end if;
							     
					       end if;
					    end if;
				     end if;
				    
				 else 
				    

				     if not exists (select 1 from pos_dtempbuyget where billno like p_billno and skubuy like p_sku) then
					   if exists(select 1 from pos_mproductbuyget where skubuy = p_sku and typepromo like 'Reguler' and DATE(now()) between fromdate and todate) then 
					       select qtybuy into v_qtybuy from pos_mproductbuyget where skubuy = p_sku and typepromo like 'Reguler' and DATE(now()) between fromdate and todate;
					      
					       if exists(select 1 from pos_dtempsalesline where billno=p_billno and sku = p_sku and (qty + cast(p_qty as numeric)) >= v_qtybuy) then
					           v_isbuyget=true;
						           INSERT INTO public.pos_dtempbuyget
									(ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, billno,skubuy, skuget,qtyget, price,discountname,status)
									select ad_mclient_key, ad_morg_key, isactived, now(), p_postby, p_postby, now(), p_billno,p_sku, skuget,qtyget, priceget,discountname,'WAIT' from pos_mproductbuyget 							
									where skubuy = p_sku and typepromo like 'Reguler' and DATE(now()) between fromdate and todate;
							else 
							    if ( cast(p_qty as numeric) >= v_qtybuy) then
							          v_isbuyget=true;
						           INSERT INTO public.pos_dtempbuyget
									(ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, billno,skubuy, skuget,qtyget, price,discountname,status)
									select ad_mclient_key, ad_morg_key, isactived, now(), p_postby, p_postby, now(), p_billno,p_sku, skuget,qtyget, priceget,discountname,'WAIT' from pos_mproductbuyget 							
									where skubuy = p_sku and typepromo like 'Reguler' and DATE(now()) between fromdate and todate;
							
							      end if;
								
					       end if;
					      
					    end if;
					  end if;
				      
			 end if;
			
		

				if exists(select 1 from ad_morg where isqty=true ) then			
				   select SUM(qty) into v_qtysale from pos_dtempsalesline where billno like p_billno and sku like p_sku ;
				   v_qtysale=coalesce(v_qtysale,0) + cast(p_qty as numeric);
	 
			   		if exists(select 1 from pos_mproduct where sku like p_sku and (stockqty - v_qtysale) < 0 and not left(p_sku,3)='839' and not sku='8320100000139') then
							o_message='Tidak Ada Stok atau Stok Kurang !!';
					else
		  
					    

							 if exists (select 1 from pos_mproductdiscountmurah where sku like p_sku) then
										if not exists(select 1 from pos_dsales where billno like p_billno and ispromomurah=true) then
										  select pricediscount,limitamount into v_pricediscount,v_limitamount  
										  from pos_mproductdiscountmurah where sku like p_sku;
										 
										  if exists(select 1 from pos_dsales where billno like p_billno and billamount >= v_limitamount) then
										    v_ismurah=true;
										  end if;
										 
										end if;
							 end if;

							
							v_discount_1=p_discount;
							v_discount_name=p_discountname;
							if exists (select 1 from pos_mproductdiscountgrosir_new where sku like p_sku and DATE(now()) between fromdate and todate) then
							
									select discount, discountname into v_discount_1, v_discount_name  from pos_mproductdiscountgrosir_new where sku like p_sku and DATE(now()) between fromdate and todate and 
									cast(v_qtysale as numeric) >= minbuy order by minbuy desc limit 1;
								
							end if;
						  
							
							if (v_ismurah=true) then			
								     if (cast(p_qty as numeric)=1) then
								       insert into pos_dtempsalesline(isactived,insertby,insertdate,postby,postdate,ad_mclient_key,ad_morg_key,pos_mcashier_key,ad_muser_key,billno,seqno,sku,qty,price,discount,discountname ,memberid,membername,isbirthday,memberpoint,membercardno,membertext ,status,ispromomurah)
									   values('1',p_postby,now(),p_postby,now(),p_ad_mclient_key,p_ad_morg_key,p_pos_mcashier_key,p_ad_muser_key,p_billno,v_seqno,p_sku,
									   1,v_discount_1,0,v_discount_name,
									  v_memberid,v_membername,v_isbirthday,v_memberpoint,v_membercardno,p_memberid,'WAITING',true);
											 	
										o_message='Success';
									 else
									   o_message='Item Tebus Murah Tidak boleh lebih dari 1 !'; --OK
									 
									 end if;			    			
							else		    					
								    if not exists (select 1 from pos_dtempsalesline where billno like p_billno and sku like p_sku and price=cast(replace(p_price,',','') as numeric)) then
								    

								        insert into pos_dtempsalesline(isactived,insertby,insertdate,postby,postdate,ad_mclient_key,ad_morg_key,pos_mcashier_key,ad_muser_key,billno,seqno,sku,qty,price,discount,discountname ,memberid,membername,isbirthday,memberpoint,membercardno,membertext,status)
										values('1',p_postby,now(),p_postby,now(),p_ad_mclient_key,p_ad_morg_key,p_pos_mcashier_key,p_ad_muser_key,p_billno,v_seqno,p_sku,cast(p_qty as numeric),
													   cast(replace(p_price,',','') as numeric),COALESCE(cast(v_discount_1 as numeric), 0),v_discount_name,v_memberid,v_membername,v_isbirthday,v_memberpoint,v_membercardno,p_memberid ,'WAITING');
																	 	
										o_message='Success';
											
									else 
									     update pos_dtempsalesline set
														    qty=qty+cast(p_qty as numeric),						        
														    memberid=v_memberid,
															membername =v_membername,
															membercardno=v_membercardno,
															memberpoint =v_memberpoint,
															isbirthday=v_isbirthday,
															membertext=p_memberid,
															discount=COALESCE(cast(v_discount_1 as numeric), 0)
															
										 where billno like p_billno and sku like p_sku and price=cast(replace(p_price,',','') as numeric) ;	
										
										 o_message='Success';
									end if;	
								
							end if;
						
						
						
		
						end if;	
				else
				
					select SUM(qty) into v_qtysale from pos_dtempsalesline where billno like p_billno and sku like p_sku ;
				    v_qtysale=coalesce(v_qtysale,0) + cast(p_qty as numeric);

							 if exists (select 1 from pos_mproductdiscountmurah where sku like p_sku) then
										if not exists(select 1 from pos_dsales where billno like p_billno and ispromomurah=true) then
										  select pricediscount,limitamount into v_pricediscount,v_limitamount  from pos_mproductdiscountmurah where sku like p_sku;
										  if exists(select 1 from pos_dsales where billno like p_billno and billamount >= v_limitamount) then
										    v_ismurah=true;
										  end if;
										end if;
							 end if;

	
	
	
							v_discount_1=p_discount;
							v_discount_name=p_discountname;
							
							if exists (select 1 from pos_mproductdiscountgrosir_new where sku like p_sku and DATE(now()) between fromdate and todate) then
									
									select discount, discountname into v_discount_1, v_discount_name  from pos_mproductdiscountgrosir_new where sku like p_sku and DATE(now()) between fromdate and todate and 
									cast(v_qtysale as numeric) >= minbuy order by minbuy desc limit 1;
							           		             
								
							end if;
							

								if (v_ismurah=true) then			
									     if (cast(p_qty as numeric)=1) then
									       insert into pos_dtempsalesline(isactived,insertby,insertdate,postby,postdate,ad_mclient_key,ad_morg_key,pos_mcashier_key,ad_muser_key,billno,seqno,sku,qty,price,discount,discountname ,memberid,membername,isbirthday,memberpoint,membercardno,membertext ,status,ispromomurah)
										   values('1',p_postby,now(),p_postby,now(),p_ad_mclient_key,p_ad_morg_key,p_pos_mcashier_key,p_ad_muser_key,p_billno,v_seqno,p_sku,
										   1,v_discount_1,0,v_discount_name,
										  v_memberid,v_membername,v_isbirthday,v_memberpoint,v_membercardno,p_memberid,'WAITING',true);
												 	
											o_message='Success';
										 else
										   o_message='Item Tebus Murah Tidak boleh lebih dari 1 !'; --OK
										 
										 end if;			    			
								else		    					
									    if not exists (select 1 from pos_dtempsalesline where billno like p_billno and sku like p_sku and price=cast(replace(p_price,',','') as numeric)) then
									    
									        insert into pos_dtempsalesline(isactived,insertby,insertdate,postby,postdate,ad_mclient_key,ad_morg_key,pos_mcashier_key,ad_muser_key,billno,seqno,sku,qty,price,discount,discountname ,memberid,membername,isbirthday,memberpoint,membercardno,membertext,status)
											values('1',p_postby,now(),p_postby,now(),p_ad_mclient_key,p_ad_morg_key,p_pos_mcashier_key,p_ad_muser_key,p_billno,v_seqno,p_sku,cast(p_qty as numeric),
														   cast(replace(p_price,',','') as numeric),COALESCE(cast(v_discount_1 as numeric), 0),v_discount_name,v_memberid,v_membername,v_isbirthday,v_memberpoint,v_membercardno,p_memberid ,'WAITING');
																		 	
											o_message='Success';
												
										else 
										     update pos_dtempsalesline set
															    qty=qty+cast(p_qty as numeric),						        
															    memberid=v_memberid,
																membername =v_membername,
																membercardno=v_membercardno,
																memberpoint =v_memberpoint,
																isbirthday=v_isbirthday,
																membertext=p_memberid,
																discount=COALESCE(cast(v_discount_1 as numeric), 0),
																discountname=v_discount_name
											 where billno like p_billno and sku like p_sku and price=cast(replace(p_price,',','') as numeric) ;	
											
											 o_message='Success';
										end if;
																	 		
								end if;
								

					end if;
				end if;
 	  	

 	  	if (v_isbuyget=true) then
	 	  	if exists(select 1 from pos_dtempbuyget where billno like p_billno and price =0 and status like 'WAIT') then 
	 	  	
				 insert into pos_dtempsalesline(isactived,insertby,insertdate,postby,postdate,ad_mclient_key,ad_morg_key,pos_mcashier_key,ad_muser_key,billno,seqno,sku,qty,price,discount,discountname ,memberid,membername,isbirthday,memberpoint,membercardno,membertext,status)
				 select '1',p_postby,now(),p_postby,now(),p_ad_mclient_key,p_ad_morg_key,p_pos_mcashier_key,p_ad_muser_key,p_billno,v_seqno,skuget,qtyget,
						 price,0,discountname ,v_memberid,v_membername,v_isbirthday,v_memberpoint,v_membercardno,p_memberid ,'WAITING'
				 from pos_dtempbuyget where billno like p_billno and price =0 and status like 'WAIT';
				 
				 update pos_dtempbuyget set 
				 status='CLOSE'
				 where billno like p_billno and price =0 and status like 'WAIT';
					      
			end if;
 	  	 end if;
 	  	
 	  	
 	  	
 
	 else
	 	o_message='Maaf Item ini belum bisa dijual ...';
	
	 end if;


	
select into v_total sum(qty*(price-discount)) from pos_dtempsalesline 
where billno like p_billno and status like 'WAITING' and pos_mcashier_key=p_pos_mcashier_key;
 v_strtotal='Rp. '||to_char(v_total, 'FM999,999,999');

execute 'select array_to_json(array_agg(row_to_json(t)))
      from (
  		Select $1 as lasttempvalue,$2 as lasttempstring,$3 as isbuygetshow
  		) t '
      INTO o_data
      using v_total,v_strtotal,coalesce(v_isbuyget,false);
 
END;
$function$
;


CREATE OR REPLACE FUNCTION public.proc_pos_dtempsalesline_2_update(p_pos_dtempsalesline_key character varying DEFAULT NULL::character varying, p_qty character varying DEFAULT NULL::character varying, p_approvedby character varying DEFAULT NULL::character varying, OUT o_data json, OUT o_message character varying)
 RETURNS record
 LANGUAGE plpgsql
AS $function$
  declare   v_pos_mcashier_key varchar(36);
 			v_total numeric;
	    	v_strtotal varchar;
	        v_pos_dcashierbalance_key varchar(32);
			v_discount_1 numeric;
			v_discount_name varchar(75);
			v_sku varchar;
BEGIN 

if exists(select 1 from pos_dtempsalesline where pos_dtempsalesline_key=p_pos_dtempsalesline_key and isrefund=true) then
   o_message='REFUND QTY TIDAK BISA DI EDIT !!';
else
	if exists(select 1 from pos_dtempsalesline where pos_dtempsalesline_key=p_pos_dtempsalesline_key) then
	    
	   select into v_pos_dcashierbalance_key pos_dcashierbalance_key from pos_dcashierbalance a inner join 
	   pos_dtempsalesline b on a.pos_mcashier_key=b.pos_mcashier_key
	   where b.pos_dtempsalesline_key=p_pos_dtempsalesline_key and a.status like 'RUNNING';
		
	
	    INSERT INTO public.pos_dsalesqtylog
		(isactived, insertdate, insertby, postby, postdate,ad_mclient_key,ad_morg_key,ad_muser_key, sku, oldqty, newqty, billno, approvedby, pos_dcashierbalance_key)
		select isactived, insertdate, insertby, postby, postdate, ad_mclient_key,ad_morg_key,ad_muser_key, sku, qty, cast(p_qty as numeric),billno, p_approvedby,v_pos_dcashierbalance_key  
		from pos_dtempsalesline 
		where pos_dtempsalesline_key=p_pos_dtempsalesline_key;
		
		

		select sku, discount into v_sku, v_discount_1 from pos_dtempsalesline where pos_dtempsalesline_key=p_pos_dtempsalesline_key;				
		if exists (select 1 from pos_mproductdiscountgrosir_new where sku like v_sku and DATE(now()) between fromdate and todate) then
				v_discount_1 = 0;
				v_discount_name = '';
				
				select discount, discountname into v_discount_1, v_discount_name  from pos_mproductdiscountgrosir_new where sku like v_sku and DATE(now()) between fromdate and todate and 
				cast(p_qty as numeric) >= minbuy order by minbuy desc limit 1;
				
				update pos_dtempsalesline set
				qty=cast(p_qty as numeric),
				discount=COALESCE(cast(v_discount_1 as numeric), 0),
				discountname=v_discount_name
				where pos_dtempsalesline_key=p_pos_dtempsalesline_key;
		else 
				update pos_dtempsalesline set
				qty=cast(p_qty as numeric)
				where pos_dtempsalesline_key=p_pos_dtempsalesline_key;
		end if;


		
	
	
	    o_message='success';
	else
	   o_message='fail';
	end if;
	
	
	select pos_mcashier_key into v_pos_mcashier_key from pos_dtempsalesline where pos_dtempsalesline_key=p_pos_dtempsalesline_key;
	
  select into v_total sum(qty*(price-coalesce(discount,0))) from pos_dtempsalesline where pos_mcashier_key=v_pos_mcashier_key;
	 v_strtotal='Rp. '||to_char(v_total, 'FM999,999,999');
	
	execute 'select array_to_json(array_agg(row_to_json(t)))
	      from (
	  		Select $1 as lasttempvalue,$2 as lasttempstring
	  		) t '
	      INTO o_data
	      using v_total,v_strtotal;
 end if;
END;
$function$
;
"

];

foreach ($cmd_grosir as $r){
	$connec->exec($r);
}


header("Location: ../pigantung.php");