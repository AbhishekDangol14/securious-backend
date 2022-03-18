<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{


    public function getMenuItems()
    {
       return self::MENUITEMS[$this->role];
    }

    use HasFactory;
    private const MENUITEMS = [
        'Customer' => [
            [
                'icon' => '',
                'label' => 'Dashboard',
                'link' => 'Dashboard',
            ],
            [
                'icon' => '',
                'label' => 'Bedrohungen',
                'link' => 'customerThreat',
            ],
            [
                'icon' => '',
                'label' => 'Maßnahmen',
                'link' => 'recommendations',
            ],
            [
                'icon' => '',
                'label' => 'Zertifikate',
                'link' => 'certificates',
            ],
            [
                'icon' => '',
                'label' => 'Daten Leaks',
                'link' => 'data-leak',
            ],
            [
                'icon' => '',
                'label' => 'Komponenten',
                'link' => 'company-assets',
            ]
        ],
        'Admin' => [
            [
                'icon' => '',
                'label' => 'Threats',
                'link' => 'threats',
            ],
            [
                'icon' => '',
                'label' => 'Assets & Partners',
                'link' => 'solutionpartners',
            ],
            [
                'icon' => '',
                'label' => 'Industries',
                'link' => 'industries',
            ],
            [
                'icon' => '',
                'label' => 'News',
                'link' => 'news',
            ],
        ],
        'Partner' => [

        ],
        'IT-Security Consultant' => [
            'icon' => '',
            'label' => 'Kunden',
        ],
        [
        'icon' => '',
        'label' => 'Kundenvorschau',
            'items' => [
            [
                'icon' => '',
                'label' => 'Dashboard',
                'link' => 'dashboard',
            ],
            [
                'icon' => '',
                'label' => 'Bedrohungen',
                'link' => 'threats',
            ],
            [
                'icon' => '',
                'label' => 'Maßnahmen',
                'link' => 'recommendations',
            ],
            [
                'icon' => '',
                'label' => 'Zertifikate',
                'link' => 'certificates',
            ],
            [
                'icon' => '',
                'label' => 'Daten Leaks',
                'link' => 'data-leak',
            ],
            [
                'icon' => '',
                'label' => 'Komponenten',
                'link' => 'company-assets',
            ]
        ]
    ],
        'Employee' => [

        ],
    ];

    /**
     * @return array
     */

}
