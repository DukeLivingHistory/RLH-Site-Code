<?php

/*
 * This file adds CSS to the admin area of the site designed to make the interface
 * more user-friendly.
 */

add_action('admin_head', function(){ ?>
  <style>
    /* disable manual editing of transcript nodes */
    [data-layout="transcript_node"] .ui-sortable-handle {
      pointer-events: none;
    }
    /*
    * These fields were initially disabled; now they're not
    *
    [data-layout="transcript_node"] input[type="text"] {
      border: none;
      box-shadow: none;
      pointer-events: none;
    }
    [data-name="transcript_contents"] .acf-fc-popup li:first-of-type {
      display: none;
    }
    */
    /* custom borders for transcript fields */
    [data-name="transcript_contents"] .acf-flexible-content .layout {
      margin: 0;
      border: none;
      border-left: 5px solid;
    }
    [data-name="transcript_contents"] .acf-flexible-content .layout[data-layout="transcript_node"] {
      border-color: tomato;
    }
    [data-name="transcript_contents"] .acf-flexible-content .layout[data-layout="section_break"] {
      border-color: rebeccapurple;
    }
    [data-name="transcript_contents"] .acf-flexible-content .layout[data-layout="speaker_break"] {
      border-color: lightseagreen;
    }
    [data-name="transcript_contents"] .acf-flexible-content .layout[data-layout="paragraph_break"] {
      border-color: powderblue;
    }
    /* hide timestamps for section breaks */
    .acf-th-transcript_node_timestamp,
    .acf-field-transcript-node-timestamp {
      display: none;
    }
    /* clean up message field */
    .acf-field-message {
      padding-bottom: 2px!important;
    }
    .acf-field-message .acf-label {
      margin-bottom: 0!important;
    }
    /* hide posts & comments menu items */
    #menu-posts,
    #menu-comments {
      display: none;
    }
    /* disable flex layout options for supp cont */
    [data-name="sc_row"] .acf-fc-layout-handle,
    [data-name="sc_row"] .acf-fc-layout-order,
    [data-name="sc_row"] .acf-fc-layout-controls,
    [data-name="sc_row"] .acf-fc-layout-controlls,
    [data-name="sc_row"] [data-event="add-layout"].disabled {
      display: none!important;
    }
    [data-name="sc_row"] .acf-flexible-content .layout {
      border: none;
    }
    /* highlight update from sections */
    .acf-field-update-from-fields,
    .acf-field-update-from-raw {
      background: greenyellow;
    }
    /* hide collections links from submenus */
    #adminmenu .wp-submenu a[href*="collection"] {
      display: none;
    }

    [data-name="is_transcript_processing"]{
      display: none!important;
    }

    [data-key="processing_message"]{
      background: yellow;
      font-weight: bold;
    }

    .term-description-wrap {
      display: none!important;
    }

    .media-modal .delete-attachment {
      display: none!important;
    }

    [data-setting="caption"] .name:before {
      content: 'Lightbox ';
    }

    .acf-flexible-content .layout .acf-fc-show-on-hover {
      display: block!important;
    }

  .acf-repeater .acf-row-handle .acf-icon {
    display: block!important;
    left: 8px;
  }

  .post-type-timeline [data-name="timestamp"] label {
    display: none;
  }

  .post-type-timeline [data-name="timestamp"] .acf-label:before {
    content: 'Date';
    display: block;
    font-weight: bold;
    font-size: 13px;
    line-height: 1.4em;
    margin: 0 0 3px;
  }

  [data-name="sc_row"] .acf-row > .acf-fields {
    border-top: 10px solid #DFDFDF;
  }

  [data-name="update_from_fields"] {
    padding-bottom: 60px!important;
    position: relative;
  }

  [data-name="update_from_fields"] [type="submit"] {
    position: absolute;
    bottom: 15px;
    left: 15px;
  }

  </style>

  <script>

  jQuery( document ).ready( function(){

    var timestampsById = {};

    var updateTimestampOptions = function( elem ){
      var id = jQuery(elem).val();
      var picker = jQuery(elem).closest( '[data-name="content"]' ).find( '[data-name="link_timestamp_picker"] select' );
      if( !id ) return;
      if( !timestampsById[id] ){
        jQuery.get( '/wp-json/v1/content/'+id+'/timestamps', function( data ){
          picker.empty();
          picker.append( '<option value="null">---</option>' );
          jQuery( data ).each( function(){
            picker.append( '<option value="#'+this.hash+'">'+this.text+'</option>' );
          } );
          timestampsById[id] = data;
        } );
      } else {
        picker.empty();
        jQuery( timestampsById[id] ).each( function(){
          picker.append( '<option value="#'+this.hash+'">'+this.text+'</option>' );
        } );
      }

    }

    jQuery( '[data-name="link"] input' ).each( function(){
        updateTimestampOptions(this);
    } );

    jQuery( 'body' ).on( 'change', '[data-name="link"] input', function(){
        var input = jQuery(this).closest( '[data-name="content"]' ).find( '[data-name="link_timestamp"] input' );
        input.val('');
        updateTimestampOptions(this);
    } );

    jQuery( 'body' ).on( 'change', '[data-name="link_timestamp_picker"] select', function(){
      var hash = jQuery(this).val();
      var input = jQuery(this).closest( '[data-name="content"]' ).find( '[data-name="link_timestamp"] input' );
      input.val( hash === 'null' ? '' : hash );
    } );

  } );

  </script>

<?php } );
