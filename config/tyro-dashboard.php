<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Route Configuration
     |--------------------------------------------------------------------------
     |
     | Configure the dashboard routes prefix and middleware.
     |
     */
    'routes' => [
        'prefix' => env('TYRO_DASHBOARD_PREFIX', 'dashboard'),
        'middleware' => ['web', 'auth'],
        'name_prefix' => 'tyro-dashboard.',
    ],

    /*
     |--------------------------------------------------------------------------
     | Admin Roles
     |--------------------------------------------------------------------------
     |
     | Users with these roles will have full access to admin features
     | (user management, role management, privilege management, settings).
     |
     */
    'admin_roles' => ['admin', 'super-admin'],

    /*
     |--------------------------------------------------------------------------
     | User Model
     |--------------------------------------------------------------------------
     |
     | The user model to use throughout the dashboard.
     |
     */
    'user_model' => env('TYRO_DASHBOARD_USER_MODEL', 'App\\Models\\User'),

    /*
     |--------------------------------------------------------------------------
     | Pagination
     |--------------------------------------------------------------------------
     |
     | Default pagination settings for lists.
     |
     */
    'pagination' => [
        'users' => 15,
        'roles' => 15,
        'privileges' => 15,
    ],

    /*
     |--------------------------------------------------------------------------
     | Branding
     |--------------------------------------------------------------------------
     |
     | Customize the dashboard appearance.
     |
     */
    'branding' => [
        'app_name' => env('TYRO_DASHBOARD_APP_NAME', 'MetroNet'),
        'logo' => env('TYRO_DASHBOARD_LOGO', '/assets/branding/metronet-logo.png'),
        'logo_height' => env('TYRO_DASHBOARD_LOGO_HEIGHT', '40px'),
        'favicon' => env('TYRO_DASHBOARD_FAVICON', '/assets/branding/metronet-logo.png'),

        // Sidebar colors (supports any CSS color value: hex, rgb, hsl, etc.)
        'sidebar_bg' => env('TYRO_DASHBOARD_SIDEBAR_BG', null), // Custom background color for sidebar
        'sidebar_text' => env('TYRO_DASHBOARD_SIDEBAR_TEXT', null), // Custom text color for sidebar
    ],

    /*
     |--------------------------------------------------------------------------
     | Admin Bar
     |--------------------------------------------------------------------------
     |
     | Configuration for the admin notice bar displayed at the top of the dashboard.
     |
     */
    'admin_bar' => [
        'enabled' => env('TYRO_DASHBOARD_ADMIN_BAR_ENABLED', false),
        'message' => env('TYRO_DASHBOARD_ADMIN_BAR_MESSAGE', ''),
        'bg_color' => env('TYRO_DASHBOARD_ADMIN_BAR_BG_COLOR', '#000000'),
        'text_color' => env('TYRO_DASHBOARD_ADMIN_BAR_TEXT_COLOR', '#ffffff'),
        'align' => env('TYRO_DASHBOARD_ADMIN_BAR_ALIGN', 'left'),
        'height' => env('TYRO_DASHBOARD_ADMIN_BAR_HEIGHT', '40px'),
    ],

    /*
     |--------------------------------------------------------------------------
     | Collapsible Sidebar
     |--------------------------------------------------------------------------
     |
     | Enable or disable the collapsible sidebar feature.
     |
     */
    'collapsible_sidebar' => env('TYRO_DASHBOARD_COLLAPSIBLE_SIDEBAR', true),

    /*
     |--------------------------------------------------------------------------
     | Features
     |--------------------------------------------------------------------------
     |
     | Enable or disable specific dashboard features.
     |
     */
    'features' => [
        'user_management' => false,
        'role_management' => true,
        'privilege_management' => true,
        'settings_management' => true,
        'profile_management' => true,
        'invitation_system' => env('TYRO_DASHBOARD_ENABLE_INVITATION', true),
        'audit_logs' => env('TYRO_DASHBOARD_ENABLE_AUDIT_LOGS', true),
        'activity_log' => false, // Future feature
        'profile_photo_upload' => env('TYRO_DASHBOARD_ENABLE_PROFILE_PHOTO', false),
        'gravatar' => env('TYRO_DASHBOARD_ENABLE_GRAVATAR', false),
    ],

    /*
     |--------------------------------------------------------------------------
     | Protected Resources
     |--------------------------------------------------------------------------
     |
     | Resources that cannot be deleted through the dashboard.
     |
     */
    'protected' => [
        'roles' => ['admin', 'super-admin', 'user'],
        'users' => [], // Add user IDs that cannot be deleted
    ],

    /*
     |--------------------------------------------------------------------------
     | Dashboard Widgets
     |--------------------------------------------------------------------------
     |
     | Configure which widgets appear on the dashboard home.
     |
     */
    'widgets' => [
        'stats' => true,
        'recent_users' => true,
        'role_distribution' => true,
    ],

    /*
     |--------------------------------------------------------------------------
     | Notifications
     |--------------------------------------------------------------------------
     |
     | Configure dashboard notifications behavior.
     |
     */
    'notifications' => [
        'show_flash_messages' => true,
        'auto_dismiss_seconds' => 5,
    ],

    /*
     |--------------------------------------------------------------------------
     | File Upload Configuration
     |--------------------------------------------------------------------------
     |
     | Configure default settings for file uploads in resources.
     |
     */
    'uploads' => [
        'disk' => env('TYRO_DASHBOARD_UPLOAD_DISK', 'public'),
        'directory' => env('TYRO_DASHBOARD_UPLOAD_DIRECTORY', 'uploads'),
        'auto_delete_on_resource_delete' => env('TYRO_DASHBOARD_AUTO_DELETE_UPLOADS', true),
    ],

    /*
     |--------------------------------------------------------------------------
     | Profile Photo Configuration
     |--------------------------------------------------------------------------
     |
     | Configure settings for user profile photos and gravatar support.
     |
     */
    'profile_photo' => [
        'disk' => env('TYRO_DASHBOARD_PROFILE_PHOTO_DISK', 'public'),
        'directory' => env('TYRO_DASHBOARD_PROFILE_PHOTO_DIRECTORY', 'profile_images'),
        'max_size' => env('TYRO_DASHBOARD_PROFILE_PHOTO_MAX_SIZE', 10240), // in KB (default 10MB)
        'width' => env('TYRO_DASHBOARD_PROFILE_PHOTO_WIDTH', 400),
        'height' => env('TYRO_DASHBOARD_PROFILE_PHOTO_HEIGHT', 400),
        'quality' => env('TYRO_DASHBOARD_PROFILE_PHOTO_QUALITY', 90),
        'crop_position' => env('TYRO_DASHBOARD_PROFILE_PHOTO_CROP', 'center'), // top, center, bottom
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'auto_delete_on_user_delete' => true,
    ],

    /*
     |--------------------------------------------------------------------------
     | Dynamic Resources (CRUD)
     |--------------------------------------------------------------------------
     |
     | Define your resources here to automatically generate CRUD interfaces.
     |
     */
    'resources' => [
        'visits' => [
            'model' => 'App\Models\SalesVisitEntry',
            'title' => 'Sales Visit', 
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
            'roles' => ['super-admin', 'admin', 'manager', 'team-leader', 'marketing-executive'],
            'custom_route' => 'tyro-dashboard.sales-visits.index',
        ],
        'calls' => [
            'model' => 'App\Models\SalesCall',
            'title' => 'Sales Call',
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>',
            'roles' => ['super-admin', 'admin', 'manager', 'team-leader', 'marketing-executive'],
            'custom_route' => 'tyro-dashboard.sales-calls.index',
            'fields' => [
                'lead_id' => ['type' => 'select', 'label' => 'Lead', 'relationship' => 'lead', 'option_label' => 'company_name', 'rules' => 'required'],
                'outcome' => ['type' => 'text', 'label' => 'Outcome'],
                'notes' => ['type' => 'textarea', 'label' => 'Notes'],
            ],
        ],
        'leads' => [
            'model' => 'App\Models\Lead',
            'title' => 'Sales Lead',
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>',
            'roles' => ['super-admin', 'admin', 'manager', 'team-leader', 'marketing-executive'],
            'fields' => [
                'company_name' => ['type' => 'text', 'label' => 'Company', 'rules' => 'required'],
                'client_name' => ['type' => 'text', 'label' => 'Client/Account Name', 'rules' => 'required'],
                'phone' => ['type' => 'text', 'label' => 'Phone', 'rules' => 'required'],
                'status' => ['type' => 'select', 'label' => 'Status', 'options' => ['new' => 'New', 'contacted' => 'Contacted', 'interested' => 'Interested', 'closed' => 'Closed', 'lost' => 'Lost'], 'rules' => 'required'],
                'assigned_user' => ['type' => 'select', 'label' => 'Assigned To', 'relationship' => 'assignedUser', 'option_label' => 'name'],
            ],
        ],
        'follow-ups' => [
            'model' => 'App\Models\FollowUp',
            'title' => 'Sales Followup',
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            'roles' => ['super-admin', 'admin', 'manager', 'team-leader', 'marketing-executive'],
            'fields' => [
                'lead_id' => ['type' => 'select', 'label' => 'Lead', 'relationship' => 'lead', 'option_label' => 'company_name', 'rules' => 'required'],
                'scheduled_at' => ['type' => 'date', 'label' => 'Scheduled At', 'rules' => 'required'],
                'notes' => ['type' => 'textarea', 'label' => 'Notes'],
            ],
        ],
        'targets' => [
            'model' => 'App\Models\QuarterlyTarget',
            'title' => 'Sales Target',
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>',
            'roles' => ['super-admin', 'admin', 'manager', 'team-leader'],
            'fields' => [
               'user_id' => ['type' => 'select', 'label' => 'User', 'relationship' => 'user', 'option_label' => 'name', 'rules' => 'required'],
               'target_amount' => ['type' => 'number', 'label' => 'Target Amount', 'rules' => 'required'],
               'quarter' => ['type' => 'number', 'label' => 'Quarter', 'rules' => 'required'],
               'year' => ['type' => 'number', 'label' => 'Year', 'rules' => 'required'],
            ],
        ],
        'achievements' => [
            'model' => 'App\Models\Sale',
            'title' => 'Sales Achievement',
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>',
            'roles' => ['super-admin', 'admin', 'manager', 'team-leader', 'marketing-executive'],
            'readonly' => ['marketing-executive'],
            'fields' => [
               'lead_id' => ['type' => 'select', 'label' => 'Lead', 'relationship' => 'lead', 'option_label' => 'company_name', 'rules' => 'required'],
               'amount' => ['type' => 'number', 'label' => 'Achievement Amount', 'rules' => 'required'],
            ],
        ],
        'sales' => [
            'model' => 'App\Models\Sale',
            'title' => 'Total Sales',
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            'roles' => ['super-admin', 'admin', 'manager', 'team-leader', 'marketing-executive'],
            'fields' => [
                'lead_id' => ['type' => 'select', 'label' => 'Lead', 'relationship' => 'lead', 'option_label' => 'company_name', 'rules' => 'required'],
                'amount' => ['type' => 'number', 'label' => 'Amount', 'rules' => 'required'],
                'service_id' => ['type' => 'select', 'label' => 'Product', 'relationship' => 'service', 'option_label' => 'name', 'rules' => 'required'],
                'user_id' => ['type' => 'select', 'label' => 'Executive', 'relationship' => 'user', 'option_label' => 'name', 'rules' => 'required'],
                'closed_at' => ['type' => 'date', 'label' => 'Sale Date', 'rules' => 'required'],
            ],
        ],
        'services' => [
            'model' => 'App\Models\Service',
            'title' => 'Products/Services',
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>',
            'fields' => [
                'name' => ['type' => 'text', 'label' => 'Name', 'rules' => 'required'],
                'description' => ['type' => 'textarea', 'label' => 'Description'],
            ],
        ],
        'service-packages' => [
            'model' => 'App\Models\ServicePackage',
            'title' => 'Service Packages',
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>',
            'fields' => [
                'service_id' => ['type' => 'select', 'label' => 'Service', 'relationship' => 'service', 'option_label' => 'name', 'rules' => 'required'],
                'name' => ['type' => 'text', 'label' => 'Package Name', 'rules' => 'required'],
                'price' => ['type' => 'number', 'label' => 'Price', 'rules' => 'required'],
                'description' => ['type' => 'textarea', 'label' => 'Description'],
            ],
        ],
        'notes' => [
            'model' => 'App\Models\Note',
            'title' => 'Notes',
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
            'roles' => ['super-admin', 'admin', 'manager', 'team-leader', 'marketing-executive'],
            'fields' => [
                'lead_id' => ['type' => 'select', 'label' => 'Lead', 'relationship' => 'lead', 'option_label' => 'company_name', 'rules' => 'required'],
                'user_id' => ['type' => 'select', 'label' => 'Added By', 'relationship' => 'user', 'option_label' => 'name', 'rules' => 'required'],
                'content' => ['type' => 'textarea', 'label' => 'Note Content', 'rules' => 'required'],
            ],
        ],
    ],

    /*
     |--------------------------------------------------------------------------
     | Resource UI Settings
     |--------------------------------------------------------------------------
     |
     | Configure the appearance and behavior of resource forms and lists.
     |
     */
    'resource_ui' => [
        'show_global_errors' => env('TYRO_SHOW_GLOBAL_ERRORS', true),
        'show_field_errors' => env('TYRO_SHOW_FIELD_ERRORS', true),
    ],

    /*
     |--------------------------------------------------------------------------
     | Disable Examples
     |--------------------------------------------------------------------------
     |
     | If this is true, the "Examples" section in the sidebar will be hidden
     | and the example routes will be disabled.
     |
     */
    'disable_examples' => env('TYRO_DASHBOARD_DISABLE_EXAMPLES', false),
];
