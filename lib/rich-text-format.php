<?php
/**
 * This file is responsible for providing the Javascript to allow
 * formatting plaintext as a transcript.
 * Because there's a "whitelisted abbreviations" option set in the site options,
 * this must be printed to the page via PHP so we have access to these values.
 *
 */
add_action('admin_head', function() {
  // Exit early if content doesn't have this field.
  if(
    !is_admin() ||
    (get_post_type() !== 'interactive' && get_post_type() !== 'interview')
  ) return;
  //
  $delimiters = get_field('whitelisted_abbreviations', 'option');
  $disallowed = explode(',', $delimiters);
  ?>
  <script>
    (function($){
      /**
       * This function is responsible for converting plaintext into an array that can
       * be reassembled into a transcript.
       *
       * In order for certain elements like paragraph breaks and notes to be maintained,
       * they're replaced with a temporary "hash" which is then replaced with the original
       * content before being returned.
       *
       * This function runs asynchronously. A critical piece of regex is not supported
       * in most current Javascript engines, so we POST it to an API endpoint provided
       * by the site's back-end.
       *
       * @param {string}   text
       * @param {array}    disallowedDelimiters  Array of strings which should _not_ be considered in parsing sentences
       * @param {function} cb                    Callback to execute upon completion
       */
      function getArrayFromSentences(text, disallowedDelimiters, cb) {
        // Replace any instance of NOTEs with a hash [[N:<original text>]]
        // so that they can be converted back into notes after sentences
        // are parsed.
        text = text.replace(/(?:\n\n)?NOTE\s(.*?)\n\n/g, function(){
          return '[[N:'+arguments[1]+']]'
        })

        // Replace any instances of new lines with hash [[P]]
        // so that they can be converted into paragraph breaks.
        text = text.replace(/\n{2,}/g, ' [[P]]')

        // Hash any instances of protected words with [[<original text>]]
        // so that they won't be caught by punctuation parser
        disallowedDelimiters.forEach(function(delimiter, i) {
          text = text.split(delimiter).join('[['+i+']]')
        })

        // If last character is not punctuation, make it so. Otherwise,
        // it won't be parsed as a sentence.
        if(!text.match(/[\.\?!]$/)) {
          text = text + '.'
        }

        // Create data to be passed to our negative lookbehind service
        var data = JSON.stringify({
          // Replace newlines with spaces, or else regex won't work
          text: text.replace('\n', ' ').replace('??', '@@@?@@@?')+' ',
          pattern: '("?.*?(?<!\\W[A-Z])[.!?]+(?!])"?)\\s?'
        })

        // The negative look-behind we need isn't implemented in most JS runtimes,
        // so we call a microservice.
        $.ajax({
          url: '/wp-json/v1/microservices/nlbaas',
          type: 'POST',
          dataType: 'json',
          headers: {
            'Content-Type': 'application/json'
          },
          data: data,
          success: function(exploded) {
            // We map over our results
            var cleaned = exploded.map(function(value) {
              var paragraph = false
              var note = false
              // Replace paragraph hashes and mark note as being a paragraph
              if(value.match(/\[\[P\]\]/)) {
                value = value.replace(/\[\[P\]\]/g, '')
                paragraph = true
              }
              // Replace note hashes and record text on node
              if(value.match(/\[\[N:(.*?)\]\]/)) {
                value = value.replace(/\[\[N:(.*?)\]\]/g, function() {
                  note = arguments[1]
                  return ''
                })
              }
              // Return note, replacing any protected strings
              return {
                item: value.replace(/\[\[(\d+)\]\]/g, function() {
                  var i = arguments[1]
                  return disallowedDelimiters[i]
                }).trim(),
                paragraph: paragraph,
                note: note
              }
            })
            // Execute our callback with our mapped content
            cb(cleaned)
          }
        })
      }

      /**
       * Given an integer, convert it into a timestamp.
       * E.g.
       * 3 -> 00:00:03.000
       * 65 -> 00:01:05.000
       * @param  {number} secs Number of seconds
       * @return {string}      Timestamp
       */
      function makeTimestamps(secs) {
        return new Date(secs * 1000).toISOString().substr(11, 8) + '.000'
      }

      /**
       * Given an array of objects representing sentences, reduce into
       * a transcript string. Objects will match signature:
       *
       * {
       *   paragraph<bool> - If sentence opens a new paragraphs
       *   note<string> - Context of note
       *   item<string> - Text content of sentence
       * }
       *
       * @param  {array<object>}  arr Array of notes
       * @return {string}             Resulting transcript
       */
      function formatArrayAsTimeStamps(arr) {
        var i = 1
        return arr.reduce(function(string, sentence) {
          return `${string}${sentence.paragraph ? 'NOTE paragraph\n\n' : ''}${sentence.note ? 'NOTE '+sentence.note+'\n\n' : ''}${makeTimestamps(i++)} --> ${makeTimestamps(i)}\n${sentence.item}\n\n`
        }, 'WEBVTT\n\n')
      }

      /**
       * Check the existing transcript contents to see if it's already a transcript,
       * and remove transcript elements
       * @param  {string} text Transcript or plaintext
       * @return {string}      Plaintext
       */
      function sanitizeTranscript(text) {
        // If the transcript already contains timestamps, warn user
        // that they're going to be replaced
        if(text.match(/\s?(?:\d\d)?:\d\d:\d\d\.\d\d\d\s?/)) {
          var r = confirm('Do you really want to replace existing time codes?')
          if(!r) return
        }

        // Clear out any transcript-specific markup
        var cleaned = text.replace(/WEBVTT\n\n/g, '')
          // Remove paragraphs
          .replace(/NOTE paragraph/g, '')
          // Remove notes, but leave the origin text
          .replace(/(NOTE .*\n)/g, function() {
            return arguments[1]+'\n'
          })
          // Remove timestamps
          .replace(/\s?(?:\d\d)?:\d\d:\d\d\.\d\d\d\s?/g, '')
          .replace(/-->/g, '')

        return cleaned
      }

      // When our document is ready, init script
      $(document).ready(function(){
        // Grab our whitelisted abbreviations as JSON
        var disallowedDelimiters = JSON.parse('<?= json_encode($disallowed); ?>');

        // When we click the button, execute function
        $('#js-format-interactive').click(function(){
          // Get transcript contents
          var $transcript = $('#acf-transcript_raw')
          var val = $transcript.val().trim()

          // Clean out existing transcript contents
          var cleaned = sanitizeTranscript(val)

          // Run script, replacing value of input as callback
          getArrayFromSentences(cleaned, disallowedDelimiters, function(split) {
            var formatted = formatArrayAsTimeStamps(split)
            $transcript.val(formatted)
          })
        })
      })
    })(jQuery)
  </script>

  <?php
});
