<?php

require 'akamai-open-edgegrid-auth-1.0.1.phar';

function fgc($method, $url, $headers, $body = '') {
  $http = [
    'ignore_errors' => true,
    'method' => $method,
    'header' => $headers,
  ];
  if (!empty($body)) {
    $http['content'] = $body;
  }
  return file_get_contents($url, null, stream_context_create(['http' => $http]));
}

function curl($method, $url, $headers, $body = '') {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  if (!empty($body)) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
  }
  $output = curl_exec($ch);
  curl_close($ch);
  return $output;
}

list($file, $section, $method, $path) = array_slice($argv, 1);
$method = strtoupper($method);
$body = file_get_contents('php://stdin');

$headers = [];
$headers['PAPI-Use-Prefixes'] = 'true';
if (!empty($body)) {
  $headers['Content-Type'] = 'application/json';
  $headers['Content-Length'] = strlen($body);
}

$auth = \Akamai\Open\EdgeGrid\Authentication::createFromEdgeRcFile($section, $file);
$auth->setHttpMethod($method);
$auth->setPath($path);
#$auth->setHeadersToSign([]); //'PAPI-Use-Prefixes'
$auth->setHeaders($headers);
if (!empty($body)) {
  $auth->setBody($body);
}

$url = 'https://' . $auth->getHost() . $auth->getPath() . '?' . $auth->getQuery();

$headers['Authorization'] = $auth->createAuthHeader();

$hl = [];
foreach ($headers as $name => $value) $hl[] = sprintf('%s: %s', $name, $value);

#$response = curl($method, $url, $hl, $body);
#print_r(json_decode($response));
$response = fgc($method, $url, $hl, $body);
print_r(json_decode($response));

#printf("%s\n", json_encode(json_decode($response), JSON_PRETTY_PRINT));

#/Users/guillermo.pereyra/.edgerc strategy-global get /diagnostic-tools/v2/ghost-locations/available
#'/papi/v1/properties/prp_555994/versions/27/rules?contractId=ctr_2-1NLW6&groupId=grp_13729'
