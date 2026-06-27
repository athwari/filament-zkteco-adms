<?php

return [
    'navigation' => [
        'group' => 'ZKTeco ADMS',
        'dashboard' => 'ZKTeco Dashboard',
    ],

    'models' => [
        'device' => [
            'label' => 'Device',
            'plural_label' => 'Devices',
        ],
        'attendance_log' => [
            'label' => 'Attendance Log',
            'plural_label' => 'Attendance Logs',
        ],
        'user' => [
            'label' => 'ZKTeco User',
            'plural_label' => 'ZKTeco Users',
        ],
        'device_command' => [
            'label' => 'Device Command',
            'plural_label' => 'Device Commands',
        ],
        'device_event' => [
            'label' => 'Device Event',
            'plural_label' => 'Device Events',
        ],
    ],

    'enums' => [
        'device_status' => [
            'online' => 'Online',
            'offline' => 'Offline',
            'unknown' => 'Unknown',
        ],
        'command_status' => [
            'pending' => 'Pending',
            'sent' => 'Sent',
            'acknowledged' => 'Acknowledged',
            'failed' => 'Failed',
        ],
        'command_type' => [
            'INFO' => 'Info',
            'REBOOT' => 'Reboot',
            'CLEAR' => 'Clear',
            'DATA' => 'Data',
            'CHECK' => 'Check',
        ],
        'attendance_status' => [
            'check_in' => 'Check In',
            'check_out' => 'Check Out',
            'break_out' => 'Break Out',
            'break_in' => 'Break In',
            'overtime_in' => 'Overtime In',
            'overtime_out' => 'Overtime Out',
        ],
        'user_privilege' => [
            'user' => 'User',
            'admin' => 'Admin',
        ],
        'device_event_type' => [
            'registered' => 'Registered',
            'connected' => 'Connected',
            'disconnected' => 'Disconnected',
            'info_received' => 'Info Received',
            'command_sent' => 'Command Sent',
            'command_acknowledged' => 'Command Acknowledged',
            'attendance_synced' => 'Attendance Synced',
            'user_synced' => 'User Synced',
            'status_changed' => 'Status Changed',
        ],
    ],

    'widgets' => [
        'overview' => [
            'total_devices' => 'Total Devices',
            'online_devices' => 'Online Devices',
            'today_attendance' => "Today's Attendance",
            'pending_commands' => 'Pending Commands',
        ],
    ],

    'actions' => [
        'get_info' => 'Get Info',
        'reboot' => 'Reboot',
        'clear_logs' => 'Clear Logs',
        'clear_all_data' => 'Clear All Data',
        'clear_users' => 'Clear Users',
        'sync_time' => 'Sync Time',
        'check_connection' => 'Check Connection',
        'retry' => 'Retry',
    ],
];
