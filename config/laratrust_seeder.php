<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'superadmin' => [
            'users'           => 'c,r,u,d',
            'roles'           => 'c,r,u,d',
            'permissions'     => 'c,r,u,d',
            'sliders'         => 'c,r,u,d',
            'teams'           => 'c,r,u,d',
            'services'        => 'c,r,u,d',
            'social-contacts' => 'c,r,u,d',
            'categories'      => 'c,r,u,d',
            'tags'            => 'c,r,u,d',
            'blog-posts'      => 'c,r,u,d',
            'designations'    => 'c,r,u,d',
            'applications'    => 'c,r,u,d',
            'countries'       => 'c,r,u,d',
            'cities'          => 'c,r,u,d',
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
