<?php
// Enable repeater field searches
function repeater_search($where) {
  $applicable_fields = [
    "events"
  ];

  foreach($applicable_fields as $field) {
    $keys[] = 'meta_key = \''.$field.'_$';
    $replace[] = 'meta_key LIKE \''.$field.'_%';
  }

	$where = str_replace($keys, $replace, $where);
  $where = preg_replace("/\{.*?\}/", '%', $where);

	return $where;
}
add_filter('posts_where', 'repeater_search', 1);
