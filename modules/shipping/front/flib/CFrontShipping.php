<?php

class CFrontShipping{
	static protected $tbl_shippings = "module_shipping";
	
	static function GetShipping($order_id){
		try {
			Cmwdb::$db->where('order_id', $order_id);
			$res = Cmwdb::$db->getOne(self::$tbl_shippings);
			if($res){
				$res['shipping_details'] = json_decode($res['shipping_details'], true);
// 				if(isset($res['shipping_details']["shipping_cart"]['shipping_datas']))
// 					$res['shipping_details']["shipping_cart"]['shipping_datas'] = json_decode($res['shipping_details']["shipping_cart"]['shipping_datas'], true);
// 				if(isset($res['shipping_details']["shipping_cart"]['billing_datas']))
// 					$res['shipping_details']["shipping_cart"]['billing_datas'] = json_decode($res['shipping_details']["shipping_cart"]['billing_datas'], true);
				if(isset($res['shipping_details']["shipping_cart"]['b_handles']))
					$res['shipping_details']["shipping_cart"]['b_handles'] = json_decode($res['shipping_details']["shipping_cart"]['b_handles'], true);
				return [
					'status'=>1,
					'result'=>$res	
				];
			}
			throw new Exception('Error: invalid order id');
		}
		catch (Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage()	
			];
		}
	}
	
	static function GetListOfShippings(array $orders){
		try {
			Cmwdb::$db->where('order_id', $orders, "in");
			$res = Cmwdb::$db->get(self::$tbl_shippings);
			if($res){
				$ret = [];
				foreach ($res as $values){
					$ret[$values['order_id']] = $values;
// 					var_dump($values);
// 					echo '<hr>';continue;
					$ret[$values['shipping_details']] = json_decode($values['shipping_details'], true);
					if(isset($values['shipping_cart']))$ret[$values['shipping_cart']] = json_decode($values['shipping_cart'], true);
				}
				return [
						'status'=>1,
						'result'=>$ret
				];
			}
			throw new Exception('Error: invalid order id');
		}
		catch (Exception $error){
			return [
					'status'=>0,
					'result'=>$error->getMessage()
			];
		}
		
	}
}