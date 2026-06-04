<?php
/**
 * Plugin Name: DR Enhance
 * Description: Code snippets and style overrides for DR (Digitálny Radca).
 * Version: 1.1.0
 * Author: Aron Meszaros
 * License: GPL-2.0-or-later
 * Text Domain: dr-enhance
 */

if (!defined('ABSPATH')) {
    exit;
}

define('DRE_VERSION', '1.1.0');
define('DRE_PLUGIN_FILE', __FILE__);
define('DRE_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('DRE_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once DRE_PLUGIN_PATH . 'includes/class-dre-plugin.php';

function dre_enhance(): DRE_Plugin
{
    return DRE_Plugin::instance();
}

dre_enhance();
