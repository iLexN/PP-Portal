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
    '1540' => [
        'code'  => 1540,
        'title' => 'get office info success',
    ],
    //file handle
    '1810' => [
        'code'  => 1810,
        'title' => 'no file upload',
    ],
    '1820' => [
        'code'  => 1820,
        'title' => 'upload file validate fail',
    ],
    '1830' => [
        'code'  => 1830,
        'title' => 'upload fail',
    ],
    '1840' => [
        'code'  => 1840,
        'title' => 'upload success',
    ],
    '1850' => [
        'code'  => 1850,
        'title' => 'upload file remove',
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
    '2021' => [
        'code'  => 2021,
        'title' => 'get userinfo success',
    ],
    '2022' => [
        'code'  => 2022,
        'title' => 'get user renew info success',
    ],
    '2023' => [
        'code'  => 2023,
        'title' => 'get user renew info, empty',
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
    '2081' => [
        'code'  => 2081,
        'title' => 'login success',
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
    '2550' => [
        'code'  => 2550,
        'title' => 'Forgot username success',
    ],
    '2560' => [
        'code'  => 2560,
        'title' => 'Forgot password token find user',
    ],
    '2570' => [
        'code'  => 2570,
        'title' => 'Forgot password ,password updated',
    ],

    //Policy
    '3010' => [
        'code'  => 3010,
        'title' => 'Policy not found',
    ],
    '3020' => [
        'code'  => 3020,
        'title' => 'get policy list success',
    ],
    '3030' => [
        'code'  => 3030,
        'title' => 'get policy success',
    ],
    '3040' => [
        'code'  => 3040,
        'title' => 'get policy people success',
    ],
    '3510' => [
        'code'  => 3510,
        'title' => 'Advisor not found',
    ],
    '3610' => [
        'code'  => 3610,
        'title' => 'bank a/c create',
    ],
    '3611' => [
        'code'  => 3611,
        'title' => 'bank a/c update',
    ],
    '3612' => [
        'code'  => 3612,
        'title' => 'bank a/c delete',
    ],
    '3620' => [
        'code'  => 3620,
        'title' => 'no bank a/c info',
    ],
    '3630' => [
        'code'  => 3630,
        'title' => 'bank a/c info success',
    ],
    '3640' => [
        'code'  => 3640,
        'title' => 'Preference info success',
    ],
    '3650' => [
        'code'  => 3650,
        'title' => 'Preference info updated',
    ],


    //UserPolicy
    '5010' => [
        'code'  => 5010,
        'title' => 'Claim Create with Submit',
    ],
    '5011' => [
        'code'  => 5011,
        'title' => 'Claim Create with Save',
    ],
    '5020' => [
        'code'  => 5020,
        'title' => 'User Policy not found',
    ],
    '5030' => [
        'code'  => 5030,
        'title' => 'Claims list',
    ],
    '5040' => [
        'code'  => 5040,
        'title' => 'get advisor info success',
    ],

    //Claim
    '6010' => [
        'code'  => 6010,
        'title' => 'claim not exist',
    ],
    '6020' => [
        'code'  => 6020,
        'title' => 'claim info update with Submit',
    ],
    '6021' => [
        'code'  => 6021,
        'title' => 'claim info update with Save',
    ],
    '6030' => [
        'code'  => 6030,
        'title' => 'claim info get',
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
