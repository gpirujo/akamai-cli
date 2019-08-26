<?php

require 'akamai.php';

CommandLineInterface::create(__FILE__, '/cps/v2', [
  'list-enrollments' => Command::get('enrollments', ['contract-id'])->headers([
    'Accept: application/vnd.akamai.cps.enrollments.v7+json'
  ]),
  'create-enrollment' => Command::post('enrollments', ['contract-id'])->stdin('enrollment')->headers([
    'Content-Type: application/vnd.akamai.cps.enrollment.v7+json',
    'Accept: application/vnd.akamai.cps.enrollment-status.v1+json'
  ]),
])->run($argv);
