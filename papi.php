<?php

require 'akamai-open-edgegrid-auth-1.0.1.phar';

function error($message) {
  echo $message . "\n";
  exit;
}

function method($method, $url, $urlCount = 0, $body = '', $bodyCount = 0) {
  $method = strtoupper($method);
  return [
    'method' => $method,
    'url' => sprintf('/papi/v1/%s', $url),
    'urlCount' => $urlCount,
    'body' => $body,
    'bodyCount' => $bodyCount,
  ];
}

function get($url, $count = 0) {
  return method('get', $url, $count);
}

function post($url, $urlCount = 0, $body = '', $bodyCount = 0) {
  return method('post', $url, $urlCount, $body, $bodyCount);
}

function put($url, $count = 0) {
  return method('put', $url, $count);
}

function delete($url, $count = 0) {
  return method('delete', $url, $count);
}

$commands = [
  'list-groups' => get('groups'),
  'list-contracts' => get('contracts'),
  'list-products' => get('products?contractId=%s', 1),
  'list-cpcodes' => get('cpcodes?contractId=%s&groupId=%s', 2),
  'create-cpcode' => post('cpcodes?contractId=%s&groupId=%s', 2, json_encode([
    'productId' => '%s',
    'cpcodeName' => '%s',
  ]), 2),
  'get-cpcode' => get('cpcodes/%s?contractId=%s&groupId=%s', 3),
  'list-edge-hostnames' => get('edgehostnames?contractId=%s&groupId=%s&options=mapDetails', 2),
  # create-edge-hostname
  'get-edge-hostname' => get('edgehostnames/%s?contractId=%s&groupId=%s&options=mapDetails', 3),
  'list-properties' => get('properties?contractId=%s&groupId=%s', 2),
  'create-property' => post('properties?contractId=%s&groupId=%s', 2, json_encode([
    'productId' => '%s',
    'propertyName' => '%s',
  ]), 2),
  'get-property' => get('properties/%s?contractId=%s&groupId=%s', 3),
  'list-property-versions' => get('properties/%s/versions?contractId=%s&groupId=%s', 3),
  'create-property-version' => post('properties/%s/versions?contractId=%s&groupId=%s', 3, json_encode([
    'createFromVersion' => '%s',
  ]), 1),
  'get-property-version' => get('properties/%s/versions/%s?contractId=%s&groupId=%s', 4),
  'get-property-version-hostnames' => get('properties/%s/versions/%s/hostnames?contractId=%s&groupId=%s', 4),
  'update-property-version-hostnames' => put('properties/%s/versions/%s/hostnames?contractId=%s&groupId=%s', 4),
  'get-property-version-rules' => get('properties/%s/versions/%s/rules?contractId=%s&groupId=%s', 4),
  'update-property-version-rules' => put('properties/%s/versions/%s/rules?contractId=%s&groupId=%s', 4),
  'list-property-activations' => get('properties/%s/activations?contractId=%s&groupId=%s', 3),
  'create-property-activation' => post('properties/%s/activations?contractId=%s&groupId=%s', 3, json_encode([
    'propertyVersion' => '%s',
    'network' => '%s',
    'notifyEmails' => ['%s'],
#    'acknowledgeWarnings' => ["msg_7a2aa72bca3f894607c126436861cb73fb82d677", "msg_1d2ecd69b22ce52028f55561bff01fd64c76f8d5", "msg_e94d764742a56988686c1e630cbe66c30eb52bf5", "msg_7b80aa702b7140f1f3b1f4d98c1a9ae48f11fd54"]
  ]), 3),
  'get-property-activation' => get('properties/%s/activations/%s?contractId=%s&groupId=%s', 4),
  'cancel-property-activation' => delete('properties/%s/activations/%s?contractId=%s&groupId=%s', 4),
];

array_shift($argv); // myself
$credentials = array_shift($argv);
$section = array_shift($argv);
$cname = array_shift($argv);
if (empty($cname)) {
  
?>
Usage: php <?= __FILE__ ?> <command> [<args> ...]
Available commands:
  list-groups
  list-contracts
  list-products <contract-id>
  list-cpcodes <contract-id> <group-id>
  create-cpcode <contract-id> <group-id> <product-id> <cpcode-name>
  get-cpcode <cpcode-id> <contract-id> <group-id>
  list-edge-hostnames <contract-id> <group-id>
<?php #create-etls-ehn <contract-id> <group-id> <product-id> <enrollment-id> <name-without-edgekey.net> <IPV4|IPV6|IPV6_COMPLIANCE> ?>
  list-properties <contract-id> <group-id>
  create-property <contract-id> <group-id> <product-id> <property-name>
  get-property <property-id> <contract-id> <group-id>
  list-property-versions <property-id> <contract-id> <group-id>
  create-property-version <property-id> <contract-id> <group-id> <from-version>
  get-property-version <property-id> <version-no> <contract-id> <group-id>
  get-property-version-hostnames <property-id> <version-no> <contract-id> <group-id>
  <hostnames> | update-property-version-hostnames <property-id> <version-no> <contract-id> <group-id>
  get-property-version-rules <property-id> <version-no> <contract-id> <group-id>
  <rules> | update-property-version-rules <property-id> <version-no> <contract-id> <group-id>
  list-property-activations <property-id> <contract-id> <group-id>
  create-property-activation <property-id> <contract-id> <group-id> <version-no> <network> <email>
  get-property-activation <property-id> <activation-id> <contract-id> <group-id>
  cancel-property-activation <property-id> <activation-id> <contract-id> <group-id>
<?php

} else if ($cname === 'alias') {

  $name = array_shift($argv);
  printf("alias %s='%s %s %s %s'\n", $name, $_SERVER['_'], __FILE__, realpath($credentials), $section);

} else if ($cdef = $commands[$cname]) {

  $method = $cdef['method'];

  $urlArgs = [];
  $urlArgs[] = $cdef['url'];
  for ($i = 0; $i < $cdef['urlCount']; $i++) $urlArgs[] = array_shift($argv);
  $path = call_user_func_array('sprintf', $urlArgs);

  if (in_array($method, ['POST', 'PUT'])) {
    if (empty($cdef['body'])) {
      $body = file_get_contents('php://stdin');
    } else {
      $bodyArgs = [];
      $bodyArgs[] = $cdef['body'];
      for ($i = 0; $i < $cdef['bodyCount']; $i++) $bodyArgs[] = array_shift($argv);
      $body = call_user_func_array('sprintf', $bodyArgs);
    }
  } else {
    $body = '';
  }

  $auth = \Akamai\Open\EdgeGrid\Authentication::createFromEdgeRcFile($section, $credentials);
  $auth->setHttpMethod($method);
  $auth->setPath($path);
  if (!empty($body)) {
    $auth->setBody($body);
  }

  $url = 'https://' . $auth->getHost() . $auth->getPath() . '?' . $auth->getQuery();

  $headers = [];
  $headers[] = 'PAPI-Use-Prefixes: true';
  $headers[] = sprintf('Authorization: %s', $auth->createAuthHeader());
  if (!empty($body)) {
    $headers[] = 'Content-Type: application/json';
    $headers[] = sprintf('Content-Length: %d', strlen($body));
  }

  $http = [
    'ignore_errors' => true,
    'method' => $method,
    'header' => $headers,
  ];
  if (!empty($body)) {
    $http['content'] = $body;
  }

  $response = file_get_contents($url, null, stream_context_create(['http' => $http]));
  printf("%s\n", json_encode(json_decode($response), JSON_PRETTY_PRINT));

} else {
  error('Unknown command ' . $command);
}
