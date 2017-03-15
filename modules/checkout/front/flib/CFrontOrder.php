<?php
class CFrontOrder{
	static protected $tbl_orders = "module_product_order_details";
	static protected $tbl_orders_products = "module_product_order_products";
	static protected $tbl_billing_shipping = "module_order_billing_shipping";
	static protected $tbl_addressing_bridge = "module_order_addressing_bridge";
	
	
	static function GetOrder($order_id, $lang = null){
		try {
			if(!$lang)$lang=CLanguage::getInstance()->getDefaultUser();
			Cmwdb::$db->where('id', $order_id);
			$simple_order = Cmwdb::$db->getOne(self::$tbl_orders);
			Cmwdb::$db->where('order_id', $order_id);
			$order_products = Cmwdb::$db->get(self::$tbl_orders_products);
			$tmp = [];
			if(!empty($order_products)){
				foreach ($order_products as $values)
					$tmp[$values['product_id']] = $values;
				$order_products = $tmp;
			}
				
			$order_shipping = CFrontShipping::GetShipping($order_id);
			CModule::LinkModule('checkout');
			$shipping_billing_datas = COrder::GetShipping($order_id);
			$order_address = "";
			if($shipping_billing_datas['address_ext']['country'])$order_address.=$shipping_billing_datas['address_ext']['country'].', ';
			if($shipping_billing_datas['address_ext']['state'])$order_address.=$shipping_billing_datas['address_ext']['state'].', ';
			if($shipping_billing_datas['address_ext']['city'])$order_address.=$shipping_billing_datas['address_ext']['city'].', ';
			if($shipping_billing_datas['address_ext']['address'])$order_address.=$shipping_billing_datas['address_ext']['address'].'';
			$ret = [];
			$ret['order_address'] = $order_address;
			$ret['order_details'] = $simple_order;
			$ret['order_products'] = $order_products;
			if($order_shipping['status']){
				$ret['shipping_cart'] = $order_shipping['result']['shipping_details']['shipping_cart'];
				$ret['shipping_cart']['address_ext'] = $shipping_billing_datas['address_ext'];
				$ret['shipping_ext'] = $order_shipping['result'];
			}
			if(CModule::HasModule('user')){
				CModule::LinkModule('user');
				$user_datas = CUserExt::GetUsersByID($simple_order['uid']);
				if($user_datas['status'])
					$ret['user_datas'] = $user_datas['result'][$simple_order['uid']];
			}
			$temp = [];
			$temp = COrder::GetFilteredAddressStack($shipping_billing_datas['billing_datas'], $lang);
			($temp['status'])?$ret['billing_addressing'] = $temp['result']:$ret['billing_addressing'] = [];
			$temp = COrder::GetFilteredAddressStack($shipping_billing_datas['shipping_datas'],$lang);
			($temp['status'])?$ret['shipping_addressing'] = $temp['result']:$ret['shipping_addressing']=[];
			$ret['order_price'] = $simple_order['order_price'];
			$ret['shipping_price'] = $simple_order['shipping_price'];
			$ret['calculated_price'] = $simple_order['order_price']+$simple_order['shipping_price'];
			return [
				'status'=>1,
				'result'=>$ret
			];

		}
		catch(Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage()	
			];
		}
	}
	
	static function GetOrdersList($page=1, $count=20){
		try {
			Cmwdb::$db->pageLimit = $count;
			$orders = [];
			$res = Cmwdb::$db->arrayBuilder()->paginate(self::$tbl_orders, $page, ['id']);
			if(!empty($res)){
				$tmp = null;
				foreach ($res as $vals){
					$tmp = self::GetOrder($vals['id']);
					if($tmp['status'])$orders['orders'][$vals['id']]=$tmp['result'];
				}
			}
			$orders['page_count'] = Cmwdb::$db->totalPages;
			return [
				'status'=>1,
				'result'=>$orders	
			];
			
		}
		catch (Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage()	
			];
		}
	}
}