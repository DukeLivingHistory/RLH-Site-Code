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
        // Hash NOTEs
        text = text.replace(/\n\nNOTE\s(.*?)\n\n/g, function(){
          return '[[N:'+arguments[1]+']]'
        })

        // Hash new lines
        text = text.replace(/\n{2,}/g, '[[P]]')

        // Hash protected words
        disallowedDelimiters.forEach(function(delimiter, i) {
          text = text.split(delimiter).join('[['+i+']]')
        })

        // Explode into sentences
        var exploded = text.match(/.*?(?:[^A-Z][\.!\?]?)+\n*/g)

        // Replace hashed values
        var cleaned = exploded.map(function(value) {
          var paragraph = false
          var note = false
          if(value.match(/\[\[P\]\]/)) {
            value = value.replace(/\[\[P\]\]/g, '')
            paragraph = true
          }
          if(value.match(/\[\[N:(.*?)\]\]/)) {
            value = value.replace(/\[\[N:(.*?)\]\]/g, function() {
              note = arguments[1]
              return ''
            })
          }
          return {
            item: value.replace(/\[\[(\d+)\]\]/g, function() {
              var i = arguments[1]
              return disallowedDelimiters[i]
            }).trim(),
            paragraph: paragraph,
            note: note
          }
        })

        return cleaned
      }

      function makeTimestamps(secs) {
        return new Date(secs * 1000).toISOString().substr(11, 8) + '.000'
      }

      function formatArrayAsTimeStamps(arr) {
        var i = 1
        return arr.reduce(function(string, sentence) {
          return `${string}${sentence.paragraph ? 'NOTE paragraph\n\n' : ''}${sentence.note ? 'NOTE '+sentence.note+'\n\n' : ''}${makeTimestamps(i++)} --> ${makeTimestamps(i)}\n${sentence.item}\n\n`
        }, 'WEBVTT\n\n')
      }

      function sanitizeTranscript(text) {
        if(text.match(/\s?(?:\d\d)?:\d\d:\d\d\.\d\d\d\s?/)) {
          var r = confirm('Do you really want to replace existing time codes?')
          if(!r) return
        }

        var cleaned = text.replace(/WEBVTT\n\n/g, '')
          .replace(/NOTE paragraph/g, '')
          .replace(/(NOTE .*\n)/g, function() {
            return arguments[1]+'\n'
          })
          .replace(/\s?(?:\d\d)?:\d\d:\d\d\.\d\d\d\s?/g, '')
          .replace(/-->/g, '')

        return cleaned
      }

      $(document).ready(function(){
        var disallowedDelimiters = JSON.parse('<?= json_encode($disallowed); ?>');

        $('#js-format-interactive').click(function(){
          var $transcript = $('#acf-transcript_raw')
          var val = $transcript.val()
          var cleaned = sanitizeTranscript(val)
          var split = getArrayFromSentences(cleaned, disallowedDelimiters)
          var formatted = formatArrayAsTimeStamps(split)
          $transcript.val(formatted)
        })
      })
    })(jQuery)
  </script>
  <?php
});
