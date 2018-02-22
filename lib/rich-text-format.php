<?php

add_action('admin_head', function() {
  if(
    !is_admin() ||
    (get_post_type() !== 'interactive' && get_post_type() !== 'interview')
  ) return;
  $delimiters = get_field('whitelisted_abbreviations', 'option');
  $disallowed = explode(',', $delimiters);
  ?>
  <script>
    (function($){
      function getArrayFromSentences(text, disallowedDelimiters) {

        text = text.replace(/\n{2,}/g, '[[P]]')

        disallowedDelimiters.forEach(function(delimiter, i) {
          text = text.split(delimiter).join('[['+i+']]')
        })
        var exploded = text.match(/.*?(?<![A-Z])[\.!\?]+\n*/g)

        var cleaned = exploded.map(function(value) {
          var paragraph = false
          if(value.match(/\[\[P\]\]/)) {
            value = value.replace(/\[\[P\]\]/g, '')
            paragraph = true
          }
          return {
            item: value.replace(/\[\[(\d+)\]\]/, function() {
              var i = arguments[1]
              return disallowedDelimiters[i]
            }).trim(),
            paragraph: paragraph
          }
        })

        return cleaned
      }

      function makeTimestamps(secs) {
        return new Date(secs * 1000).toISOString().substr(11, 8) + '.000'
      }

      function formatArrayAsTimeStamps(arr) {
        var i = 0
        return arr.reduce(function(string, sentence) {
          return `${string}${sentence.paragraph ? 'NOTE paragraph\n\n' : ''}${makeTimestamps(i++)} --> ${makeTimestamps(i++)}\n${sentence.item}\n\n`
        }, 'WEBVTT\n\n')
      }

      $(document).ready(function(){
        var disallowedDelimiters = JSON.parse('<?= json_encode($disallowed); ?>');

        $('#js-format-interactive').click(function(){
          var $transcript = $('#acf-transcript_raw')
          var val = $transcript.val()
          var split = getArrayFromSentences(val, disallowedDelimiters)
          var formatted = formatArrayAsTimeStamps(split)
          $transcript.val(formatted)
        })
      })
    })(jQuery)
  </script>
  <?php
});
