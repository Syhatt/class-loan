<?php

if (!function_exists('isAdminFakultas')) {
    function isAdminFakultas($user)
    {
        return in_array($user->role, ['admin_fakultas', 'admin_ruangan', 'admin_barang']);
    }
}
