<?php
defined( 'ABSPATH' ) || exit;

use Etn\Utils\Helper;
?>

<div class="modal slide fade etn-event-es-social-modal" id="etn-event-es-social-modal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog attr-modal-dialog-centered" role="document">
      <div class="modal-content etn-org-event-social-content">
         <button type="button" class="close etn-social-close" aria-label="Close">
            <span aria-hidden="true">&times;</span>
         </button>
         <div class="modal-header">
            <h5 class="etn-social-title" id="Title"><?php echo esc_html( $item['label'] ); ?></h5>
         </div>
         <div class="modal-body etn-social-icon-search-input">
            <div class="input-group etn-modal-search-box">
               <span class="input-group-addon"></span>
               <input placeholder="<?php echo esc_html__("search icon", "eventin"); ?>" type="text" class="attr-form-control etn-search-event-mng-social" />
            </div>
            <?php $etn_etn_icons = Helper::etn_event_manager_fontawesome_icons();?>
            <div class="etn-social-icon-list">
               <?php
                  foreach ( $etn_etn_icons as $icon_key => $value ): ?>
                     <i data-value="<?php echo esc_attr( $value ); ?>" data-class="<?php echo esc_attr( $icon_key ); ?>" class="<?php echo esc_attr( $icon_key ); ?>"></i>
               <?php 
                  endforeach;
                  ?>
            </div>
         </div>
      </div>
   </div>
</div>