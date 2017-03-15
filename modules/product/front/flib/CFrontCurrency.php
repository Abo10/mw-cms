<?php

class CFrontCurrency{
	static protected $configs = array();
	static $currencies = array();
	function __construct(){
		self::$currencies = CConfig::GetBlock(null, 'CCurrency');
		self::$configs['web_module_dir'] = CConfig::GetKey('web_module_dir');
		self::$configs['currency_default'] = CConfig::GetKey('currency_default');
		self::$configs['rank_currency'] = CConfig::GetKey('rank_currency');
		
// 		echo '<pre>';
// 		echo "Currencies is: <br>";
// 		var_dump(self::$configs);
// 		echo '<hr>';
// 		foreach (self::$configs as $key=>$value)
// 			echo $key.' : '.$value.'<br>';
// 		echo '</pre>';
	}
	
	static function GetPrice($price, $price_currency, $need_currency){
		self::$currencies = CConfig::GetBlock(null, 'CCurrency');
		self::$configs['web_module_dir'] = CConfig::GetKey('web_module_dir');
		self::$configs['currency_default'] = CConfig::GetKey('currency_default');
		self::$configs['rank_currency'] = CConfig::GetKey('rank_currency');
		if(isset(self::$currencies[$price_currency]) && isset(self::$currencies[$need_currency])){
			$cur_rank = self::$currencies[$price_currency]['rank'];
			if($need_currency===self::$configs['rank_currency']){
				return round($price/$cur_rank,2);
			}
			$cur_price = $price/$cur_rank;
			return round(self::$currencies[$need_currency]['rank']*$cur_price, 2);
		}
		return false;
	}
	
	static function GetDefaultCurrency(){
		$config = CConfig::GetKey('currency_default');
		return $config;
	}
	
	static function GetCurrentCurrency(){
		if(isset($_SESSION['config']['currency']))
			return $_SESSION['config']['currency'];
		$_SESSION['config']['currency'] = CConfig::GetKey('currency_default');
		return $_SESSION['config']['currency'];
	}
	
	static function SetCurrentCurrency($currency=null){
		if(!$currency)$currency = CConfig::GetKey('currency_default');
		$currencies = CConfig::GetBlock(null, 'CCurrency');
		if(!array_key_exists($currency, $currencies))
			return self::GetCurrentCurrency();
		$_SESSION['config']['currency'] = $currency;
		return $currency;
	}
	
	static function GetAllCurrencies(){
		self::$currencies = CConfig::GetBlock(null, 'CCurrency');
		self::$configs['web_module_dir'] = CConfig::GetKey('web_module_dir');
		self::$configs['currency_default'] = CConfig::GetKey('currency_default');
		self::$configs['rank_currency'] = CConfig::GetKey('rank_currency');
		return self::$currencies;
	}
	
	static function GetCurrency($currency){
		self::$currencies = CConfig::GetBlock(null, 'CCurrency');
		self::$configs['web_module_dir'] = CConfig::GetKey('web_module_dir');
		self::$configs['currency_default'] = CConfig::GetKey('currency_default');
		self::$configs['rank_currency'] = CConfig::GetKey('rank_currency');
		if(isset(self::$currencies[$currency]))
			return self::$currencies[$currency];
		return false;
	}
	
	static function GetSymbol($currency=null){
		
		self::$currencies = CConfig::GetBlock(null, 'CCurrency');
		self::$configs['web_module_dir'] = CConfig::GetKey('web_module_dir');
		self::$configs['currency_default'] = CConfig::GetKey('currency_default');
		self::$configs['rank_currency'] = CConfig::GetKey('rank_currency');
		if(!$currency)$currency = self::GetCurrentCurrency();
		if(isset(self::$currencies[$currency])){
			return self::$currencies[$currency]['symbol'];
		}
		return false;
	}

	static function GetDesc($currency){
		self::$currencies = CConfig::GetBlock(null, 'CCurrency');
		self::$configs['web_module_dir'] = CConfig::GetKey('web_module_dir');
		self::$configs['currency_default'] = CConfig::GetKey('currency_default');
		self::$configs['rank_currency'] = CConfig::GetKey('rank_currency');
		$ret = self::GetCurrency($currency);
		if($ret)return $ret['desc'];
		return false;
	}
	
	static function GetImg($currency){
		self::$currencies = CConfig::GetBlock(null, 'CCurrency');
		self::$configs['web_module_dir'] = CConfig::GetKey('web_module_dir');
		self::$configs['currency_default'] = CConfig::GetKey('currency_default');
		self::$configs['rank_currency'] = CConfig::GetKey('rank_currency');
		$ret = self::GetCurrency($currency);
		if($ret)return $ret['icon'];
		return false;
	}
	
	static function GetRank($currency){
		self::$currencies = CConfig::GetBlock(null, 'CCurrency');
		self::$configs['web_module_dir'] = CConfig::GetKey('web_module_dir');
		self::$configs['currency_default'] = CConfig::GetKey('currency_default');
		self::$configs['rank_currency'] = CConfig::GetKey('rank_currency');
		$ret = self::GetCurrency($currency);
		if($ret)return $ret['rank'];
		return false;
	}
	
	
	
}
?>