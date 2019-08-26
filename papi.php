<?php

require 'akamai.php';

CommandLineInterface::create('/papi/v1')
->get(
  'list-groups',
   'groups'
)
->get(
  'list-contracts',
  'contracts'
)
->get(
  'list-products',
  'products?contractId=%s', ['contract-id']
)
->get(
  'list-cpcodes',
  'cpcodes?contractId=%s&groupId=%s', ['contract-id', 'group-id']
)
->post(
  'create-cpcode',
  'cpcodes?contractId=%s&groupId=%s', ['contract-id', 'group-id'],
  function($p, $n) {
    return [
      'productId' => $p,
      'cpcodeName' => $n,
    ];
  }, ['product-id', 'cpcode-name']
)
->get(
  'get-cpcode',
  'cpcodes/%s?contractId=%s&groupId=%s', ['cpcode-id', 'contract-id', 'group-id']
)
->get(
  'list-edge-hostnames',
  'edgehostnames?contractId=%s&groupId=%s&options=mapDetails', ['contract-id', 'group-id']
)
->get(
  'get-edge-hostname',
  'edgehostnames/%s?contractId=%s&groupId=%s&options=mapDetails', ['edge-hostname-id', 'contract-id', 'group-id']
)
->get(
  'list-properties',
  'properties?contractId=%s&groupId=%s', ['contract-id', 'group-id']
)
->post(
  'create-property',
  'properties?contractId=%s&groupId=%s', ['contract-id', 'group-id'],
  function($p, $n) {
    return [
      'productId' => $p,
      'propertyName' => $n,
    ];
  }, ['product-id', 'property-name']
)
->get(
  'get-property',
  'properties/%s?contractId=%s&groupId=%s', ['property-id', 'contract-id', 'group-id']
)
->get(
  'list-property-versions',
  'properties/%s/versions?contractId=%s&groupId=%s', ['property-id', 'contract-id', 'group-id']
)
->post(
  'create-property-version',
  'properties/%s/versions?contractId=%s&groupId=%s', ['property-id', 'contract-id', 'group-id'],
  function($f) {
    return [
      'createFromVersion' => $f,
    ];
  }, ['from-version']
)
->get(
  'get-property-version',
  'properties/%s/versions/%s?contractId=%s&groupId=%s', ['property-id', 'version-no', 'contract-id', 'group-id']
)
->get(
  'get-property-version-hostnames',
  'properties/%s/versions/%s/hostnames?contractId=%s&groupId=%s', ['property-id', 'version-no', 'contract-id', 'group-id']
)
->put(
  'update-property-version-hostnames',
  'properties/%s/versions/%s/hostnames?contractId=%s&groupId=%s', ['property-id', 'version-no', 'contract-id', 'group-id'],
  'hostnames'
)
->get(
  'get-property-version-rules',
  'properties/%s/versions/%s/rules?contractId=%s&groupId=%s', ['property-id', 'version-no', 'contract-id', 'group-id']
)
->put(
  'update-property-version-rules',
  'properties/%s/versions/%s/rules?contractId=%s&groupId=%s', ['property-id', 'version-no', 'contract-id', 'group-id'],
  'rules'
)
->get(
  'list-property-activations',
  'properties/%s/activations?contractId=%s&groupId=%s', ['property-id', 'contract-id', 'group-id']
)
->post(
  'create-property-activation',
  'properties/%s/activations?contractId=%s&groupId=%s', ['property-id', 'contract-id', 'group-id'],
  function($v, $n, $e) {
    return [
      'propertyVersion' => $v,
      'network' => $n,
      'notifyEmails' => [$e],
      #    'acknowledgeWarnings' => ["msg_7a2aa72bca3f894607c126436861cb73fb82d677", "msg_1d2ecd69b22ce52028f55561bff01fd64c76f8d5", "msg_e94d764742a56988686c1e630cbe66c30eb52bf5", "msg_7b80aa702b7140f1f3b1f4d98c1a9ae48f11fd54"]
    ];
  }, ['version-no', 'network', 'notify-email']
)
->get(
  'get-property-activation',
  'properties/%s/activations/%s?contractId=%s&groupId=%s', ['property-id', 'activation-id', 'contract-id', 'group-id']
)
->delete(
  'cancel-property-activation',
  'properties/%s/activations/%s?contractId=%s&groupId=%s', ['property-id', 'activation-id', 'contract-id', 'group-id']
)
->run($argv);
