<!-- General Tab -->
<div class="etn-settings-section attr-tab-pane" data-id="tab1" id="etn-general_options">
    <div class="etn-settings-single-section">
        <div class="etn-recaptcha-settings-wrapper">
            <div class="etn-recaptcha-settings">
                <div class="etn-settings-tab-wrapper etn-settings-tab-style">
                    <ul class="etn-settings-nav">
                        <?php do_action( 'etn_before_general_settings_inner_tab_heading' ); ?>
                        <li>
                            <a class="etn-settings-tab-a etn-settings-active" data-id="general-options">
                                <?php echo esc_html__('Event Display Settings', 'eventin'); ?>
                                <svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>
                            </a>
                        </li>
                        <li>
                            <a class="etn-settings-tab-a" data-id="date-format-settings">
                                <?php echo esc_html__('Date Format Settings', 'eventin'); ?>
                                <svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>
                            </a>
                        </li>
                        <li>
                            <a class="etn-settings-tab-a" data-id="slug-options">
                                <?php echo esc_html__('Slug Settings', 'eventin'); ?>
                                <svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>
                            </a>
                        </li>                       
                        <li>
                            <a class="etn-settings-tab-a" data-id="parchase-report-settings">
                                <?php echo esc_html__( 'Woo/Purchase Settings ', 'eventin-pro' ); ?>
                                <svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>
                            </a>
                        </li>

                        <li>
                            <a class="etn-settings-tab-a" data-id="color-options">
                                <?php echo esc_html__('Style', 'eventin'); ?>
                                <svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>
                            </a>
                        </li>

                        <?php do_action( 'etn_after_general_settings_inner_tab_heading' ); ?>
                        
                    </ul>
                    <div class="etn-settings-tab-content">
                        
                        <?php do_action( 'etn_before_general_settings_inner_tab_content' ); ?>

                        <div class="etn-settings-tab" id="general-options"> 

                            <?php do_action( 'etn_before_general_settings' ); ?>

                            <div class="attr-form-group etn-label-item">
                                <div class="etn-label">
                                    <label>
                                        <?php esc_html_e('Events Show Per Page', 'eventin'); ?>
                                    </label>
                                    <div class="etn-desc"> <?php esc_html_e('Number of events to show in Archive page.', 'eventin'); ?> </div>
                                </div>
                                <div class="etn-meta">
                                    <input type="number" name="events_per_page" step="1" min="1" value="<?php echo esc_attr(isset( $events_per_page) ? $events_per_page : 10); ?>" class="etn-setting-input attr-form-control">
                                </div>
                            </div>
                            <div class="attr-form-group etn-label-item">
                                <div class="etn-label">
                                    <label for="captcha-method"><?php esc_html_e( 'Select Event Template', 'eventin' );?></label>
                                    <div class="etn-desc"> <?php esc_html_e( 'Event single page template.', 'eventin' );?> </div>
                                </div>
                                <div class="etn-meta">
                                    <select id="event_template" name="event_template" class="etn-setting-input attr-form-control etn-settings-select">
                                        <option value=''> --- </option>
                                        <?php 
                                        foreach( $event_template_array as $key => $value ){
                                            ?>
                                            <option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_html( selected( $selected_event_template, $key, false ) ); ?>> <?php echo esc_html( $value ); ?> </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="attr-form-group etn-label-item">
                                <div class="etn-label">
                                    <label for="captcha-method"><?php esc_html_e( 'Select Speaker Template', 'eventin' );?></label>
                                    <div class="etn-desc"> <?php esc_html_e( 'Speaker single page template.', 'eventin' );?> </div>
                                </div>
                                <div class="etn-meta">
                                    <select id="speaker_template" name="speaker_template" class="etn-setting-input attr-form-control etn-settings-select">
                                        <option value=''> --- </option>
                                        <?php 
                                        foreach( $speaker_template_array as $key => $value ){
                                            ?>
                                            <option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_html( selected( $selected_speaker_template, $key, false ) ); ?>> <?php echo esc_html( $value ); ?> </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="attr-form-group etn-label-item">
                                <div class="etn-label">
                                    <label>
                                        <?php esc_html_e('Include Into Search', 'eventin'); ?>
                                    </label>
                                    <div class="etn-desc"> <?php esc_html_e('Include event in search and enable taxonomy on archive page.', 'eventin'); ?> </div>
                                </div>
                                <div class="etn-meta">
                                    <select value='on' id="checked_exclude_from_search" name='etn_include_from_search' class="etn-setting-input attr-form-control etn-settings-select">
                                        <option value=''> --- </option>
                                        <option value='on' <?php echo esc_html( selected( $checked_exclude_from_search, 'on', false ) ); ?>> <?php echo esc_html__( 'Yes', 'eventin' ); ?> </option>
                                        <option value='off' <?php echo esc_html( selected( $checked_exclude_from_search, 'off', false ) ); ?>> <?php echo esc_html( 'No', 'eventin' ); ?> </option>
                                    </select>
                                </div>
                            </div>
                            <div class="attr-form-group etn-label-item">
                                <div class="etn-label">
                                    <label>
                                        <?php esc_html_e( 'Disable Registration After Event has Expired', 'eventin' );?>
                                    </label>
                                    <div class="etn-desc"> <?php esc_html_e( 'Turn off registration once event has expired', 'eventin' );?> </div>
                                </div>
                                <div class="etn-meta">
                                    <input id='disable_registration_if_expired' type="checkbox" <?php echo esc_html( $is_registration_disabled_if_expired ); ?> class="etn-admin-control-input etn-form-modalinput-paypal_sandbox" name="disable_registration_if_expired" />
                                    <label for="disable_registration_if_expired" class="etn_switch_button_label"></label>
                                </div>
                            </div>
                            
                            <?php do_action( 'etn_after_general_settings' ); ?>

                        </div>

                        <div class="etn-settings-tab" id="date-format-settings">
                            <div class="attr-form-group etn-label-item">
                                <div class="etn-label">
                                    <label for="captcha-method"><?php esc_html_e('Select Time Format:', 'eventin'); ?></label>
                                    <div class="etn-desc"> <?php esc_html_e('Select time format. For instance 12h.', 'eventin'); ?> </div>
                                </div>
                                <div class="etn-meta">
                                    <select id="time_format" name="time_format" class="etn-setting-input attr-form-control etn-settings-select">
                                        <option value=''> --- </option>
                                        <option value="24" <?php echo esc_html(selected($selected_time_format, '24', false)); ?>> <?php echo esc_html__('24h', 'eventin'); ?> </option>
                                        <option value="12" <?php echo esc_html(selected($selected_time_format, '12', false)); ?>><?php echo esc_html__('12h', 'eventin'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="attr-form-group etn-label-item">
                                <div class="etn-label">
                                    <label for="captcha-method"><?php esc_html_e( 'Select Event Expiry Time', 'eventin' );?></label>
                                    <div class="etn-desc"> <?php esc_html_e( 'Set event expiry time and filter events in event report using selected time.', 'eventin' );?> </div>
                                </div>
                                <div class="etn-meta">
                                    <select id="expiry_point" name="expiry_point" class="etn-setting-input attr-form-control etn-settings-select">
                                        <option value=''> --- </option>
                                        <option value="start" <?php echo esc_html( selected( $selected_expiry_point, 'start', false ) ); ?>> <?php echo esc_html__( 'Starting Date', 'eventin' ); ?> </option>
                                        <option value="end" <?php echo esc_html( selected( $selected_expiry_point, 'end', false ) ); ?>><?php echo esc_html__( 'Ending Date', 'eventin' ); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="attr-form-group etn-label-item">
                                <div class="etn-label">
                                    <label class="etn-setting-label" for="captcha-method"><?php esc_html_e('Select Date Format', 'eventin'); ?></label>
                                    <div class="etn-desc"> <?php esc_html_e('Select date format to display. For instance DD-MM-YYYY.', 'eventin'); ?> </div>
                                </div>
                                <div class="etn-meta">
                                    <select id="date_format" name="date_format" class="etn-setting-input attr-form-control etn-settings-select">
                                        <option value=''> --- </option>
                                        <?php
                                        if( is_array( $date_formats ) ){
                                            foreach ($date_formats as $key => $date_format) {
                                                ?>
                                                <option <?php echo esc_html( selected( $selected_date_format, $key, false) ); ?> value='<?php echo esc_attr( $key ); ?>'> <?php echo esc_html( date_i18n( $date_format, $sample_date) ); ?> </option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>  
                            
                        </div>

                        <div class="etn-settings-tab" id="slug-options">
                            <!-- slug change -->
                            <?php do_action( 'etn_before_slug_settings' ); ?>

                            <div class="attr-form-group etn-label-item">
                                <div class="etn-label">
                                    <label><?php esc_html_e('Event Slug Name', 'eventin'); ?> </label>
                                    <div class="etn-desc"> <?php esc_html_e('Place event slug here', 'eventin'); ?> </div>

                                </div>
                                <div class="etn-meta">
                                    <input type="text" name="event_slug" value="<?php echo esc_attr(isset($event_slug) ? $event_slug : ''); ?>" class="etn-setting-input attr-form-control etn-recaptcha-secret-key" placeholder="<?php esc_html_e('Enter event slug', 'eventin'); ?>">
                                </div>
                            </div>
                            <div class="attr-form-group etn-label-item">
                                <div class="etn-label">
                                    <label><?php esc_html_e('Speaker Slug Name', 'eventin'); ?> </label>
                                    <div class="etn-desc"> <?php esc_html_e('Place speaker slug here', 'eventin'); ?> </div>

                                </div>
                                <div class="etn-meta">
                                    <input type="text" name="speaker_slug" value="<?php echo esc_attr(isset( $speaker_slug) ? $speaker_slug : ''); ?>" class="etn-setting-input attr-form-control etn-recaptcha-secret-key" placeholder="<?php esc_html_e('Enter speaker slug', 'eventin'); ?>">
                                </div>
                            </div>

                            <?php do_action( 'etn_after_slug_settings' ); ?>
                        </div>

                        <div class="etn-settings-tab" id="color-options">
                            <!-- Color option  -->
                            <?php do_action( 'etn_before_color_settings' ); ?>
                            <div class="attr-form-group etn-label-item etn-label-top">
                                <div class="etn-label">
                                    <label><?php esc_html_e('Primary Color', 'eventin'  ); ?></label>
                                    <div class="etn-desc"> <?php esc_html_e("Choose primary color", 'eventin'  ); ?> </div>
                                </div>
                                <div class="etn-meta">
                                    <input type="text" name="etn_primary_color" id="etn_primary_color"
                                    value="<?php echo esc_attr( isset($settings['etn_primary_color'] ) ? $settings['etn_primary_color'] : ''); ?>"
                                    />
                                </div>
                            </div>
                            <div class="attr-form-group etn-label-item  etn-label-top">
                                <div class="etn-label">
                                    <label for="etn_secondary_color"><?php esc_html_e('Secondary Color', 'eventin'  ); ?></label>
                                    <div class="etn-desc"> <?php esc_html_e("Choose secondary color", 'eventin'  ); ?> </div>
                                </div>
                                <div class="etn-meta">
                                    <input type="text" name="etn_secondary_color" id="etn_secondary_color"
                                    value="<?php echo esc_attr( isset($settings['etn_secondary_color'] ) ? $settings['etn_secondary_color'] : ''); ?>"
                                    />
                                </div>
                            </div>
                            <?php do_action( 'etn_after_color_settings' ); ?>
                        </div>

                        <div class="etn-settings-tab" id="parchase-report-settings">
                            <!-- purchase settings option  -->
                            <?php do_action( 'etn_before_purchase_report_settings' ); ?>

                            <div class="attr-form-group etn-label-item etn-label-top">
                                <div class="etn-label">
                                    <label>
                                        <?php esc_html_e('Sell Ticket on WooCommerce', 'eventin'); ?>
                                    </label>
                                    <div class="etn-desc mb-2"> <?php esc_html_e('This will require WooCommerce plugin.', 'eventin'); ?> </div>
                                </div>
                                <div class="etn-meta">
                                    <input id="sell_tickets" type="checkbox" <?php echo esc_html($sell_tickets); ?> class="etn-admin-control-input" name="sell_tickets"  value="woocommerce" />
                                    <label for="sell_tickets" data-left="Yes" data-right="No" class="etn_switch_button_label"></label>
                                </div>

                                <?php
                                $woocommerce_class = ( $etn_sells_engine_woocommerce == 'checked' ) ?  'woocommerce_section' : 'woocommerce_section_hide';
                                ?>

                                <div class="woocommerce-payment-type <?php echo esc_attr($woocommerce_class); ?>" id="woocommerce-payment-type">
                                    <div class="attr-form-group etn-label-item" id="etn-add-to-cart-redirect-settings">
                                        <div class="etn-label">
                                            <label>
                                                <?php esc_html_e('Redirect After Adding to Cart ', 'eventin'); ?>
                                            </label>
                                            <div class="etn-desc"> <?php esc_html_e('After adding an event to cart, redirect to the following page.', 'eventin'); ?> </div>
                                        </div>
                                        <div class="etn-meta">
                                            <select id="date_format" name="add_to_cart_redirect" class="etn-setting-input attr-form-control etn-settings-select">
                                                <option value=''> --- </option>
                                                <?php
                                                if( is_array( $redirect_after_cart_array ) ){
                                                    foreach ($redirect_after_cart_array as $key => $redirect_after_cart) {
                                                        ?>
                                                        <option <?php echo esc_html( selected( $selected_add_to_cart_redirect, $key, false) ); ?> value='<?php echo esc_attr( $key ); ?>'> <?php echo esc_html( $redirect_after_cart ); ?> </option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <?php do_action( 'etn_after_woocommerce_fields_settings' ); ?>

                                    <div class="attr-form-group etn-label-item">
                                        <div class="etn-label">
                                            <label>
                                                <?php esc_html_e('Require Login', 'eventin'); ?>
                                            </label>
                                            <div class="etn-desc"> <?php esc_html_e('Require login to purchase event ticket', 'eventin'); ?> </div>
                                        </div>
                                        <div class="etn-meta">
                                            <input id="checked_purchase_login_required" type="checkbox" <?php echo esc_html($checked_purchase_login_required); ?> class="etn-admin-control-input etn-form-modalinput-paypal_sandbox" name="etn_purchase_login_required" />
                                            <label for="checked_purchase_login_required" class="etn_switch_button_label"></label>
                                        </div>
                                    </div>

                                    <div class="attr-form-group etn-label-item">
                                        <div class="etn-label">
                                            <label><?php esc_html_e('Price Label Text', 'eventin'); ?> </label>
                                            <div class="etn-desc"> <?php esc_html_e('Place price label', 'eventin'); ?> </div>

                                        </div>
                                        <div class="etn-meta">
                                            <input type="text" name="etn_price_label" value="<?php echo esc_attr(isset($etn_price_label) ? $etn_price_label : ''); ?>" class="etn-setting-input attr-form-control etn-recaptcha-secret-key" placeholder="<?php esc_html_e('Label Text', 'eventin'); ?>">
                                        </div>
                                    </div>
                                    
                                </div>

                            </div>

                            <?php do_action( 'etn_after_purchase_report_settings' ); ?>
                            
                        </div>

                        <?php do_action( 'etn_after_general_settings_inner_tab_content' ); ?>
                        
                    </div>
                </div>
                <?php
                    if( is_array( $settings_arr ) && isset( $settings_arr['remainder_email'] ) && file_exists( $settings_arr['remainder_email'] )){
                        include_once $settings_arr['remainder_email'];
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- ./End General Tab -->