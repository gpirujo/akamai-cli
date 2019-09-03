<?php

require 'akamai.php';

cli(__FILE__, '/papi/v1', [

  'list-groups' => get('groups'),

  'list-contracts' => get('contracts'),

  'list-products' => get('products?contractId=%s', ['contract-id']),

  'list-cpcodes' => get('cpcodes?contractId=%s&groupId=%s', ['contract-id', 'group-id']),

  'create-cpcode' => post('cpcodes?contractId=%s&groupId=%s', ['contract-id', 'group-id'])->body(function($p, $n) {
    return [
      'productId' => $p,
      'cpcodeName' => $n,
    ];
  }, ['product-id', 'cpcode-name']),

  'get-cpcode' => get('cpcodes/%s?contractId=%s&groupId=%s', ['cpcode-id', 'contract-id', 'group-id']),

  'list-edge-hostnames' => get('edgehostnames?contractId=%s&groupId=%s&options=mapDetails', ['contract-id', 'group-id']),

  'get-edge-hostname' => get('edgehostnames/%s?contractId=%s&groupId=%s&options=mapDetails', ['edge-hostname-id', 'contract-id', 'group-id']),

  'list-properties' => get('properties?contractId=%s&groupId=%s', ['contract-id', 'group-id']),

  'create-property' => post('properties?contractId=%s&groupId=%s', ['contract-id', 'group-id'])->body(function($p, $n) {
    return [
      'productId' => $p,
      'propertyName' => $n,
    ];
  }, ['product-id', 'property-name']),

  'get-property' => get('properties/%s?contractId=%s&groupId=%s', ['property-id', 'contract-id', 'group-id']),

  'list-property-versions' => get('properties/%s/versions?contractId=%s&groupId=%s', ['property-id', 'contract-id', 'group-id']),

  'create-property-version' => post('properties/%s/versions?contractId=%s&groupId=%s', ['property-id', 'contract-id', 'group-id'])->body(function($f) {
    return [
      'createFromVersion' => $f,
    ];
  }, ['from-version']),

  'get-property-version' => get('properties/%s/versions/%s?contractId=%s&groupId=%s', ['property-id', 'version-no', 'contract-id', 'group-id']),

  'get-property-version-hostnames' => get('properties/%s/versions/%s/hostnames?contractId=%s&groupId=%s', ['property-id', 'version-no', 'contract-id', 'group-id']),

  'update-property-version-hostnames' => put('properties/%s/versions/%s/hostnames?contractId=%s&groupId=%s', ['property-id', 'version-no', 'contract-id', 'group-id'], 'hostnames'),

  'get-property-version-rules' => get('properties/%s/versions/%s/rules?contractId=%s&groupId=%s', ['property-id', 'version-no', 'contract-id', 'group-id']),

  'update-property-version-rules' => put('properties/%s/versions/%s/rules?contractId=%s&groupId=%s', ['property-id', 'version-no', 'contract-id', 'group-id'], 'rules'),

  'list-property-activations' => get('properties/%s/activations?contractId=%s&groupId=%s', ['property-id', 'contract-id', 'group-id']),

  'create-property-activation' => post('properties/%s/activations?contractId=%s&groupId=%s', ['property-id', 'contract-id', 'group-id'])->body(function($v, $n, $e) {
    return [
      'propertyVersion' => $v,
      'network' => $n,
      'notifyEmails' => [$e],
      #    'acknowledgeWarnings' => ["msg_7a2aa72bca3f894607c126436861cb73fb82d677", "msg_1d2ecd69b22ce52028f55561bff01fd64c76f8d5", "msg_e94d764742a56988686c1e630cbe66c30eb52bf5", "msg_7b80aa702b7140f1f3b1f4d98c1a9ae48f11fd54"]
    ];
  }, ['version-no', 'network', 'notify-email']),

  'get-property-activation' => get('properties/%s/activations/%s?contractId=%s&groupId=%s', ['property-id', 'activation-id', 'contract-id', 'group-id']),

  'cancel-property-activation' => delete('properties/%s/activations/%s?contractId=%s&groupId=%s', ['property-id', 'activation-id', 'contract-id', 'group-id']),

]);
