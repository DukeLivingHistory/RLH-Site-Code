<?php
function attachment_field_credit( $form_fields, $post ) {
    $form_fields['photographer_name'] = [
        'label' => 'Photographer Name',
        'input' => 'text',
        'value' => get_post_meta( $post->ID, 'photographer_name', true ),
        'helps' => 'If provided, photo credit will be displayed',
    ];
    $form_fields['photographer_url'] = [
        'label' => 'Photographer Name Link',
        'input' => 'text',
        'value' => get_post_meta( $post->ID, 'photographer_url', true ),
        'helps' => 'Add Photographer Name Link'
    ];
    return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'attachment_field_credit', 10, 2 );

function attachment_field_credit_save( $post, $attachment ) {
    if( isset( $attachment['photographer_name'] ) ){
        update_post_meta( $post['ID'], 'photographer_name', $attachment['photographer_name'] );
    }
    if( isset( $attachment['photographer_url'] ) ){
      update_post_meta( $post['ID'], 'photographer_url', esc_url( $attachment['photographer_url'] ) );
    }
    return $post;
}
add_filter( 'attachment_fields_to_save', 'attachment_field_credit_save', 10, 2 );
