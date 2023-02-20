<?php
use \Etn\Utils\Helper as Helper;
$date_format    = Helper::get_option("date_format");
$date_options   = Helper::get_date_formats();

$i = 0;

?>
<!-- schedule tab start -->
<div class="event-tab-wrapper etn-tab-wrapper event-tab-1">
    <ul class='etn-nav'>
    
        <?php
        if( !empty($tab_list ) ){
            foreach((array)$tab_list as $key=> $cat_id){
                $i++;
                $active_class = ($i===1) ? 'etn-active' : '';
                ?>
                <li>
                    <a href='#' class='etn-tab-a <?php echo esc_attr($active_class); ?>' data-id='tab<?php echo esc_attr($widget_id) . "-" . $i; ?>'>
                        <?php
                          echo esc_html($cat_id['tab_title']); 
                        ?>
                    </a>
                </li>
                <?php
             }
        }
        ?>
    </ul>

    <div class='etn-tab-content clearfix etn-schedule-wrap'>
        <?php
        if( !empty($tab_list ) ){

            $j = 0;
            foreach($tab_list as $key=> $event_cats){
                $j++;
         
                $active_class = (($j == 1) ? 'tab-active' : '');

                ?>
                <div class="etn-tab <?php echo esc_attr($active_class); ?>" data-id='tab<?php echo esc_attr($widget_id) . "-" . $j; ?>'>
                    <?php
                    $event_cat = $event_cats['etn_event_cat'];
                    $event_tag = $event_cats["etn_event_tag"];
                    $order     = (isset($event_cats["order"]) ? $event_cats["order"] : 'DESC');
                    $orderby   = $event_cats["orderby"];
                    $filter_with_status = $event_cats['filter_with_status'];

                    if ( $orderby == "etn_start_date" || $orderby == "etn_end_date" ) {
                        $orderby_meta       = "meta_value";
                    } else {
                        $orderby_meta       = null;
                    }
               
                    include \Wpeventin::plugin_dir() . "widgets/events/style/{$style}.php";
                    ?>
                </div>
        
            <?php
            }
        }
        ?>
    </div>
</div>