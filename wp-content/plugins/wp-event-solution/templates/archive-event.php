<?php
defined( 'ABSPATH' ) || exit;
use Etn\Utils\Helper as helper;

?>

<?php do_action( 'etn_before_event_archive_container' ); ?>


<div class="etn-advanced-search-form">
    <div class="etn-container">
        <?php helper::get_event_search_form(); ?>
    </div>
</div>


<div class="etn-event-archive-wrap etn_search_item_container" data-json='<?php echo json_encode([
        "error_content" => [
            "title" => esc_html__('Nothing found!', 'eventin'),
            "exerpt" => esc_html__('It looks like nothing was found here. Maybe try a search?','eventin')
        ]
    ]); ?>'>
    <div class="etn-container">
        <div class="etn-row etn-event-wrapper">

            <?php do_action( 'etn_before_event_archive_item' ); ?>

            <?php
            if (have_posts()) {

                while (have_posts()) {
                    the_post();
                    ?>
                    <div class="etn-col-md-6 etn-col-lg-<?php echo esc_attr( apply_filters( 'etn_event_archive_column', '4' ) ); ?>">

                        <div class="etn-event-item">

                            <?php do_action( 'etn_before_event_archive_content', get_the_ID(  ) ); ?>

                            <!-- content start-->
                            <div class="etn-event-content">

                                <?php do_action( 'etn_before_event_archive_title', get_the_ID(  ) ); ?>

                                <h3 class="etn-title etn-event-title">
                                    <a href="<?php echo esc_url(get_the_permalink()) ?>">
                                        <?php echo esc_html(get_the_title()); ?>
                                    </a>
                                </h3>

                                <?php do_action( 'etn_after_event_archive_title', get_the_ID(  ) ); ?>
                            </div>
                            <!-- content end-->

                            <?php do_action( 'etn_after_event_archive_content', get_the_ID(  ) ); ?>

                        </div>
                        <!-- etn event item end-->
                    </div>
                <?php
                }
            }
            ?>

            <?php do_action( 'etn_after_event_archive_item' ); ?>

        </div>

        <?php do_action('etn_event_archive_pagination');?>

    </div>
</div>

<?php do_action( 'etn_after_event_archive_container' ); ?>