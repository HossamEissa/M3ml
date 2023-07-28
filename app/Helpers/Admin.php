<?php
if (!function_exists('get_data_of_admin')) {
    function get_data_of_admin($admin, $token)
    {
        return [
            'id' => $admin->id,
            'name' => $admin->name,
            'phone' => $admin->phone,
            'token' => $token,
        ];
    }
}
