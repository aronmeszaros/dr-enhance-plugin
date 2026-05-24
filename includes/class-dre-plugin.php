<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once DRE_PLUGIN_PATH . 'includes/class-dre-assets.php';

class DRE_Plugin
{
    private static ?DRE_Plugin $instance = null;

    private DRE_Assets $assets;

    private function __construct()
    {
        $this->assets = new DRE_Assets();
    }

    public static function instance(): DRE_Plugin
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
