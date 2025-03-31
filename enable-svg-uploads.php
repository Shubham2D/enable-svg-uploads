<?php
/**
 * Plugin Name: Enable SVG Uploads
 * Description: Allows administrators to upload SVG and SVGZ files to WordPress.
 * Version: 1.0
 * Author: Shubham Sawarkar
 * License: MIT
 */

if (!defined('ABSPATH')) exit; // Prevent direct access

/**
 * Allow SVG uploads for administrator users.
 *
 * @param array $upload_mimes Allowed mime types.
 * @return array
 */
add_filter('upload_mimes', function ($upload_mimes) {
    // Only allow SVG uploads for admins
    if (!current_user_can('administrator')) {
        return $upload_mimes;
    }

    $upload_mimes['svg']  = 'image/svg+xml';
    $upload_mimes['svgz'] = 'image/svg+xml';

    return $upload_mimes;
});

/**
 * Add additional SVG MIME type validation.
 *
 * @param array $wp_check_filetype_and_ext File validation results.
 * @param string $file Full path to the file.
 * @param string $filename The name of the file.
 * @param array $mimes Allowed MIME types.
 * @param string|false $real_mime The actual MIME type.
 * @return array
 */
add_filter('wp_check_filetype_and_ext', function ($wp_check_filetype_and_ext, $file, $filename, $mimes, $real_mime) {
    if (!$wp_check_filetype_and_ext['type']) {
        $check_filetype  = wp_check_filetype($filename, $mimes);
        $ext             = $check_filetype['ext'];
        $type            = $check_filetype['type'];
        $proper_filename = $filename;

        if ($type && 0 === strpos($type, 'image/') && 'svg' !== $ext) {
            $ext  = false;
            $type = false;
        }

        $wp_check_filetype_and_ext = compact('ext', 'type', 'proper_filename');
    }
    return $wp_check_filetype_and_ext;
}, 10, 5);