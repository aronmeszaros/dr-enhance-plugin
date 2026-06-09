<?php

if (!defined('ABSPATH')) {
    exit;
}

class DRE_Assets
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('wp_head', [$this, 'print_thumbnail_position_script']);
    }

    public function enqueue_frontend_assets(): void
    {
        wp_enqueue_style(
            'dre-frontend',
            DRE_PLUGIN_URL . 'assets/css/frontend.css',
            [],
            DRE_VERSION
        );

        wp_enqueue_script(
            'dre-frontend',
            DRE_PLUGIN_URL . 'assets/js/frontend.js',
            [],
            DRE_VERSION,
            true
        );

        wp_localize_script(
            'dre-frontend',
            'dreShareLabels',
            [
                'copy' => __('Kopírovať odkaz', 'dr-enhance'),
                'copied' => __('Skopírované', 'dr-enhance'),
            ]
        );
    }

    public function print_thumbnail_position_script(): void
    {
        if (!is_single() || get_post_type() !== 'post') {
            return;
        }

        $post_id = get_the_ID();
        if (!$post_id) {
            return;
        }

        $position = get_post_meta($post_id, 'umiestnenie_obrazka', true);

        if (!in_array($position, ['Hore', 'Stred', 'Dole'], true)) {
            return;
        }

        $class = esc_js('position-' . $position);

        echo '<script>document.addEventListener("DOMContentLoaded",function(){var t=document.querySelector(".post-thumbnail");if(t)t.classList.add("' . $class . '");});</script>' . "\n";
    }
}
