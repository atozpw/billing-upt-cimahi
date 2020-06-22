<?php
	require "model/setDB.php";
	$db		= false;
	try {
		$db 	= new PDO($PSPDO[0],$PSPDO[1],$PSPDO[2]);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		unset($mess);
	}
	catch (PDOException $err){
		$mess = $err->getTrace();
		errorLog::errorDB(array($mess[0]['args'][0]));
		$mess = "Mungkin telah terjadi kesalahan pada database server, sehingga koneksi tidak bisa dilakukan. Tekan tombol <b>Esc</b> untuk menutup pesan ini";
		$klas = "error";
	}
	
	$msql 	= mssql_connect("merauke_agresso","agresso","agresso");
	mssql_select_db('Agresso', $msql);
	// $que0	= "SELECT client, apar_id, apar_name, apar_gr_id, description, ext_apar_ref, address, RT, RW, Desa_Keluruhan, Kecematan, zip_code, Contact, telephone_1, factor_short, Cash_counter, CONVERT(VARCHAR(19), last_update, 120) AS last_update, user_id, property_id, property_description, route, sequence, CONVERT(VARCHAR(19), date_from, 120) AS date_from, meter_id, Meter_type, CONVERT(VARCHAR(19), instal_date, 120) AS instal_date, digit_no, product_id, prod_year, CONVERT(VARCHAR(19), Last_reading, 120) AS Last_reading, Meter_reading, Position FROM dbo.uviwaterconsumer WHERE client='WI' AND apar_id<99999999 ORDER BY apar_id";
	// $que0	= "SELECT account, allocation_key, amend_no, amount, amount_set, apar_id, apar_id2, apar_type, apar_type2, arr_amount, arr_val, art_descr, art_gr_id, article, article_id, att_1_id, att_2_id, att_3_id, att_4_id, att_5_id, att_6_id, att_7_id, back_flag, back_value, bb_deliv_addr, bonus_type, break_flag, client, com_amount, CONVERT(VARCHAR(19), com_del_date, 120) AS com_del_date, com_val, cost_amount, cur_amount, currency, CONVERT(VARCHAR(19), deliv_date, 120) AS deliv_date, delivery_descr, delivery_flag, dim_1, dim_2, dim_3, dim_4, dim_5, dim_6, dim_7, disc_code, disc_percent, disc_type, discount, exch_rate, flag, forecast, fund_chk, guarantee, invoiced, kit_type, CONVERT(VARCHAR(19), last_update, 120) AS last_update, line_no, line_no2, location, lot, main_unit, multiplier, old_forecast, open_flag, order_discount, order_id, order_id2, period, price_gr, print_flag, print_status, priority, real_amount, reserved_val, ret_amount, ret_val, CONVERT(VARCHAR(19), rev_del_date, 120) AS rev_del_date, rev_price, rev_status, rev_val, sales_amt, sequence_no, serial_no, status, struct_flag, sup_article, tax_amount, tax_code, tax_cur_amt, tax_percent, tax_system, temp_code, template_id, test_amount, test_val, unit_code, unit_descr, unit_price, user_id, value_1, voucher_no, voucher_type, vow_amount, vow_val, warehouse, agrtid FROM dbo.asodetail WHERE period=201507";
	// $que0	= "SELECT * FROM dbo.acuhistr WHERE period=201507 ORDER BY payment_date DESC";
	$que0	= "SELECT * FROM dbo.ubtasodetail WHERE RIGHT(CONVERT(VARCHAR(10), readdate, 105), 7) LIKE '%-2015'";
	$res0	= mssql_query($que0);
	while($data = mssql_fetch_assoc($res0)){
		$keys 	= array_keys($data);
		$fields = '`'.implode('`, `',$keys).'`';
		$placeholder = substr(str_repeat('?,',count($keys)),0,-1);
		$db->prepare("INSERT INTO `temp_wedu`.`ubtasodetail`(".$fields.") VALUES(".$placeholder.")")->execute(array_values($data));
	}
?>
