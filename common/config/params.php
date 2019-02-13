<?php
return [
    'adminEmail' => 'webmaster@sibleyiowa.net',
    'supportEmail' => 'support@sibleyiowa.net',
    'user.passwordResetTokenExpire' => 3600,
    'media' => 'media/',
    'orgImagePath' => 'img/org/',
    'eventGroups' => [
        'city' => 'City of Sibley',
        'chamber' => 'Sibley Chamber of Commerce',
        'rec' => 'Sibley Recreation Department',
        'hol' => 'Holiday'
    ],
    'eventRepition' => [
        0 => 'One-Time Event',
        1 => 'Repeat Weekly',
        2 => 'Repeat Bi-Weekly',
        3 => 'Repeat Monthly',
        4 => 'Repeat Annually',
    ],
    'eventGroupColor' => [
        'city' => '#e76b32', //orange
        'rec' => '#468847', //green
        'chamber' => '#3a87ad', //blue
        'hol' => '#808080', //gray
    ],
    'eventGroupIcon' => [
        'city' => '<i class="fas fa-city"></i>', 
        'rec' => '<i class="fas fa-flag-checkered"></i>',
        'chamber' => '<i class="far fa-building"></i>',
        'hol' => '<i class="fas fa-calendar-alt"></i>',
    ],
    'contactContacts' => [
        'city' => [
            'City Administrator' => [
                'name' => 'Glenn Anderson',
                'email' => 'ctysibly@hickorytech.net'
            ],
            'City Clerk' => [
                'name' => 'Susan Sembach',
                'email' => 'sibleyclerk@premieronline.net'
            ],
        ],
        'rec' => [
            'Sibley Parks & Recreation Director' => [
                'name' => 'Sara Berndgen',
                'email' => 'sibley.rec@gmail.com'
            ]
        ],
        'chamber' => [
            'Chamber of Commerce Director' => [
                'name' => 'Ashley Goettig',
                'email' => 'chamber2@premieronline.net'
            ]
        ],
        'site' => [
            'Website Administrator' => [
                'name' => 'Lucas',
                'email' => 'lpedley@gmail.com'
            ],
        ]
    ]
];
