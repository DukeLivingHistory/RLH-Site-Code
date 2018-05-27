<?php

function generate_global_query($config, $flags) {
  $comparator = in_array('whole_word', $flags) ? 'RLIKE ' : 'LIKE ';
  $comparator .= in_array('case_sensitive', $flags) ? 'BINARY ' : '';
  $comparator .= in_array('whole_word', $flags) ?
    "'([[:blank:][:punct:]]|^)<<%s>>([[:blank:][:punct:]]|$)'" :
    "'%%%s%%'";

  $query = "SELECT ID AS id , 'content' AS type FROM wp_posts ";
  foreach($config['primary_fields'] as $key => $value) {
    $query .= ($key < 1) ? "WHERE (" : "OR ";
    $query .= "$value ";
    $query .= "$comparator ";
  }
  $query .= ") AND post_type IN ( ";
  foreach($config['types'] as $key => $value) {
    if($key > 0) $query .= ", ";
    $query .= "'$value'";
  }
  $query .= " ) AND post_status = 'publish'\n";
  $query .= "UNION ";
  $query .= "SELECT post_id as id, 'content' AS type FROM wp_postmeta ";
  $query .= "WHERE meta_value ";
  $query .= "$comparator ";
  $query .= "AND ( ";
  $query .= "meta_key IN ( ";
  foreach($config['meta_fields'] as $key => $value) {
    if($key > 0) $query .= ", ";
    $query .= "'$value'";
  }
  $query .= " ) ";
  foreach($config['meta_like_fields'] as $key => $value) {
    $query .= "OR meta_key LIKE '$value' ";
  }
  $query .= ") ";
  $query .= "UNION ";
  $query .= "SELECT wp_terms.term_id, 'term' as type FROM wp_terms ";
  $query .= "JOIN wp_term_taxonomy ";
  $query .= "ON wp_terms.term_id = wp_term_taxonomy.term_id ";
  $query .= "AND wp_term_taxonomy.taxonomy IN ( ";
  foreach($config['terms'] as $key => $value) {
    if($key > 0) $query .= ", ";
    $query .= "'$value'";
  }
  $query .= " ) ";
  $query .= "AND wp_terms.name ";
  $query .= "$comparator ";
  $query .= "UNION ";
  $query .= "SELECT term_id, 'term' as type from wp_termmeta ";
  $query .= "WHERE meta_value ";
  $query .= "$comparator ";
  $query .= "AND meta_key IN ( ";
  foreach($config['term_fields'] as $key => $value) {
    if($key > 0) $query .= ", ";
    $query .= "'$value'";
  }
  $query .= " ) ";

  return $query;
}

function generate_blog_query($config, $flags) {

  $comparator = in_array('whole_word', $flags) ? 'RLIKE ' : 'LIKE ';
  $comparator .= in_array('case_sensitive', $flags) ? 'BINARY ' : '';
  $comparator .= in_array('whole_word', $flags) ?
    "'([[:blank:][:punct:]]|^)%d([[:blank:][:punct:]]|$)'" :
    "'%%%s%%'";

  $query .= "SELECT ID as id, 'content' AS type from wp_posts ";
  foreach($config['primary_fields'] as $key => $value) {
    $query .= ($key < 1) ? "WHERE " : "OR ";
    $query .= "$value ";
    $query .= "$comparator ";
  }
  $query .= "AND post_type IN ( ";
  foreach($config['types'] as $key => $value) {
    if($key > 0) $query .= ", ";
    $query .= "'$value'";
  }
  $query .= " ) AND post_status = 'publish'\n";
  $query .= "UNION ";
  $query .= "SELECT post_id, 'content' AS type FROM wp_postmeta ";
  $query .= "JOIN wp_posts ";
  $query .= "ON wp_posts.ID = wp_postmeta.post_id ";
  $query .= "AND meta_value ";
  $query .= "$comparator ";
  $query .= "AND ";
  $query .= "meta_key IN ( ";
  foreach($config['meta_fields'] as $key => $value) {
    if($key > 0) $query .= ", ";
    $query .= "'$value'";
  }
  $query .= " ) ";
  $query .= "AND wp_posts.post_type IN ( ";
  foreach($config['types'] as $key => $value) {
    if($key > 0) $query .= ", ";
    $query .= "'$value'";
  }
  $query .= " )";

  return $query;
}

function generate_collection_query($config, $flags) {
  $comparator = in_array('whole_word', $flags) ? 'RLIKE ' : 'LIKE ';
  $comparator .= in_array('case_sensitive', $flags) ? 'BINARY ' : '';
  $comparator .= in_array('whole_word', $flags) ?
    "'([[:blank:][:punct:]]|^)%d([[:blank:][:punct:]]|$)'" :
    "%%%s%%'";

  $query .= "SELECT id, 'content' as type FROM ( ";
  $query .= "SELECT ID as id from wp_posts ";
  foreach($config['primary_fields'] as $key => $value) {
    $query .= ($key < 1) ? "WHERE " : "OR ";
    $query .= "$value ";
    $query .= "$comparator ";
  }
  $query .= "AND post_type IN ( ";
  foreach($config['types'] as $key => $value) {
    if($key > 0) $query .= ", ";
    $query .= "'$value'";
  }
  $query .= " ) AND post_status = 'publish'\n";
  $query .= "UNION ";
  $query .= "SELECT post_id FROM wp_postmeta ";
  $query .= "WHERE meta_value ";
  $query .= "$comparator ";
  $query .= "AND ( ";
  $query .= "meta_key IN ( ";
  foreach($config['meta_fields'] as $key => $value) {
    if($key > 0) $query .= ", ";
    $query .= "'$value'";
  }
  $query .= " ) ";
  foreach($config['meta_like_fields'] as $key => $value) {
    $query .= "OR meta_key LIKE '$value' ";
  }
  $query .= ") ";
  $query .= ") AS content  ";
  $query .= "JOIN wp_term_relationships ";
  $query .= "ON wp_term_relationships.object_id = content.id ";
  $query .= "AND wp_term_relationships.term_taxonomy_id = %d";

  return $query;
}
