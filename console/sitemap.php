<?php
unlink(__DIR__ . DIRECTORY_SEPARATOR . '../sitemap.xml');
$domDocument = new DOMDocument('1.0', "UTF-8");

$domElement = $domDocument->createElement('urlset');
$domAttribute = $domDocument->createAttribute('xmlns');

// Value for the created attribute
$domAttribute->value = 'http://www.sitemaps.org/schemas/sitemap/0.9';

// Don't forget to append it to the element
$domElement->appendChild($domAttribute);
$url = $domDocument->createElement('url');
$loc = $domDocument->createElement('loc', URL_BASE);
$changefreq = $domDocument->createElement('changefreq', 'never');
$priority = $domDocument->createElement('priority', '1.0');
$domElement->appendChild($url);
$url->appendChild($loc);
$url->appendChild($changefreq);
$url->appendChild($priority);

Cmwdb::$db->where('page_slug', '', '!=');
Cmwdb::$db->where('page_isactive', 1);
$pages = Cmwdb::$db->get(CFrontPage::$tbl_name, null, ['page_slug', 'page_lang']);
foreach ($pages as $page) {
    $obj_url = CUrlManager::GetStaticURL('page', $page['page_slug'], $page['page_lang']);
    $url = $domDocument->createElement('url');
    $loc = $domDocument->createElement('loc', $obj_url);
    $changefreq = $domDocument->createElement('changefreq', 'never');
    $priority = $domDocument->createElement('priority', '0.9');
    $domElement->appendChild($url);
    $url->appendChild($loc);
    $url->appendChild($changefreq);
    $url->appendChild($priority);
}

Cmwdb::$db->where('slugs', '', '!=');
//Cmwdb::$db->where('page_isactive', 1);
$categories = Cmwdb::$db->get(CFrontCategoryPost::$tbl_name, null, ['slugs', 'category_lang']);
foreach ($categories as $category) {
    $obj_url = CUrlManager::GetStaticURL('post_category', $category['slugs'], $category['category_lang']);
    $url = $domDocument->createElement('url');
    $loc = $domDocument->createElement('loc', $obj_url);
    $changefreq = $domDocument->createElement('changefreq', 'never');
    $priority = $domDocument->createElement('priority', '0.8');
    $domElement->appendChild($url);
    $url->appendChild($loc);
    $url->appendChild($changefreq);
    $url->appendChild($priority);
}

Cmwdb::$db->where('post_slug', '', '!=');
//Cmwdb::$db->where('page_isactive', 1);
$categories = Cmwdb::$db->get(CFrontPost::$tbl_name, null, ['post_slug', 'post_lang']);
foreach ($categories as $category) {
    $obj_url = CUrlManager::GetStaticURL('post', $category['post_slug'], $category['post_lang']);
    $url = $domDocument->createElement('url');
    $loc = $domDocument->createElement('loc', $obj_url);
    $changefreq = $domDocument->createElement('changefreq', 'never');
    $priority = $domDocument->createElement('priority', '0.7');
    $domElement->appendChild($url);
    $url->appendChild($loc);
    $url->appendChild($changefreq);
    $url->appendChild($priority);
}

Cmwdb::$db->where('tag_slug', '', '!=');
//Cmwdb::$db->where('page_isactive', 1);
$tags = Cmwdb::$db->get(CFrontTags::$tbl_name, null, ['tag_slug', 'lang']);
foreach ($tags as $tag) {
    $obj_url = CUrlManager::GetStaticURL(CFrontTags::$tbl_name, $tag['tag_slug'], $tag['lang']);
    $url = $domDocument->createElement('url');
    $loc = $domDocument->createElement('loc', $obj_url);
    $changefreq = $domDocument->createElement('changefreq', 'never');
    $priority = $domDocument->createElement('priority', '0.6');
    $domElement->appendChild($url);
    $url->appendChild($loc);
    $url->appendChild($changefreq);
    $url->appendChild($priority);
}

if (CModule::HasModule('product_category')) {
    Cmwdb::$db->where('slugs', '', '!=');
//Cmwdb::$db->where('page_isactive', 1);
    $categories = Cmwdb::$db->get(CFrontProductCategory::$tbl_name, null, ['slugs', 'category_lang']);
    foreach ($categories as $category) {
        $obj_url = CUrlManager::GetStaticURL('product_category', $category['slugs'], $category['category_lang']);
        $url = $domDocument->createElement('url');
        $loc = $domDocument->createElement('loc', $obj_url);
        $changefreq = $domDocument->createElement('changefreq', 'never');
        $priority = $domDocument->createElement('priority', '0.8');
        $domElement->appendChild($url);
        $url->appendChild($loc);
        $url->appendChild($changefreq);
        $url->appendChild($priority);
    }
}
if (CModule::HasModule('product')) {
    Cmwdb::$db->where('product_slug', '', '!=');
//Cmwdb::$db->where('page_isactive', 1);
    $products = Cmwdb::$db->get(CFrontProduct::$tbl_name, null, ['product_slug', 'product_lang']);
    foreach ($products as $product) {
        $obj_url = CUrlManager::GetStaticURL('product', $product['product_slug'], $product['product_lang']);
        $url = $domDocument->createElement('url');
        $loc = $domDocument->createElement('loc', $obj_url);
        $changefreq = $domDocument->createElement('changefreq', 'never');
        $priority = $domDocument->createElement('priority', '0.7');
        $domElement->appendChild($url);
        $url->appendChild($loc);
        $url->appendChild($changefreq);
        $url->appendChild($priority);
    }
}
if (CModule::HasModule('brand')) {
    Cmwdb::$db->where('brand_slug', '', '!=');
//Cmwdb::$db->where('page_isactive', 1);
    $brands = Cmwdb::$db->get(CFrontBrand::$tbl_name, null, ['brand_slug', 'brand_lang']);
    foreach ($brands as $brand) {
        $obj_url = CUrlManager::GetStaticURL('brand', $brand['brand_slug'], $brand['brand_lang']);
        $url = $domDocument->createElement('url');
        $loc = $domDocument->createElement('loc', $obj_url);
        $changefreq = $domDocument->createElement('changefreq', 'never');
        $priority = $domDocument->createElement('priority', '0.6');
        $domElement->appendChild($url);
        $url->appendChild($loc);
        $url->appendChild($changefreq);
        $url->appendChild($priority);
    }
}
if (CModule::HasModule('tags')) {
    Cmwdb::$db->where('tag_slug', '', '!=');
//Cmwdb::$db->where('page_isactive', 1);
    $tags = Cmwdb::$db->get(CFrontProductTags::$tbl_name, null, ['tag_slug', 'lang']);
    foreach ($tags as $tag) {
        $obj_url = CUrlManager::GetStaticURL('product_tag', $tag['tag_slug'], $tag['lang']);
        $url = $domDocument->createElement('url');
        $loc = $domDocument->createElement('loc', $obj_url);
        $changefreq = $domDocument->createElement('changefreq', 'never');
        $priority = $domDocument->createElement('priority', '0.5');
        $domElement->appendChild($url);
        $url->appendChild($loc);
        $url->appendChild($changefreq);
        $url->appendChild($priority);
    }
}
$domDocument->appendChild($domElement);
$domDocument->save('sitemap.xml');
$date_end = date('Y-m-d H:i:s', strtotime("-280 days"));
//CSitemap::Update(file_get_contents('sitemap.xml'));
CSitemap::Update();
//$mwdb->query("DELETE FROM adds WHERE add_datetime<'$date_end'");
?>