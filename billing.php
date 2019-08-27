<?php

require 'akamai.php';

CommandLineInterface::create(__FILE__, '/billing-center-api/v2', [
  'list-usage' => Command::get('reporting-groups/%s/products/%s/measures?year=%s&month=%s', ['reporting-group-id', 'product-id', 'year', 'month']),
  'list-stats' => Command::get('reporting-groups/%s/products/%s/statistics?year=%s&month=%s', ['reporting-group-id', 'product-id', 'year', 'month']),
  'list-prods' => Command::get('reporting-groups/%s/products?year=%s&month=%s', ['reporting-group-id', 'year', 'month']),
])->run($argv);
