<?php

// 2016-07-15

return [
     // general 1xxx
    '1010' => [
        'code'  => 1010,
        'title' => 'Missing field(s)',
    ],
    '1020' => [
        'code'  => 1020,
        'title' => 'field validate fail',
    ],
    '1510' => [
        'code'  => 1510,
        'title' => 'file not found',
    ],
    '1520' => [
        'code'  => 1520,
        'title' => 'System Error',
    ],
    '1530' => [
        'code'  => 1530,
        'title' => 'OK',
    ],


    // user 2xxx
    '2010' => [
        'code'  => 2010,
        'title' => 'User Not Found',
    ],
    '2020' => [
        'code'  => 2020,
        'title' => 'New User Info Create',
    ],
    '2030' => [
        'code'  => 2030,
        'title' => 'User Login Created',
    ],
    '2040' => [
        'code'  => 2040,
        'title' => 'User Verify is true and already Register',
    ],
    '2050' => [
        'code'  => 2050,
        'title' => 'User Verify is true, not Register yet',
    ],
    '2051' => [
        'code'  => 2051,
        'title' => 'User Verify fail',
    ],
    '2060' => [
        'code'  => 2060,
        'title' => 'User can use',
    ],
    '2070' => [
        'code'  => 2070,
        'title' => 'User already used',
    ],
    '2080' => [
        'code'  => 2080,
        'title' => 'Login Fail',
    ],

    // User password
    '2510' => [
        'code'  => 2510,
        'title' => 'Password not strong enough',
    ],
    '2520' => [
        'code'  => 2520,
        'title' => 'Old password not same as before',
    ],
    '2530' => [
        'code'  => 2530,
        'title' => 'Password updated',
    ],
    '2540' => [
        'code'  => 2540,
        'title' => 'Forgot password mail sent',
    ],

    //Policy
    '3010' => [
        'code'  => 3010,
        'title' => 'Policy not found',
    ],

    //Claim
    '5010' => [
        'code'  => 5010,
        'title' => 'Claim Create',
    ],


    // using api fail 4xxxx
    '4010' => [
        'code'  => 4010,
        'title' => 'Platform Header Missing',
    ],
    '4020' => [
        'code'  => 4020,
        'title' => 'Need Authenticate',
    ],
];
