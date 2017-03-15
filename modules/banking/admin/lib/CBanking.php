<?php

class CBanking{
//	static protected $tbl_name = "module_banking_conteyer";
	static protected $tbl_name = "module_banking";
	static protected $config = [];
	
	static function AddTransaction($order_id, $args, $method){
		try {
			if(!self::Initial())
				throw new Exception("Error: invalid configuration for banking, usualy cant continue process");
			$component_exists = CModule::LinkComponent('banking', $method);
			if($component_exists['status']){
				$component_answer = $component_exists['result']::AddTransaction($order_id, $args, self::$tbl_name, self::$config[$method], self::$config['config']);
				return [
					'status'=>1,
					'result'=>$component_answer	
				];
			}
			throw new Exception("Error: ".$method." component does not exists");
		}
		catch (Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage()	
			];
		}
	}
	
	static function StartTransaction($order_id){
		try {
			if(!self::Initial())
				throw new Exception("Error: invalid configuration for banking, usualy cant continue process");
			Cmwdb::$db->where('order_id', $order_id);
			$current_transaction = Cmwdb::$db->getOne(self::$tbl_name, ['banking_id','banking_method']);
			if(empty($current_transaction))throw new Exception('Error: invalid or missing order id');
			$banking_component = CModule::LinkComponent('banking', $current_transaction['banking_method']);
			if($banking_component['status']){
				$component_answer = $banking_component['result']::StartTransaction(
						$current_transaction['banking_id'],
						self::$config['config'],
						self::$config[$current_transaction['banking_method']],
						self::$tbl_name
						);
				return $component_answer;
			}
			return $current_transaction;
			
		}
		catch (Exception $error){
			return [
					'status'=>0,
					'result'=>$error->getMessage()
			];
		}
	}
	
	static protected function Initial(){
		$res = CConfig::GetBlock('config', 'CBanking');
		if($res!==CONFIG_NO_ENTRY){
			self::$config = $res;
			self::$config['config']['back_url'] = URL_BASE.self::$config['config']['back_url'];
			return true;
		}
		return false;
	}
	
/*	
	static function GetBankingDatas($buid, $type){
		try {
			Cmwdb::$db->where('buid', $buid);
			Cmwdb::$db->where('utype', $type);
			$res = Cmwdb::$db->getOne(self::$tbl_name);
			if(empty($res))throw new Exception('No such banking account', 1);
			$ret = array();
			$ret['conteyer'] = json_decode($res['b_datas'], true);
			$decode_key = $res['b_key'];
			foreach ($ret['conteyer'] as $index=>$values){
				//Now we need walk on array $values and decode all content,
				//that encoded previusly
				foreach ($values as $unit_key=>$unit){
					$values[$unit_key] = substr($unit, $decode_key);
				}
				$ret['conteyer'][$index] = $values;
			}
			$ret['bid'] = $res['bid'];
			$ret['buid'] = $res['buid'];
			$ret['utype'] = $res['utype'];
			return ['status'=>1, 'result'=>$ret];
		}
		catch (Exception $error){
			return ['status'=>0, 'result'=>$error->getMessage()];
		}
		
	}
	
	static function AddBankingDatas($id, $type, $datas){
		try {
			Cmwdb::$db->where('buid', $id);
			Cmwdb::$db->where('utype', $type);
			if(Cmwdb::$db->getValue(self::$tbl_name, 'bid'))throw new Exception('The cart exists, cant add exists cart');
			$decode_key = rand(1,9);
			$prefix = "";
			foreach ($datas as $index=>$values){
				//Starting encode datas using decode_key as base for it
				foreach ($values as $unit_key=>$unit){
					//Generate perfix for units
					for($i=0;$i<$decode_key;$i++)
						$prefix.=rand(1,9);
					$values[$unit_key] = $prefix.$unit;
					$prefix = "";
				}
				$datas[$index] = $values;
			}
			$queryData = array();
			$queryData['buid'] = $id;
			$queryData['utype'] = $type;
			$queryData['b_datas'] = json_encode($datas);
			$queryData['b_key'] = $decode_key;
			if(Cmwdb::$db->insert(self::$tbl_name, $queryData)){
				return ['status'=>1, 'result'=>Cmwdb::$db->getInsertId()];
			}
			throw new Exception('Error, cant to store in table');
				
		}
		catch (Exception $error){
			return ['status'=>0, 'result'=>$error->getMessage()];
		}
	}
	
*/	
}

?>