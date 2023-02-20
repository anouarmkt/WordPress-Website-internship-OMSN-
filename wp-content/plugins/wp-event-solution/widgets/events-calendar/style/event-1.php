<?php
if (!defined('ABSPATH')) exit;

use \Etn\Utils\Helper as Helper;

$data           = Helper::post_data_query('etn', $event_count, $order, $event_cat, 'etn_category',
 null, null, $event_tag,  $orderby_meta, $orderby, $filter_with_status);
$date_format    = Helper::get_option("date_format");
$date_options   = Helper::get_date_formats();
?>

<?php
    
    if (!empty($data)) {
        foreach ($data as $value) {

            $etn_event_location = get_post_meta($value->ID, 'etn_event_location', true);
            $etn_start_date     = get_post_meta($value->ID, 'etn_start_date', true);
            $etn_end_date     = get_post_meta($value->ID, 'etn_end_date', true);
            $category           =  Helper::cate_with_link($value->ID, 'etn_category');

            $current_date = date('Y-m-d');
            $title_single = get_the_title($value->ID);
            $url = get_permalink($value->ID);

            $json_data[] = [
                'start' => $etn_start_date,
                'end'   => $etn_end_date,
                'title' => $title_single,
                'url' => $url,
            ];

        }

    } else{
        $json_data = [];
    }    

    ?>
<div id="calendar" class="event-calendar-wrapper" data-events='<?php echo wp_json_encode($json_data); ?>'>
</div>