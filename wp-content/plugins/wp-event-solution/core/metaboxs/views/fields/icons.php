<?php
defined( 'ABSPATH' ) || exit;

use Etn\Utils\Helper;

?>

<div class="attr-modal attr-slide attr-fade" id="etn-event-es-social-modal" tabindex="-1" role="attr-dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
   <div class="attr-modal-dialog modal-dialog-centered" role="document">
      <div class="attr-modal-content">
         <div class="attr-modal-header">
            <h5 class="attr-modal-title" id="Title"><?php echo esc_html( $item['label'] ); ?></h5>
            <button type="button" class="attr-close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="attr-modal-body etn-social-icon-search-input">

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
         <div class="attr-modal-footer">
            <button type="button" class="etn-btn attr-btn-secondary" data-dismiss="modal"><?php echo esc_html__( 'Close', 'eventin' ); ?></button>
         </div>
      </div>
   </div>
</div>