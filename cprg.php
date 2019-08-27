<?php

require 'akamai.php';

CommandLineInterface::create(__FILE__, '/cprg/v1', [
  'list-cpcodes' => Command::get('cpcodes'),
  'get-cpcode' => Command::get('cpcodes/%s', ['cpcode-id']),
  # update cpcode
  'list-reporting-groups' => Command::get('reporting-groups'),
  'get-reporting-group' => Command::get('reporting-groups/%s', ['reporting-group-id']),
  # update reporting group
  'list-reporting-group-products' => Command::get('reporting-groups/%s/products', ['reporting-group-id']),
])->run($argv);
