<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once DRE_PLUGIN_PATH . 'includes/class-dre-assets.php';
require_once DRE_PLUGIN_PATH . 'includes/class-dre-admin.php';
require_once DRE_PLUGIN_PATH . 'includes/class-dre-share.php';

class DRE_Plugin
{
    private static ?DRE_Plugin $instance = null;

    private DRE_Assets $assets;
    private DRE_Admin $admin;
    private DRE_Share $share;

    private function __construct()
    {
        $this->assets = new DRE_Assets();
        $this->admin = new DRE_Admin();
        $this->share = new DRE_Share();
    }

    public static function instance(): DRE_Plugin
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
