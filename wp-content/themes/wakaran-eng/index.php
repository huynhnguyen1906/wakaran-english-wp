<?php
/**
 * Wakaran Eng Headless Theme
 * 
 * This is a headless WordPress theme.
 * Frontend is handled by a separate application.
 * 
 * Nothing to display here.
 */

// Redirect to admin or show nothing
if (is_admin()) {
    return;
}

// For headless setup, we don't output anything
exit;