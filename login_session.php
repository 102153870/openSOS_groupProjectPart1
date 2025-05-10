<?php
session_start();

// Initialize login attempts if not set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = 0;
}

// Check if account is locked
function isAccountLocked() {
    $lockout_duration = 300; // 5 minutes lockout
    if ($_SESSION['lockout_time'] > 0) {
        $time_left = $_SESSION['lockout_time'] + $lockout_duration - time();
        if ($time_left > 0) {
            return $time_left;
        }
        // Reset lockout if time has expired
        $_SESSION['lockout_time'] = 0;
        $_SESSION['login_attempts'] = 0;
    }
    return 0;
}
?>