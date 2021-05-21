<?php

use Config\Services;
use Ecosystem\Authentication\Models\ActivityLog;

if (!function_exists('log_activity')) {

    function log_activity(array $log)
    {
        
        $user_id = service('userlib')->get_user()->id ?? $log['user_id'];

        $data = [
            'user_id'     => $user_id,
            'ip_address'  => Services::request()->getIPAddress(),
            'activity'    => @$log['activity'],
            'description' => isset($log['data']) ? json_encode($log['data']) : null,
        ];

        if (!empty($data['activity']) && !empty($user_id)) {
            return ActivityLog::create($data);
        }
        return false;
    }
}
