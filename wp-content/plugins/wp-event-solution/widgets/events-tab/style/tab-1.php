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
        if( !empty($event_cats ) ){
            foreach((array)$event_cats as $key=> $cat_id){

                $i++;
                $category =  get_term($cat_id);
                $category_name = (!empty($cat_id)) ? $category->name : '';
                $active_class = ($i===1) ? 'etn-active' : '';
                ?>
                <li>
                    <a href='#' class='etn-tab-a <?php echo esc_attr($active_class); ?>' data-id='tab<?php echo esc_attr($widget_id) . "-" . $i; ?>'>
                        <?php
                          echo esc_html($category_name); 
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
        if( !empty($event_cats ) ){

            $j = 0;
            foreach($event_cats as $key=> $event_cat){
                $j++;
                $event_cat = [$event_cat];
                $active_class = (($j == 1) ? 'tab-active' : '');
                ?>
                <div class="etn-tab <?php echo esc_attr($active_class); ?>" data-id='tab<?php echo esc_attr($widget_id) . "-" . $j; ?>'>
                    <?php
                    include \Wpeventin::plugin_dir() . "widgets/events/style/{$style}.php";
                    ?>
                </div>
        
            <?php
            }
        }
        ?>
    </div>
</div>