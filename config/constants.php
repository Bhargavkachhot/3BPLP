<?php
// define('GENEREAL_USER_TYPE',0);
return [
    'USER_TYPE_GENERAL' => 1,
    'USER_TYPE_AGENT' => 2,
    'AGENTS_TYPE' => [
        'individual' => [
            'urlText' => 'individual', // key name and this value will be same.
            'label_key' => 'individual',
            'name' => 'Individual',
            'agents' => 'Individual Agents',
            'agent' => 'Individual Agent',
            'value' => 1,
        ],
        'company' => [
            'urlText' => 'company', // key name and this value will be same.
            'label_key' => 'company',
            'name' => 'Company',
            'agents' => 'Company Agents',
            'agent' => 'Company Agent',
            'value' => 2,
        ]
    ],
    'AGENT_TYPE' => [
        1 => [
            'urlText' => 'individual',
            'label_key' => 'individual',
            'name' => 'Individual',
            'agents' => 'Individual Agents',
            'agent' => 'Individual Agent',
            'value' => 1,
        ],
        2 => [
            'urlText' => 'company',
            'label_key' => 'company',
            'name' => 'Company',
            'agents' => 'Company Agents',
            'agent' => 'Company Agent',
            'value' => 2,
        ]
    ],
    'PROPERTY_FOR_SALE' => 1,
    'PROPERTY_FOR_RENT' => 2,
    'PROPERTY_FOR' => [
        1 => [
            'urlText' => 'sell', 
            'label_key' => 'sell',
            'front_label_key' => 'buy',
            'name' => 'Sale',
            'value' => 1,
            'badge_class' => 'badge-buy',
        ],
        2 => [
            'urlText' => 'rent', 
            'label_key' => 'rent',
            'front_label_key' => 'rent',
            'name' => 'Rent',
            'value' => 2,
            'badge_class' => 'badge-rent',
        ]
    ],
    'PROPERTY_FOR_TEXT' => [
        'sell' => [
            'urlText' => 'sell', 
            'label_key' => 'sell',
            'front_label_key' => 'buy',
            'name' => 'Sale',
            'value' => 1,
            'badge_class' => 'badge-buy',
        ],
        'rent' => [
            'urlText' => 'rent', 
            'label_key' => 'rent',
            'front_label_key' => 'rent',
            'name' => 'Rent',
            'value' => 2,
            'badge_class' => 'badge-rent',
        ]
    ],
    'DEFAULT_CURRENCY' => 'KD',
    'SUBSCRIPTION_TYPE' => [
        1 => [
            'urlText' => 'property_wise', 
            'label_key' => 'property_wise',
            'name' => 'Property wise',
            'value' => 1,
        ],
        2 => [
            'urlText' => 'subscription_wise', 
            'label_key' => 'subscription_wise',
            'name' => 'Subscription wise',
            'value' => 2,
        ]
    ],
    'otp_varify_type_register' => 1,
    'otp_varify_type_forgot_password' => 2,
    'PLAN_DURATION_TYPE' => [
        1 => [
            'label_key' => 'day',
            'value' => 1,
        ],
        2 => [
            'label_key' => 'week',
            'value' => 2,
        ],
        3 => [
            'label_key' => 'month',
            'value' => 3,
        ],
        4 => [
            'label_key' => 'year',
            'value' => 4,
        ],
    ],
    'UPAY_RESULT' => [
        'success' => 'CAPTURED',
        'canceled' => 'CANCELED',
    ]
];