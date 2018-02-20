<?php

add_action('admin_head', function() {
  if(get_post_type() !== 'rich-text') return;
  $delimiters = get_field('whitelisted_abbreviations', 'option');
  $disallowed = explode(',', $delimiters);
  ?>
  <script>
    (function($){
      function getArrayFromSentences(text, disallowedDelimiters) {
        // TODO: Move the following line of code
        var hashed = text.replace(/\n{2,}/g, '\n\nNOTE paragraph\n\n')
        disallowedDelimiters.forEach(function(delimiter, i) {
          hashed = hashed.split(delimiter).join('[['+i+']]')
        })
        var exploded = hashed.match(/[^\.!\?]+[\.!\?]+/g)
        var cleaned = exploded.map(function(value) {
          return value.replace(/\[\[(\d+)\]\]/, function() {
            var i = arguments[1]
            return disallowedDelimiters[i]
          }).trim()
        })

        return cleaned
      }

      function makeTimestamps(secs) {
        return new Date(secs * 1000).toISOString().substr(11, 8) + '.000'
      }

      function formatArrayAsTimeStamps(arr) {
        var i = 0
        return arr.reduce(function(string, item) {
          return string + makeTimestamps(i++)+' --> '+makeTimestamps(i++)+'\n'+item+'\n\n'
        }, 'WEBVTT\n\n')
      }

      $(document).ready(function(){
        var disallowedDelimiters = JSON.parse('<?= json_encode($disallowed); ?>');

        $('#js-format-rich-text').click(function(){
          var $transcript = $('#acf-transcript_raw')
          var val = $transcript.val()
          var split = getArrayFromSentences(val, disallowedDelimiters)
          var formatted = formatArrayAsTimeStamps(split)
          $transcript.val(formatted)
          $(this).attr('disabled', 'disabled')
        })
      })
    })(jQuery)
  </script>
  <?php
});
