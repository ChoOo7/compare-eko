<?php

require_once(__DIR__.'/functions.php');

$searchedTerm = null;
$outType = null;
if( ! empty($_REQUEST))
{
    $searchedTerm = $_REQUEST['term'];
    $outType = $_REQUEST['out'];
}else{
    $searchedTerm = $argv[1];
    $outType = $argv[2];
}

$products = searchInElastic($searchedTerm);

if(count($products) == 0)
{
  echo "NO FOUND";
}elseif(count($products) == 1){
  //var_dump($products[0]);
  $product = $products[0];
  switch($outType)
  {
    case 'energy':
        echo $product['efficiency_exact'];
        break;
    
    case 'img':
        echo $product['imgUrl'];
        break;
    }
}else{
  echo "\n".count($products).' matches'."\n";
  foreach($products as $product)
  {
    echo "\n".$product['name'];
  }
}
