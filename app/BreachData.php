<?php

namespace App;

class BreachData
{
    public static $dataTypes = [
        'name.first' => 'First Name',
        'name.last' => 'Last Name',
        'account.email' => 'Email Address',
        'account.security' => 'Account Security Question',
        'account.password' => 'Account Password',
        'phone.landline' => 'Landline Number',
        'phone.mobile' => 'Mobile Number',
        'address.house' => 'House Number',
        'address.road' => 'Road Name',
        'address.city' => 'City',
        'address.postcode' => 'Postcode',
        'address.country' => 'Country',
        'bank.card.number' => 'Credit/Debit Card Number',
        'bank.card.pin' => 'Credit/Debit Card PIN',
        'bank.card.end' => 'Credit/Debit Card Expiry Date',
        'bank.card.start' => 'Credit/Debit Card Start Date',
        'bank.card.cv2' => 'Credit/Debit Card CV2',
        'bank.name' => 'Bank Name',
        'bank.sort' => 'Bank Sort Code',
        'bank.account' => 'Bank Account Number',
        'gov.ni' => 'National Insurance Number',
        'gov.passport' => 'Passport Number',
        'other' => 'Other Data'
    ];

    public static $methods = [
        'Hack' => [
            'title' => 'Hacking',
            'description' => 'A technical vulnerability such as SQL injection or remote code execution.'
        ],
        'Social' => [
            'title' => 'Social Engineering',
            'description' => 'Psychological manipulation of employees to obtain access to data.'
        ],
        'Employee' => [
            'title' => 'Organisation Employee',
            'description' => 'An employee stole or leaked data.'
        ],
        'Organisation' => [
            'title' => 'Organisation Action/Policy',
            'description' => 'An organisation policy such as selling customer data to other companies.'
        ],
        'Physical' => [
            'title' => 'Physical Data Theft or Loss',
            'description' => 'A physical data medium was stolen or lost. For example USB containing customer data left on a train.'
        ],
        'Other' => [
            'title' => 'Other',
            'description' => 'A breach not covered by the above categories.'
        ],
        'Unknown' => [
            'title' => 'Unknown',
            'description' => 'The breach method is not known.'
        ]
    ];

    public static $responseStances = [
        'Ignored' => 'No organisation response',
        'Denied' => 'Organisation denied breach',
        'Partial' => 'Organisation partial admission',
        'Full' => 'Organisation full admission'
    ];

    public static $responseStanceModifiers = [
        'Ignored' => 1,
        'Denied' => 1,
        'Partial' => 0.5,
        'Full' => 0
    ];

    public static $responsePatched = [
        'NotRequired' => 'No patch required',
        'Patched1' => 'Patched within 24 hours',
        'Patched2' => 'Patched within 48 hours',
        'Patched4' => 'Patched within 72 hours',
        'Patched7' => 'Patched within 1 week',
        'Patched30' => 'Patched within 1 month',
        'NotPatched' => 'Not patched'
    ];

    public static $responsePatchedModifiers = [
        'NotRequired' => 0,
        'Patched1' => 0,
        'Patched2' => 0.25,
        'Patched4' => 0.5,
        'Patched7' => 0.75,
        'Patched30' => 1,
        'NotPatched' => 1
    ];

    public static $responseCustomersInformed = [
        'Informed1' => 'Informed within 24 hours',
        'Informed2' => 'Informed within 48 hours',
        'Informed4' => 'Informed within 72 hours',
        'Informed7' => 'Informed within 1 week',
        'Informed30' => 'Informed within 1 month',
        'NotInformed' => 'Not informed'
    ];

    public static $responseCustomersInformedModifiers = [
        'Informed1' => 0,
        'Informed2' => 0.125,
        'Informed4' => 0.25,
        'Informed7' => 0.375,
        'Informed30' => 0.5,
        'NotInformed' => 0.5
    ];

    public static $initialModifier = 1;
    
    public static $previouslyKnownModifier = 1;
}