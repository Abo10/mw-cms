<?php
 return array (
  'modules' => 
  array (
    'map' => 
    array (
      'class' => 'CMapLink',
      'front_class' => 'CFrontMap',
      'css' => 
      array (
        0 => 'style',
      ),
    ),
    'slider' => 
    array (
      'class' => 'CSlider',
      'front_class' => 'CFrontSlider',
      'css' => 
      array (
        0 => 'style',
      ),
    ),
    'user' => 
    array (
      'class' => 'CUserExt',
      'front_class' => 'CFrontUserExt',
      'css' => 
      array (
        0 => 'style',
      ),
    ),
  ),
  'config' => 
  array (
    'base_url' => 'http://localhost/ring2/',
    'theme' => 'default_theme_basic',
    'web_module_dir' => 'web/modules/',
    'currency_default' => 'amd',
    'rank_currency' => 'usd',
    'installer' => 
    array (
      'status' => true,
    ),
    'CLanguage' => 
    array (
      'lang_list' => 
      array (
        0 => 
        array (
          'key' => 'am',
          'title' => 'Հայերեն',
        ),
        1 => 
        array (
          'key' => 'en',
          'title' => 'English',
        ),
        2 => 
        array (
          'key' => 'ru',
          'title' => 'Русский',
        ),
      ),
      'user_langs' => 
      array (
        0 => 
        array (
          'key' => 'am',
          'title' => 'Հայերեն',
        ),
      ),
      'default_admin' => 'ru',
      'default_user' => 'am',
    ),
    'blo' => 
    array (
      'temp1' => 'value 1',
      'temp2' => 
      array (
      ),
    ),
    'Cmwdb' => 
    array (
      'DB_HOST' => 'localhost',
      'DB_USER' => 'root',
      'DB_PASS' => '',
      'DB_NAME' => 'ring2',
    ),
    'CCurrency' => 
    array (
      'amd' => 
      array (
        'icon' => 'res/icons/amd.png',
        'desc' => 'Armenian Dram',
        'symbol' => '֏',
        'rank' => 480,
      ),
      'usd' => 
      array (
        'icon' => 'res/icons/usd.png',
        'desc' => 'Dollar of USA',
        'symbol' => '$',
        'rank' => 1,
      ),
      'rub' => 
      array (
        'icon' => 'res/icons/rub.png',
        'desc' => 'Rubl of RF',
        'symbol' => '₽',
        'rank' => 60,
      ),
    ),
    'units' => 
    array (
      'length' => 
      array (
        1 => 
        array (
          'text' => 
          array (
            'am' => 'Սանտիմետր',
            'ru' => 'Сантиметр',
            'en' => 'Santimetr',
          ),
          'code' => 'sm',
        ),
        2 => 
        array (
          'text' => 
          array (
            'am' => 'Դեցիմետր',
            'ru' => 'Дециметр',
            'en' => 'Decimetr',
          ),
          'code' => 'dm',
        ),
        3 => 
        array (
          'text' => 
          array (
            'am' => 'Մետր',
            'ru' => 'Метр',
            'en' => 'Metr',
          ),
          'code' => 'm',
        ),
        4 => 
        array (
          'text' => 
          array (
            'am' => 'Դյում',
            'ru' => 'Дюм',
            'en' => 'Inch',
          ),
          'code' => 'inch',
        ),
      ),
      'weight' => 
      array (
        1 => 
        array (
          'text' => 
          array (
            'am' => 'Գրամ',
            'ru' => 'Грам',
            'en' => 'Gram',
          ),
          'code' => 'gr',
        ),
        2 => 
        array (
          'text' => 
          array (
            'am' => 'Կիլոգրամ',
            'ru' => 'Килограм',
            'en' => 'Kilogram',
          ),
          'code' => 'kg',
        ),
      ),
    ),
    'CAttributika' => 
    array (
      'work_dir' => '/var/www/html/mastershop.local/web/modules/attributika/uploads/',
    ),
    'CArchive' => 
    array (
      'save_dir' => '/var/www/u0194925/public_html/alutech/configs/../archives/',
    ),
    'CBanking' => 
    array (
      'config' => 
      array (
        'back_url' => 'account/orders/',
      ),
      'arca' => 
      array (
        'MERCHANTNUMBER' => '',
        'MERCHANTPASSWD' => '',
      ),
    ),
  ),
  'gago' => 'chemiche peto',
  'predefines' => 
  array (
    'product' => 
    array (
      0 => 'product_category',
      1 => 'brand',
      2 => 'tags',
      3 => 'discount',
    ),
    'product_category' => 
    array (
      0 => 'product',
      1 => 'brand',
    ),
  ),
);
 ?>