<?php

require 'akamai-open-edgegrid-auth-1.0.1.phar';

function request($file, $section, $method, $path, $body = null) {
  $method = strtoupper($method);
  $auth = \Akamai\Open\EdgeGrid\Authentication::createFromEdgeRcFile($section, $file);
  $auth->setHttpMethod($method);
  $auth->setPath($path);
  $auth->setBody($body);
  $context = stream_context_create(array(
  	'http' => array(
  		'header' => array(
  			'Authorization: ' . $auth->createAuthHeader(),
  		 	'Content-Type: application/json',
        'Content-Length: ' . strlen($body),
        #'PAPI-Use-Prefixes: true'
      ),
      'method' => $method,
      'ignore_errors' => true,
      'content' => $body,
  	)
  ));
  var_dump('https://' . $auth->getHost() . $auth->getPath() . '?' . $auth->getQuery());
  $response = file_get_contents('https://' . $auth->getHost() . $auth->getPath() . '?' . $auth->getQuery(), null, $context);
  return $response;
}

list($file, $section, $method, $path) = array_slice($argv, 1);
$body = file_get_contents('php://stdin');
$response = request($file, $section, $method, $path, $body);
printf("%s\n", json_encode(json_decode($response), JSON_PRETTY_PRINT));

#/Users/guillermo.pereyra/.edgerc strategy-global get /diagnostic-tools/v2/ghost-locations/available
#'/papi/v1/properties/prp_555994/versions/27/rules?contractId=ctr_2-1NLW6&groupId=grp_13729'
