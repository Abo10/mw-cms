<?php

class CArca{
	
	static function AddTransaction($order_id, $args, $tbl_name, $component_config, $banking_config){
		$queryDatas = [];
		$queryDatas['order_id'] = $order_id;
		$queryDatas['transaction_status'] = 0;
		$queryDatas['transaction_date'] = date('Y/m/d H:i:s');
		$queryDatas['banking_method'] = "arca";
		$banking_datas = [];
		if(isset($args['amount']))$banking_datas['AMOUNT'] = $args['amount']*100;
		else throw new Exception('Error: Missing amount for transaction');
		if(isset($args['order_description']))$banking_datas['ORDERDESCRIPTION'] = $args['order_description'];
		else throw new Exception('Error: Missing description for transaction');
		$banking_datas['ORDERNUMBER']=$order_id;
		$banking_datas['BACKURL']=$banking_config['back_url'];
		$banking_datas['LANGUAGE']='EN';
		$banking_datas['DEPOSITFLAG']=1;
		$banking_datas['MODE']=1;
		$queryDatas['banking_datas'] = json_encode($banking_datas);
		if(Cmwdb::$db->insert($tbl_name, $queryDatas))
			return Cmwdb::$db->getInsertId();
		throw new Exception("Error: Cant create transaction, failed to insert into db");
	}
	
	static function StartTransaction($transaction_id, $banking_config, $component_config, $tbl_name){
		Cmwdb::$db->where('banking_id', $transaction_id);
		$banking_datas = Cmwdb::$db->getValue($tbl_name, 'banking_datas');
		$banking_datas = json_decode($banking_datas, true);
		var_dump($banking_datas);
		
	}
}