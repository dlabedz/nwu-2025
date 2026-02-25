<?php
/**
 * Chapter Details Block
 *
 * @package NWU2025
 */

$caption = get_field( 'chapter_image_caption' );
$contact_info = get_field( 'chapter_contact_info' );
$has_thumbnail = has_post_thumbnail();

// In admin, always show the block container so fields are visible
$show_block = is_admin() || $has_thumbnail || $caption || $contact_info;

if ( ! $show_block ) {
    return;
}

// Add admin-only class for styling
$classes = 'chapter-details-block';
if ( is_admin() ) {
    $classes .= ' chapter-details-block--editor';
}
?>

<div class="<?php echo esc_attr( $classes ); ?>">
    <?php if ( is_admin() ) : ?>
        <!-- Editor View: Just show instructions -->
        <div class="chapter-details-block__editor-notice">
            <p><strong>Chapter Details Block</strong></p>
            <p>Fill in the fields below. The image caption and contact info will display alongside the featured image on the front end.</p>
        </div>
    <?php else : ?>
        <!-- Frontend View: Full chapter header layout -->
        <div class="chapter-header">

            <?php if ( $has_thumbnail || $caption ) : ?>
                <div class="chapter-header__image-column">

                    <?php if ( $has_thumbnail ) : ?>
                        <div class="chapter-header__image">
                            <?php
                            the_post_thumbnail(
                                'large',
                                array(
                                    'alt'     => esc_attr( get_the_title() ),
                                    'loading' => 'eager',
                                )
                            );
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $caption ) : ?>
                        <div class="chapter-header__caption">
                            <?php echo wp_kses_post( wpautop( $caption ) ); ?>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endif; ?>

            <?php if ( $contact_info ) : ?>
                <div class="chapter-header__contact-column">
                    <div class="chapter-header__contact">
                        <?php echo wp_kses_post( $contact_info ); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    <?php endif; ?>
</div>
