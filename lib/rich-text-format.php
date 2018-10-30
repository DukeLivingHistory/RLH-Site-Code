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
       * @param {string}   text
       * @param {array}    disallowedDelimiters  Array of strings which should _not_ be considered in parsing sentences
       * @param {function} cb                    Callback to execute upon completion
       */
      function getArrayFromSentences(text, disallowedDelimiters, cb) {
        var PUNCTUATION = ['.', '?', '!', '–', '—', '…']
        var QUOTES = ['"', '“', '”']
        var chars = text.split('')
        var sentences = []
        var currentSentence = ''
        var openQuotes = false
        var paragraph = false
        var currentNote = null
        var openNote = false

        function arrayInclude(arr, thing) {
          return arr.indexOf(thing) !== -1
        }

        // Move from current sentence to next
        function stop() {
          var sentence = {
            text: currentSentence,
            paragraph: !currentNote && paragraph,
            note: currentNote,
          }
          sentences.push(sentence)
          currentSentence = ''
          paragraph = false
          currentNote = null
        }

        // Attach to current note
        function appendNote(character) {
          if (character === '\n') {
            currentNote = currentNote.replace(/^NOTE/, '').trim()
            openNote = false
          } else {
            currentNote = (currentNote || '') + character
          }
        }

        // Attach to previous sentence
        function append(character) {
          sentences[sentences.length - 1].text = sentences[sentences.length - 1].text + character
        }

        // Add to current sentence buffer
        function print(character) {
          currentSentence = currentSentence + character
        }

        // Determin if string is allowed
        function endsInAllowed(str) {
          var value = false
          disallowedDelimiters.forEach(function(term) {
              if (value) return
              var pattern = new RegExp(`${term}$`)
              value = pattern.test(str)
          })
          return value
        }

        chars.forEach(function (character, index) {
          var nextBy1 = chars[index + 1]
          var prevBy1 = chars[index - 1]
          var prevBy2 = chars[index - 2]

          if (character === 'N') {
            var maybeNote = text.substr(index, 4)
            if (maybeNote === 'NOTE') {
              openNote = true
            }
          }

          if (openNote) {
            appendNote(character)
          } else if (character === '\n') {
            paragraph = true
          } else if (
            character === '<' && nextBy1 === 'v' &&
            (
              arrayInclude(PUNCTUATION, currentSentence[currentSentence.length - 1]) ||
              (
                arrayInclude(QUOTES, currentSentence[currentSentence.length - 1]) &&
                arrayInclude(QUOTES, currentSentence[currentSentence.length - 2])
              )
            )
          ) {
            stop()
            print(character)
          } else if (arrayInclude(PUNCTUATION, character)) {
            if (
              endsInAllowed(currentSentence) ||
              arrayInclude(PUNCTUATION, nextBy1) ||
              (arrayInclude(PUNCTUATION, prevBy2) && !arrayInclude(PUNCTUATION, prevBy1)) ||
              (nextBy1 === ' ' && (/[A-Z]/).test(prevBy1) && !(/[A-Z]/).test(prevBy2))
            ) {
              print(character)
            } else if (nextBy1 === ' ' || nextBy1 === '\n' || arrayInclude(QUOTES, nextBy1)) {
              print(character)
              stop()
            } else {
              print(character)
            }
          } else {
            if (arrayInclude(QUOTES, character)) {
              if (openQuotes && arrayInclude(PUNCTUATION, prevBy1)) {
                append(character)
              } else {
                print(character)
              }
              openQuotes = !openQuotes
            } else {
              print(character)
            }
          }
        })
        stop()
        cb(sentences.map(function(sentence) {
          return Object.assign(sentence, {
            text: sentence.text.trim()
          })
        }).filter(function(sentence) {
          return !!sentence.text
        }))
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
          return `${string}${sentence.paragraph ? 'NOTE paragraph\n\n' : ''}${sentence.note ? 'NOTE '+sentence.note+'\n\n' : ''}${makeTimestamps(i++)} --> ${makeTimestamps(i)}\n${sentence.text}\n\n`
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
