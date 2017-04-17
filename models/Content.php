<?php

class Content {

  function __construct( $id ){
    $this->id = (int)$id;
    $this->name = get_the_title( $id );
    $this->link = get_the_permalink( $id );
    $this->image = get_post_thumbnail_id( $id );
    $this->description = get_post($id)->post_excerpt;
    $this->collections = $this->get_collections();
    $this->related = $this->get_related();
  }

  private function get_collections(){
    $collections = get_the_terms( $this->id, 'collection' );
    if( $collections ){
      foreach( $collections as $collection ){
        $collection_ids[] = $collection->term_id;
      }
      return $collection_ids;
    }
    return false;
  }

  public function get_supp_cont(){
    $supp_content = get_field( 'sc_row', $this->id );
    if( !$supp_content ) return [];
    $i = 0;
    foreach( $supp_content as $item ){
      $item_formatted = $this->format_cont( $item['content'][0] );
      $supp_content_formatted[$i]['timestamp'] = $item['timestamp'];
      $supp_content_formatted[$i]['type'] = $item_formatted['type'];
      $supp_content_formatted[$i]['open'] = $item['open'];
      $supp_content_formatted[$i++]['data'] = isset( $item_formatted['data'] ) ? $item_formatted['data'] : false ;
    }
    return $supp_content_formatted;
  }

  private function get_related(){
    $related_content = get_posts( [
      'connected_type' => [ 'content_bi', 'content_uni' ],
      'connected_items' => $this->id,
      'suppress_filters' => false,
      'post_type' => 'any'
    ] );

    foreach( $related_content as $content ){
      $related[] = [
        'name' => $content->post_title,
        'id' => $content->ID,
        'type' => get_post_type( $content->ID ),
        'link' => get_permalink( $content->ID )
      ];
    }
    return isset( $related ) ? $related : false;
  }

  private function format_cont( $content ){
    $type = $content['acf_fc_layout'];
    $returns['type'] = $type;
    switch( $type ){
      case 'blockquote':
        $returns['data'] = [
          'attribution' => $content['attribution'],
          'quote' => $content['quote']
        ];
        break;
      case 'externallink':
        $returns['data'] = [
          'description' => $content['description'],
          'link_text' => $content['text'],
          'link_url' => $content['url'],
          'title' => $content['title'],
        ];
        break;
      case 'file':
        $file = $content['file'];
        $returns['data'] = [
          'description' => $content['description'],
          'file' => $file['url'],
          'title' => $file['title']
        ];
        break;
      case 'gallery':
        $returns['data'] = [
          'description' => $content['description'],
          'imgs' => $this->get_gallery_imd_ids( $content['gallery'] ),
          'title' => $content['title']
        ];
        break;
      case 'internallink':
        $link = $content['link'];
        $returns['data'] = [
          'description' => $link->post_excerpt,
          'feat_img' => get_post_thumbnail_id( $link->ID ),
          'id' => $link->ID,
          'title' => $link->post_title,
          'type' => get_post_type( $link->ID ),
          'link' => $content['link_timestamp'] ? get_permalink( $link->ID ).$content['link_timestamp'] : get_permalink( $link->ID )
        ];
        break;
      case 'map_location':
        $returns['data'] = [
          'title' => $content['name'],
          'coords' => [
            'lat' => $content['location']['lat'],
            'lng' => $content['location']['lng']
          ]
        ];
        break;
      case 'image':
        $img = $content['sc_image_img'];
        $returns['data'] = [
          'alt' => $img['alt'],
          'caption' => $img['caption'],
          'img_id' => $img['ID'],
          'title' => $img['title']
        ];
        break;
      case 'text':
        $returns['data'] = [
          'content' => $content['content']
        ];
        break;
    }
    return $returns;
  }

  private function get_gallery_imd_ids( $id ){
    $imgs = get_field( 'gallery_contents', $id );
    if(!$imgs) return [];
    foreach( $imgs as $img ){
      $returns[] = [
        'alt' => $img['alt'],
        'caption' => $img['caption'],
        'img_id' => $img['ID'],
        'title' => $img['title']
      ];
    }
    return $returns;
  }

}
