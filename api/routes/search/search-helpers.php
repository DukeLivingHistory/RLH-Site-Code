<?php
/**
 * Given a WebVTT file, return an array of strings that contain a term.
 * @param  string $all  Multiline text
 * @param  string $term Search term
 * @return array        Array of lines
 */
function get_matching_lines($all, $term) {
  $exploded = explode("\n", $all);
  $results = [];
  foreach($exploded as $line) {
    if(stripos($line, $term) !== false) {
      $results[] = trim($line);
    }
  }
  return $results;
}

/**
 * Given text, generate new lines for each sentence.
 * @param  [type] $string [description]
 * @return [type]         [description]
 */
function get_lines_from_sentences($string) {
  $string = strip_tags($string);
  preg_match_all("/(.*?)(?<![A-Z])[\.!\?]+/", $string, $exploded);
  if(!$exploded) return $string;
  return implode("\n", $exploded[0]);
}

/**
 * Given a string, wrap results in span tag.
 * @param  string $string Haystack
 * @param  string $term   Search term
 * @return string         HTML for string.
 */
function highlight_term($string, $term) {
  return preg_replace("/${term}/i", "<span class='search-result'>$1</span>", $string);
}

/**
 * Given a string, remove all WebVTT-specific markup
 * @param  string $text Possibly VTT content
 * @return string       Cleaned content
 */
function clean_vtt($text) {
  return str_replace(['<v', '>'], '', $text);
}
