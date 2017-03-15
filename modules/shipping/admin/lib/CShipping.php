<?php
class CShipping implements IShippingCore{
	protected static $tbl_name = "module_shipping";
	
	static function Calculate(array $args, $method="local"){
		try {
			$res = CModule::LinkComponent('shipping', $method);
			if($res['status']){
				if(!isset($args['is_handled']))$args['is_handled'] = false;
				$ret = $res['result']::Calculate($args);
				return $ret;
			}
			throw new Exception('Error: no such shipping method defined in system',1);
		}
		catch (Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage()
			];
		}
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
	static function AddShipping(array $args, $order_id, $method="local"){
		try {
			$res = CModule::LinkComponent('shipping', $method);
			if($res['status']){
				self::VerifySentToHandling($args);
				//Verify and set shipping_to field for componnet
				if(!$args['is_handled']){
					
					$shipping_to = self::GetLastInTree($args);
					$args['shipping_to'] = $shipping_to;
				}
				$ret = $res['result']::AddShipping($args, $order_id);
				return $ret;
			}
			throw new Exception('Error: no such shipping method defined in system',1);
		}
		catch (Exception $error){
			return [
					'status'=>0,
					'result'=>$error->getMessage()
			];
		}
	}
	
	static function ConfirmShipping($shipping_id){
		try {
			$res = self::GetShipping($shipping_id);
			if($res['status']){
				$method = $res['result']['shipping_method'];
				$is_connected = CModule::LinkComponent('shipping', $method);
				if($is_connected['status']){
					return $is_connected['result']::ConfirmShipping($shipping_id);
				}
				throw new Exception('Error: no such shipping method was finded in system');
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
				$method = $res['result']['shipping_method'];
				$is_connected = CModule::LinkComponent('shipping', $method);
				if($is_connected['status']){
					return $is_connected['result']::CancelShipping($shipping_id);
				}
				throw new Exception('Error: no such shipping method was finded in system');
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
	
	static function RemoveShippingByOrder($order_id){
		try {
			Cmwdb::$db->where('order_id', $order_id);
			if(Cmwdb::$db->delete(self::$tbl_name)){
				return [
						'status'=>1,
						'result'=>"",
						
				];
			}
			throw new Exception('Error: cant update status in table');
		}
		catch (Exception $error){
			return [
					'status'=>0,
					'result'=>$error->getMessage(),
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
		try {
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
		catch (Exception $error){
			return [
					'status'=>0,
					'result'=>$error->getMessage()
			];
		}	
	}
	
	static protected function VerifySentToHandling(&$args){
		$is_handling = true;
		if(isset($args['shipping_cart']['country']) && $args['shipping_cart']['country'])$is_handling = false;
		if(isset($args['shipping_cart']['state']) && $args['shipping_cart']['state'])$is_handling = false;
		if(isset($args['shipping_cart']['city']) && $args['shipping_cart']['city'])$is_handling = false;
		if(isset($args['shipping_cart']['community']) && $args['shipping_cart']['community'])$is_handling = false;
		$args['is_handled'] = $is_handling;
	}
	
	static protected function GetLastInTree($args){
		$tree_id = 0;
		if(isset($args['shipping_cart']['country']) && $args['shipping_cart']['country'])$tree_id = $args['shipping_cart']['country'];
		if(isset($args['shipping_cart']['state']) && $args['shipping_cart']['state'])$tree_id = $args['shipping_cart']['state'];
		if(isset($args['shipping_cart']['city']) && $args['shipping_cart']['city'])$tree_id = $args['shipping_cart']['city'];
		if(isset($args['shipping_cart']['community']) && $args['shipping_cart']['community'])$tree_id = $args['shipping_cart']['community'];
		return $tree_id;
	}
	
	static function DeleteByOrder(array $order_ids){
		try {
			Cmwdb::$db->where('order_id', $order_ids, "in");
			
			if(Cmwdb::$db->delete(self::$tbl_name)){
				return [
					'status'=>1,
					'result'=>""	
				];
			}
			throw new Exception("Error: cant delete shippings");
		}
		catch (Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage()	
			];
		}
	}
}
?>