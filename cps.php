<?php

require 'akamai.php';

define('ENROLLMENTS', 'application/vnd.akamai.cps.enrollments.v7+json');
define('ENROLLMENT', 'application/vnd.akamai.cps.enrollment.v7+json');
define('ENROLLMENT_STATUS', 'application/vnd.akamai.cps.enrollment-status.v1+json');

cli(__FILE__, '/cps/v2', [

  'list-enrollments' => get('enrollments', ['contract-id'])->accept(ENROLLMENTS),

  'create-enrollment' => post('enrollments', ['contract-id'], 'enrollment')->contentType(ENROLLMENT)->accept(ENROLLMENT_STATUS),

]);
