<?php
/**
 * Given a WebVTT file, return an array of strings that contain a term.
 * @param  string $all              Multiline text
 * @param  string $term             Search term
 * @param  string $timestamp_method Means
 * @return array                    Array of lines
 */
function get_matching_lines($all, $term, $timestamp_method, $flags) {
  $exploded = explode("\n", clean_vtt($all));
  $results = [];
  $timestamp = '';
  $i = 0;

  foreach($exploded as $index => $line) {
    // Allow custom timestamp methods based on field name
    switch($timestamp_method) {
      case "transcript_raw":
      case "description_raw":
        $start = [];
        if(preg_match("/((?:\d\d)?\d\d:\d\d.\d\d\d) --/", $line, $start)) {
          $timestamp = sanitize_timestamp($start[1]);
        }
        break;
      case "supporting_content_raw":
        if(preg_match("/((?:\d\d)?\d\d:\d\d.\d\d\d) --/", $line, $start)) {
          $timestamp = "sc-$i";
          $i = $i + 1;
        }
        break;
      case "content": // Timelines
        $timestamp = $index;
        break;
      default:
        break;
    }

    if(stripos($line, $term) !== false) {
      $results[] = [
        'text' => trim(highlight_term($line, strip_tags($term), $flags)),
        'timestamp' => $timestamp
      ];
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
function highlight_term($string, $term, $flags = []) {
  if(!$term) return $string;
  $term = preg_quote($term);
  $replace = in_array('whole_word', $flags) ?
    "/((?:^${term})|(?<=\s)(?:${term}))(?=\s|[[:punct:]])/" :
    "/(${term})/";
  if(!in_array('case_sensitive', $flags)) $replace .= 'i';
  return preg_replace($replace, "<span class='content-search-result'>\\1</span>", $string);
}

/**
 * Given a string, remove all WebVTT-specific markup
 * @param  string $text Possibly VTT content
 * @return string       Cleaned content
 */
function clean_vtt($text) {
  return preg_replace("/<v (.*?)>/", "$1 $2", $text);
}
