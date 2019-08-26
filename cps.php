<?php

require 'akamai.php';

CommandLineInterface::create('/cps/v2', [
  'list-enrollments' => Command::get('enrollments', ['contract-id'])
                        ->headers(['Accept: application/vnd.akamai.cps.enrollments.v7+json']),
])->run($argv);
