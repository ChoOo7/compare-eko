<?php


function getElasticHost()
{
    $host = 'localhost';
    if(isset($_ENV) && is_array($_ENV) && isset($_ENV["COMPELASTICSEARCH_1_PORT_9200_TCP_ADDR"]))
    {
      $host = $_ENV["COMPELASTICSEARCH_1_PORT_9200_TCP_ADDR"];
    }
    return $host;
}

function getElasticIndexUrl()
{
  return 'http://'.getElasticHost().':9200/products/product';
}

function addInfosToElastic($infos)
{
    $url = getElasticIndexUrl();
    
    $url.='/'.$infos['id'];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    $diff = array('product'=>$infos);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($diff));
    curl_setopt($ch, CURLOPT_URL, $url);
    $response = curl_exec($ch);    
    $tmp = json_decode($response, true);
}

function searchInElastic($term)
{

    $params = array();
    //$params['fields'] = array("_id");
    $params['query'] = array();
    $params['query']['query_string'] = array();
    $params['query']['query_string']['query'] = $term;
    $params['size'] = 9999;
    //$params['query']['query_string']['default_field'] = "text";

    
    $url = getElasticIndexUrl();
    $url .= '/_search?pretty=true';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_URL, $url);
    $response = curl_exec($ch);    
    $tmp = json_decode($response, true);
    $return = array();
    foreach($tmp['hits']['hits'] as $item)
    {
        $return[] = $item['_source']['product'];
    }
    return $return;
}