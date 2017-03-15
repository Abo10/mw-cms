<?php
class CLocalShipping implements IShipping{
	protected static $tbl_name = "module_shipping";
	
	static function Calculate(array $args){
		//Generate error, if not defined where must go product
		$ret = ['status'=>1, 'result'=>0];
		if(!$args['is_handled']){
			if(!isset($args['shipping_to']))
				throw new Exception('Error: no shipping address defiend');
	// 		if(!isset($args['quantity']))
	// 			throw new Exception('Error: no quantity defiend');
			if(!CModule::HasModule('addressing'))
				throw new Exception('Error: address list not linked to system');
			CModule::LinkModule('addressing');
			$res = CAddressing::GetUnit($args['shipping_to']);
			if(!$res['status'])throw new Exception($res['result']);
			$ret['status'] = 1;
			$ret['result']=['price'=>$res['result']['addr_price']];
			return $ret;
				
		}
		return $ret;
	}
	
	/*
	 * @arry args - all fields, that needed for this api to create shipping
	 * @order_id - Connected order in checkout
	 * 
	 * Status - 0: Shipping just created(function AddShipping)
	 * 			1: Shipping confirmed(function ConfirmShipping)
	 * 			2: Products on the way
	 * 			3: Shipping canceled(function CancelShipping)
	 * 			4: Shipping removed(function RemoveShipping)
	 * 			5: Shipping complated sucsesful
	 */
	static function AddShipping(array $args, $order_id){
		Cmwdb::$db->where('order_id', $order_id);
		//In shipping can't exists 2 shipping request with some order_id, its error
		//So for first we must verify it
		if(Cmwdb::$db->getValue(self::$tbl_name, 'order_id'))
			throw new Exception('Error: Order was fount in list, cant create shipping');
		$res = self::Calculate($args);
// 		if(!isset($args['shipping_to']))$args['shipping_to'] = 0;
		if(!$res['status'])return $res;//Something wrong and colculation returned error, operation must be terminated
		$args['calculated_price'] = $res['result']['price'];
		$queryData = array();
		$queryData['order_id'] = $order_id;
		$queryData['shipping_details'] = json_encode($args);
		$queryData['shipping_status'] = 0;
		$queryData['addr_from'] = 0;
		$queryData['addr_to'] = $args['shipping_to'];
		$queryData['shipping_method'] = 'local';
		if(Cmwdb::$db->insert(self::$tbl_name, $queryData)){
			return [
				'status'=>1,
				'result'=>[
					'shipping_id'=>Cmwdb::$db->getInsertId(),
					'shipping_price'=>$args['calculated_price']	
				]
			];
		}
		throw new Exception('Error: Cant insert into table');
	}
	
	static function ConfirmShipping($shipping_id){
		try {
			$res = self::GetShipping($shipping_id);
			if($res['status']){
				Cmwdb::$db->where('shipping_id', $shipping_id);
				if(Cmwdb::$db->update(self::$tbl_name, ['shipping_status'=>1])){
					return [
							'status'=>1,
							'result'=>""
					];
				}
				throw new Exception('Error: cant update status in table');
			}
			return $res;
		}
		catch (Exception $error){
			return [
					'status'=>0,
					'result'=>$error->getMessage()
			];
		}
		
	}
	
	static function CancelShipping($shipping_id){
		try {
			$res = self::GetShipping($shipping_id);
			if($res['status']){
				Cmwdb::$db->where('shipping_id', $shipping_id);
				if(Cmwdb::$db->update(self::$tbl_name, ['shipping_status'=>3])){
					return [
							'status'=>1,
							'result'=>""
					];
				}
				throw new Exception('Error: cant update status in table');
			}
			return $res;
		}
		catch (Exception $error){
			return [
					'status'=>0,
					'result'=>$error->getMessage()
			];
		}
	}
	
	static function RemoveShipping($shipping_id){
		try {
			$res = self::GetStatus($shipping_id);
			if($res['status']){
				Cmwdb::$db->where('shipping_id', $shipping_id);
				if(Cmwdb::$db->update(self::$tbl_name, ['shipping_status'=>4])){
					return [
							'status'=>1,
							'result'=>""
					];
				}
				throw new Exception('Error: cant update status in table');
			}
			return $res;
		}
		catch (Exception $error){
			return [
					'status'=>0,
					'result'=>$error->getMessage()
			];
		}		
	}
	
	static function GetStatus($shipping_id){
		try {
			Cmwdb::$db->where('shipping_id',$shipping_id);
			$res = Cmwdb::$db->getValue(self::$tbl_name, 'shipping_status');
			if($res){
				return [
						'status'=>1,
						'result'=>$res
				];
			}
			throw new Exception('Error: no such shipping id was founded');
		}
		catch (Exception $error){
			return [
					'status'=>0,
					'result'=>$error->getMessage()
			];
		}	
	}
	
	static function GetShipping($shipping_id){
		Cmwdb::$db->where('shipping_id',$shipping_id);
		$res = Cmwdb::$db->getOne(self::$tbl_name);
		if($res){
			$res['shipping_details'] = json_decode($res['shipping_details'], true);
			return [
				'status'=>1,
				'result'=>$res	
			];
		}
		throw new Exception('Error: no such shipping id was founded');
	}
}
?>