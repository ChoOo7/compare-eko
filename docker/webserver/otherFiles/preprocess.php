<?php
### AUTO MANAGED BY SALT. DO NOT EDIT ###

if(function_exists("gethostname") && function_exists("apache_note"))
{
  apache_note('statsd.stat', 'php.'.gethostname());
}

if( ! function_exists("canonicalize"))
{
  function canonicalize($address)
  {
    $address = explode('/', $address);
    $keys = array_keys($address, '..');

    foreach ($keys as $keypos => $key)
    {
      array_splice($address, $key - ($keypos * 2 + 1), 2);
    }

    $address = implode('/', $address);
    $address = str_replace('./', '', $address);

    return $address;
  }
}