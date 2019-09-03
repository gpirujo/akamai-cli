<?php

require 'akamai.php';

cli(__FILE__, '/billing-center-api/v2', [

  'list-usage' => get('reporting-groups/%s/products/%s/measures?year=%s&month=%s', ['reporting-group-id', 'product-id', 'year', 'month']),

  'list-stats' => get('reporting-groups/%s/products/%s/statistics?year=%s&month=%s', ['reporting-group-id', 'product-id', 'year', 'month']),

  'list-prods' => get('reporting-groups/%s/products?year=%s&month=%s', ['reporting-group-id', 'year', 'month']),
  
]);
