<?php

require_once(__DIR__.'/functions.php');

function getProductsOfPage($page = 0)
{
    $cacheFile = __DIR__."/cache/compare-cache-".$page;
    $cnt = null;
    if( ! file_exists($cacheFile))
    {
        $url = "http://compareco.ch/fr/cooling/search.json?page=".urlencode($page);
        $cnt = file_get_contents($url);
        file_put_contents($cacheFile, $cnt);
    }else{
        $cnt = file_get_contents($cacheFile);
    }
    $tmp = json_decode($cnt, true);
    return $tmp["results"];
}

function storeProductInformation($product)
{
    $name = $product['name'];
    $brand = $product['brand_exact'];
    $categoryName = $product['l1_i'];
    $imgUrl = 'http://compareco.ch'.$product['imgurl_s'];    
    $efficiency = $product['efficiency_exact'];
    
    
    $infos = $product;
    $infos['brand']=$brand;
    $infos['categoryName']=$categoryName;
    $infos['imgUrl']=$imgUrl;
    
    addInfosToElastic($infos);    
}


$products = array();
$lastProducts = array();
for($pageNumber=0; $pageNumber <= 2500;$pageNumber++)
{
    echo "\n".$pageNumber;
    $products = getProductsOfPage($pageNumber);
    foreach($products as $product)
    {
        storeProductInformation($product);
        //var_dump($product);
    }
    
}