<?php

require 'akamai.php';

cli(__FILE__, '/cprg/v1', [

  'list-cpcodes' => get('cpcodes'),

  'get-cpcode' => get('cpcodes/%s', ['cpcode-id']),

  # update cpcode

  'list-reporting-groups' => get('reporting-groups'),

  'get-reporting-group' => get('reporting-groups/%s', ['reporting-group-id']),

  # update reporting group

  'list-reporting-group-products' => get('reporting-groups/%s/products', ['reporting-group-id']),

]);
