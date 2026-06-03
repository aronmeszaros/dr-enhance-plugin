<?php

/**
 * @var $settings
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Display heading if it exists
$carousel_id = 'digiPostsCarousel_' . uniqid();
?>
<div class="digi-posts-wrapper">
    <div class="digi-posts-header">
        <?php if (!empty($settings['heading'])): ?>
            <h2 class="digi-posts-heading"><?= esc_html($settings['heading']) ?></h2>
        <?php endif; ?>
        <div class="digi-posts-arrows" data-carousel-id="<?= $carousel_id ?>">
            <button class="slick-prev-custom materialize-button btn outlined waves-effect waves-light" type="button">
                <i class="material-icons">arrow_back</i>
            </button>
            <button class="slick-next-custom materialize-button btn outlined waves-effect waves-light" type="button">
                <i class="material-icons">arrow_forward</i>
            </button>
        </div>
    </div>

    <?php
    $query = new WP_Query([
        'category_name' => esc_attr($settings['category']),
        'post_status' => 'publish',
        'posts_per_page' => -1, // Get all posts, override default limit
        'orderby' => 'date',
        'order' => 'DESC'
    ]);
    if ($query->have_posts()) { ?>
        <div id="<?= $carousel_id ?>" class="slick-carousel">
            <?php while ($query->have_posts()) {
                $query->the_post();
                $thumbnail_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'small') : get_template_directory_uri() . '/assets/img/placeholder.png';
            ?>
                <div class="carousel-item">
                    <div class="card">
                        <div class="card-image">
                            <a href="<?= esc_attr(get_permalink() ?: 'javascript:;') ?>" data-ga-event="blog_click">
                                <img src="<?= esc_url($thumbnail_url) ?>" alt="<?php the_title_attribute(); ?>">
                            </a>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title">
                                <a href="<?= esc_attr(get_permalink() ?: 'javascript:;') ?>" data-ga-event="blog_click">
                                    <?= esc_html(get_the_title()) ?>
                                </a>
                            </h3>
                            <?php if (!empty($settings['show_date']) && $settings['show_date'] === 'yes'): ?>
                                <p class="card-date"><?= esc_html(get_the_date()) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($settings['show_description']) && $settings['show_description'] === 'yes'): ?>
                                <p class="card-text"><?= wp_kses_post(get_the_excerpt()) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="card-action">
                            <a href="<?= esc_attr(get_permalink() ?: 'javascript:;') ?>" data-ga-event="blog_click">
                                <?php echo __('Čítať viac', 'digiradca') ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php
            }
            wp_reset_postdata(); ?>
        </div>
        <script>
            jQuery(document).ready(function($) {
                const carouselId = '<?= $carousel_id ?>';
                const carouselSelector = '#' + carouselId;
                const buttonsSelector = '[data-carousel-id="' + carouselId + '"]';

                // Initialize Slick Carousel
                $(carouselSelector).slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    autoplay: false,
                    autoplaySpeed: 5000,
                    arrows: false, // Disable default arrows
                    dots: false,
                    infinite: <?= !empty($settings['infinite_carousel']) && $settings['infinite_carousel'] === 'yes' ? 'true' : 'false' ?>,
                    adaptiveHeight: false,
                    cssEase: 'ease-in-out',
                    speed: 300,
                    pauseOnHover: true,
                    pauseOnFocus: true,
                    responsive: [{
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                arrows: false
                            }
                        }
                    ]
                });

                // Function to update button states for this specific carousel
                function updateButtonStates() {
                    const carousel = $(carouselSelector);
                    const currentSlide = carousel.slick('slickCurrentSlide');
                    const slideCount = carousel.slick('getSlick').slideCount;
                    const slidesToShow = carousel.slick('getSlick').options.slidesToShow;
                    const infinite = <?= !empty($settings['infinite_carousel']) && $settings['infinite_carousel'] === 'yes' ? 'true' : 'false' ?>;

                    const prevBtn = $(buttonsSelector + ' .slick-prev-custom');
                    const nextBtn = $(buttonsSelector + ' .slick-next-custom');

                    if (!infinite) {
                        // Disable/enable prev button
                        if (currentSlide === 0) {
                            prevBtn.addClass('disabled').prop('disabled', true);
                        } else {
                            prevBtn.removeClass('disabled').prop('disabled', false);
                        }

                        // Disable/enable next button
                        if (currentSlide >= slideCount - slidesToShow) {
                            nextBtn.addClass('disabled').prop('disabled', true);
                        } else {
                            nextBtn.removeClass('disabled').prop('disabled', false);
                        }
                    } else {
                        // Always enable buttons when infinite is true
                        prevBtn.removeClass('disabled').prop('disabled', false);
                        nextBtn.removeClass('disabled').prop('disabled', false);
                    }
                }

                // Initial button state
                updateButtonStates();

                // Update button states on slide change
                $(carouselSelector).on('afterChange', function() {
                    updateButtonStates();
                });

                // Custom arrow functionality for this specific carousel
                $(buttonsSelector + ' .slick-prev-custom').click(function() {
                    if (!$(this).hasClass('disabled')) {
                        $(carouselSelector).slick('slickPrev');
                    }
                });

                $(buttonsSelector + ' .slick-next-custom').click(function() {
                    if (!$(this).hasClass('disabled')) {
                        $(carouselSelector).slick('slickNext');
                    }
                });
            });

            // GA tracking
            jQuery('[data-ga-event]').on('click', function(e) {
                const target = jQuery(e.currentTarget)
                window.dataLayer = window.dataLayer || []
                window.dataLayer.push({
                    'event': target.attr('data-ga-event'),
                    'type': target.attr('data-title')
                })
            })
        </script>

        <style>
            [data-carousel-id="<?= $carousel_id ?>"] .btn.disabled {
                opacity: 0.5;
                cursor: not-allowed;
                pointer-events: none;
            }

            [data-carousel-id="<?= $carousel_id ?>"] .btn.disabled:hover {
                background-color: transparent;
                color: inherit;
            }
        </style>
</div>
<?php }
?>