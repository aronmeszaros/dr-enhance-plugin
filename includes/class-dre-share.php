<?php

if (!defined('ABSPATH')) {
    exit;
}

class DRE_Share
{
    public function __construct()
    {
        add_filter('the_content', [$this, 'inject_share_into_content'], 20);
    }

    public function inject_share_into_content(string $content): string
    {
        if (!$this->should_inject()) {
            return $content;
        }

        $top_share = $this->render_share_block('top');
        $bottom_share = $this->render_share_block('bottom');

        return $top_share . $content . $bottom_share;
    }

    private function should_inject(): bool
    {
        return !is_admin()
            && is_singular('post')
            && in_the_loop()
            && is_main_query();
    }

    private function render_share_block(string $position): string
    {
        $url = get_permalink();
        $title = get_the_title();

        if (!$url || !$title) {
            return '';
        }

        $share_links = $this->build_share_links($url, $title);
        $panel_id = 'dre-share-panel-' . $position . '-' . get_the_ID();

        ob_start();
        ?>
        <div class="dre-share-wrapper dre-share-wrapper--<?php echo esc_attr($position); ?>">
            <button type="button" class="dre-share-toggle" aria-expanded="false" aria-controls="<?php echo esc_attr($panel_id); ?>">
                <i class="material-icons dre-share-toggle__icon" aria-hidden="true">share</i>
                <span><?php echo esc_html__('Zdieľať', 'dr-enhance'); ?></span>
            </button>

            <div id="<?php echo esc_attr($panel_id); ?>" class="dre-share-panel" hidden>
                <div class="dre-share-panel__header">
                    <h3 class="dre-share-panel__title"><?php echo esc_html__('Zdieľať', 'dr-enhance'); ?></h3>
                    <a href="#" class="btn dre-share-close" aria-label="<?php echo esc_attr__('Zavrieť zdieľanie', 'dr-enhance'); ?>" role="button">
                        <i class="material-icons" aria-hidden="true">close</i>
                    </a>
                </div>

                <div class="dre-share-actions">
                    <a class="btn dre-share-action" target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($share_links['facebook']); ?>">
                        <span class="dre-share-action__icon"><i class="material-icons" aria-hidden="true">thumb_up</i></span>
                        <span class="dre-share-action__label">Facebook</span>
                    </a>
                    <a class="btn dre-share-action" target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($share_links['linkedin']); ?>">
                        <span class="dre-share-action__icon"><i class="material-icons" aria-hidden="true">business_center</i></span>
                        <span class="dre-share-action__label">LinkedIn</span>
                    </a>
                    <a class="btn dre-share-action" target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($share_links['x']); ?>">
                        <span class="dre-share-action__icon"><i class="material-icons" aria-hidden="true">alternate_email</i></span>
                        <span class="dre-share-action__label">X</span>
                    </a>
                    <a class="btn dre-share-action" target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($share_links['threads']); ?>">
                        <span class="dre-share-action__icon"><i class="material-icons" aria-hidden="true">forum</i></span>
                        <span class="dre-share-action__label">Threads</span>
                    </a>
                    <a class="btn dre-share-action" target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($share_links['whatsapp']); ?>">
                        <span class="dre-share-action__icon"><i class="material-icons" aria-hidden="true">chat</i></span>
                        <span class="dre-share-action__label">WhatsApp</span>
                    </a>
                    <a class="btn dre-share-action" target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($share_links['messenger']); ?>">
                        <span class="dre-share-action__icon"><i class="material-icons" aria-hidden="true">send</i></span>
                        <span class="dre-share-action__label">Messenger</span>
                    </a>
                    <a class="btn dre-share-action dre-share-copy" href="<?php echo esc_url($url); ?>" data-copy-url="<?php echo esc_attr($url); ?>" data-default-label="<?php echo esc_attr__('Odkaz na stránku', 'dr-enhance'); ?>">
                        <span class="dre-share-action__icon"><i class="material-icons" aria-hidden="true">link</i></span>
                        <span class="dre-share-action__label"><?php echo esc_html__('Odkaz na stránku', 'dr-enhance'); ?></span>
                    </a>
                </div>
            </div>
        </div>
        <?php

        return (string) ob_get_clean();
    }

    private function build_share_links(string $url, string $title): array
    {
        $encoded_url = rawurlencode($url);
        $encoded_title = rawurlencode($title);
        $encoded_combo = rawurlencode($title . ' ' . $url);

        return [
            'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url,
            'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $encoded_url,
            'x' => 'https://twitter.com/intent/tweet?url=' . $encoded_url . '&text=' . $encoded_title,
            'threads' => 'https://www.threads.net/intent/post?text=' . $encoded_combo,
            'whatsapp' => 'https://wa.me/?text=' . $encoded_combo,
            'messenger' => 'https://www.facebook.com/dialog/send?link=' . $encoded_url . '&app_id=291494419107518&redirect_uri=' . $encoded_url,
        ];
    }
}
