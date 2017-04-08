<?php

/*
 * This file adds CSS to the admin area of the site designed to make the interface
 * more user-friendly.
 */

add_action('admin_head', function(){ ?>
  <?php // general styling niceties ?>
  <style>
    /* disable manual editing of transcript nodes */
    [data-layout="transcript_node"] .ui-sortable-handle {
      pointer-events: none;
    }
    [data-name="transcript_contents"] .acf-flexible-content .layout {
      margin: 0;
      border: none;
      border-left: 5px solid;
    }
    [data-name="transcript_contents"] .acf-flexible-content .layout[data-layout="transcript_node"] {
      border-color: tomato;
    }
    [data-name="transcript_contents"] .acf-flexible-content .layout[data-layout="section_break"] {
      border-color: rebeccapurple;
    }
    [data-name="transcript_contents"] .acf-flexible-content .layout[data-layout="speaker_break"] {
      border-color: lightseagreen;
    }
    [data-name="transcript_contents"] .acf-flexible-content .layout[data-layout="paragraph_break"] {
      border-color: powderblue;
    }
    /* hide timestamps for section breaks */
    .acf-th-transcript_node_timestamp,
    .acf-field-transcript-node-timestamp {
      display: none;
    }
    /* clean up message field */
    .acf-field-message {
      padding-bottom: 2px!important;
    }
    .acf-field-message .acf-label {
      margin-bottom: 0!important;
    }
    /* hide posts & comments menu items */
    #menu-posts,
    #menu-comments {
      display: none;
    }
    /* disable flex layout options for supp cont */
    [data-name="sc_row"] .acf-fc-layout-handle,
    [data-name="sc_row"] .acf-fc-layout-order,
    [data-name="sc_row"] .acf-fc-layout-controls,
    [data-name="sc_row"] .acf-fc-layout-controlls,
    [data-name="sc_row"] [data-event="add-layout"].disabled {
      display: none!important;
    }
    [data-name="sc_row"] .acf-flexible-content .layout {
      border: none;
    }
    /* highlight update from sections */
    .acf-field-update-from-fields,
    .acf-field-update-from-raw {
      background: greenyellow;
    }
    /* hide collections links from submenus */
    #adminmenu .wp-submenu a[href*="collection"] {
      display: none;
    }

    .term-description-wrap {
      display: none!important;
    }

    .media-modal .delete-attachment {
      display: none!important;
    }

    [data-setting="caption"] .name:before {
      content: 'Lightbox ';
    }

    .acf-flexible-content .layout .acf-fc-show-on-hover {
      display: block!important;
    }
    .acf-repeater .acf-row-handle .acf-icon {
      display: block!important;
      left: 8px;
    }
    .post-type-timeline [data-name="timestamp"] label {
      display: none;
    }
    .post-type-timeline [data-name="timestamp"] .acf-label:before {
      content: 'Date';
      display: block;
      font-weight: bold;
      font-size: 13px;
      line-height: 1.4em;
      margin: 0 0 3px;
    }
    [data-name="sc_row"] .acf-row > .acf-fields {
      border-top: 10px solid #DFDFDF;
    }
    [data-name="update_from_fields"] {
      padding-bottom: 60px!important;
      position: relative;
    }
    [data-name="update_from_fields"] [type="submit"] {
      position: absolute;
      bottom: 15px;
      left: 15px;
    }

    /* transcript validation */
    [data-name="transcript_raw"] .acf-input {
      position: relative;
    }

    .acf-line-numbers + textarea {
      float: right;
      width: calc(100% - 3em);
      margin: 0 0 2em;
    }

    .acf-line-numbers {
      float: left;
      //margin-top: .25em;
    }

    .acf-line-numbers .line-number {
      width: 2.6em;
      text-align: right;
      font-size: 14px;
      line-height: 1.4;
      display: block;
      font-family: monospace;
      opacity: .5;
    }

    .vtt-error-list {
      position: fixed;
      bottom: 15px;
      right: 15px;
      background: #CC3834;
      padding: 15px;
      color: white;
      max-height: 400px;
      overflow: scroll;
    }
    .vtt-error-list strong {
      display: inline-block;
      width: 3em;
      text-align: right;
      padding-right: .5em;
      vertical-align: middle;
    }
    .vtt-error-list em {
      display: inline-block;
      width: 15em;
      vertical-align: middle;
    }

    .acf-line-numbers > div {
      transition: background 5s ease;
    }

    .acf-line-numbers > div.was-jumped-to {
      transition: background 0s;
      background: yellow;
    }

    #jump-to-acf-error {
      position: fixed;
      bottom: 15px;
      right: 15px;
      background: #CC3834;
      padding: 15px;
      color: white;
      cursor: pointer;
    }
  </style>

  <?php // remote timestamp picking logic ?>
  <script>
  jQuery( document ).ready( function(){

    var timestampsById = {};

    var updateTimestampOptions = function( elem ){
      var id = jQuery(elem).val();
      var picker = jQuery(elem).closest( '[data-name="content"]' ).find( '[data-name="link_timestamp_picker"] select' );
      if( !id ) return;
      if( !timestampsById[id] ){
        jQuery.get( '/wp-json/v1/content/'+id+'/timestamps', function( data ){
          picker.empty();
          picker.append( '<option value="null">---</option>' );
          jQuery( data ).each( function(){
            picker.append( '<option value="#'+this.hash+'">'+this.text+'</option>' );
          } );
          timestampsById[id] = data;
        } );
      } else {
        picker.empty();
        jQuery( timestampsById[id] ).each( function(){
          picker.append( '<option value="#'+this.hash+'">'+this.text+'</option>' );
        } );
      }

    }

    jQuery( '[data-name="link"] input' ).each( function(){
        updateTimestampOptions(this);
    } );

    jQuery( 'body' ).on( 'change', '[data-name="link"] input', function(){
        var input = jQuery(this).closest( '[data-name="content"]' ).find( '[data-name="link_timestamp"] input' );
        input.val('');
        updateTimestampOptions(this);
    } );

    jQuery( 'body' ).on( 'change', '[data-name="link_timestamp_picker"] select', function(){
      var hash = jQuery(this).val();
      var input = jQuery(this).closest( '[data-name="content"]' ).find( '[data-name="link_timestamp"] input' );
      input.val( hash === 'null' ? '' : hash );
    } );

  } );
  </script>

  <?php // transcript parsing ?>
  <script>
  (function($) {
    // Any copyright is dedicated to the Public Domain.
    // http://creativecommons.org/publicdomain/zero/1.0/

    // Not intended to be fast, but if you can make it faster, please help out!

    var WebVTTParser = function() {
      this.parse = function(input, mode) {
        //XXX need global search and replace for \0
        var NEWLINE = /\r\n|\r|\n/,
            startTime = Date.now(),
            linePos = 0,
            lines = input.split(NEWLINE),
            alreadyCollected = false,
            cues = [],
            errors = []
        function err(message, col) {
          errors.push({message:message, line:linePos+1, col:col})
        }

        var line = lines[linePos],
            lineLength = line.length,
            signature = "WEBVTT",
            bom = 0,
            signature_length = signature.length

        /* Byte order mark */
        if (line[0] === "\ufeff") {
          bom = 1
          signature_length += 1
        }
        /* SIGNATURE */
        if (
          lineLength < signature_length ||
          line.indexOf(signature) !== 0+bom ||
          lineLength > signature_length &&
          line[signature_length] !== " " &&
          line[signature_length] !== "\t"
        ) {
          err("No valid signature. (File needs to start with \"WEBVTT\".)")
        }

        linePos++

        /* HEADER */
        while(lines[linePos] != "" && lines[linePos] != undefined) {
          err("No blank line after the signature.")
          if(lines[linePos].indexOf("-->") != -1) {
            alreadyCollected = true
            break
          }
          linePos++
        }

        /* CUE LOOP */
        while(lines[linePos] != undefined) {
          var cue
          while(!alreadyCollected && lines[linePos] == "") {
            linePos++
          }
          if(!alreadyCollected && lines[linePos] == undefined)
            break

          /* CUE CREATION */
          cue = {
            id:"",
            startTime:0,
            endTime:0,
            pauseOnExit:false,
            direction:"horizontal",
            snapToLines:true,
            linePosition:"auto",
            textPosition:50,
            size:100,
            alignment:"middle",
            text:"",
            tree:null
          }

          var parseTimings = true

          if(lines[linePos].indexOf("-->") == -1) {
            cue.id = lines[linePos]

            /* COMMENTS
               Not part of the specification's parser as these would just be ignored. However,
               we want them to be conforming and not get "Cue identifier cannot be standalone".
             */
            if(/^NOTE($|[ \t])/.test(cue.id)) { // .startsWith fails in Chrome
              linePos++
              while(lines[linePos] != "" && lines[linePos] != undefined) {
                if(lines[linePos].indexOf("-->") != -1)
                  err("Cannot have timestamp in a comment.")
                linePos++
              }
              continue
            }

            linePos++

            if(lines[linePos] == "" || lines[linePos] == undefined) {
              err("Cue identifier cannot be standalone.")
              continue
            }

            if(lines[linePos].indexOf("-->") == -1) {
              parseTimings = false
              err("Cue identifier needs to be followed by timestamp.")
            }
          }

          /* TIMINGS */
          alreadyCollected = false
          var timings = new WebVTTCueTimingsAndSettingsParser(lines[linePos], err)
          var previousCueStart = 0
          if(cues.length > 0) {
            previousCueStart = cues[cues.length-1].startTime
          }
          if(parseTimings && !timings.parse(cue, previousCueStart)) {
            /* BAD CUE */

            cue = null
            linePos++

            /* BAD CUE LOOP */
            while(lines[linePos] != "" && lines[linePos] != undefined) {
              if(lines[linePos].indexOf("-->") != -1) {
                alreadyCollected = true
                break
              }
              linePos++
            }
            continue
          }
          linePos++

          /* CUE TEXT LOOP */
          while(lines[linePos] != "" && lines[linePos] != undefined) {
            if(lines[linePos].indexOf("-->") != -1) {
              err("Blank line missing before cue.")
              alreadyCollected = true
              break
            }
            if(cue.text != "")
              cue.text += "\n"
            cue.text += lines[linePos]
            linePos++
          }

          /* CUE TEXT PROCESSING */
          var cuetextparser = new WebVTTCueTextParser(cue.text, err, mode)
          cue.tree = cuetextparser.parse(cue.startTime, cue.endTime)
          cues.push(cue)
        }
        cues.sort(function(a, b) {
          if (a.startTime < b.startTime)
            return -1
          if (a.startTime > b.startTime)
            return 1
          if (a.endTime > b.endTime)
            return -1
          if (a.endTime < b.endTime)
            return 1
          return 0
        })
        /* END */
        return {cues:cues, errors:errors, time:Date.now()-startTime}
      }
    }
    var WebVTTCueTimingsAndSettingsParser = function(line, errorHandler) {
      var SPACE = /[\u0020\t\f]/,
          NOSPACE = /[^\u0020\t\f]/,
          line = line,
          pos = 0,
          err = function(message) {
            errorHandler(message, pos+1)
          },
          spaceBeforeSetting = true
      function skip(pattern) {
        while(
          line[pos] != undefined &&
          pattern.test(line[pos])
        ) {
          pos++
        }
      }
      function collect(pattern) {
        var str = ""
        while(
          line[pos] != undefined &&
          pattern.test(line[pos])
        ) {
          str += line[pos]
          pos++
        }
        return str
      }
      /* http://dev.w3.org/html5/webvtt/#collect-a-webvtt-timestamp */
      function timestamp() {
        var units = "minutes",
            val1,
            val2,
            val3,
            val4
        // 3
        if(line[pos] == undefined) {
          err("No timestamp found.")
          return
        }
        // 4
        if(!/\d/.test(line[pos])) {
          err("Timestamp must start with a character in the range 0-9.")
          return
        }
        // 5-7
        val1 = collect(/\d/)
        if(val1.length > 2 || parseInt(val1, 10) > 59) {
          units = "hours"
        }
        // 8
        if(line[pos] != ":") {
          err("No time unit separator found.")
          return
        }
        pos++
        // 9-11
        val2 = collect(/\d/)
        if(val2.length != 2) {
          err("Must be exactly two digits.")
          return
        }
        // 12
        if(units == "hours" || line[pos] == ":") {
          if(line[pos] != ":") {
            err("No seconds found or minutes is greater than 59.")
            return
          }
          pos++
          val3 = collect(/\d/)
          if(val3.length != 2) {
            err("Must be exactly two digits.")
            return
          }
        } else {
          val3 = val2
          val2 = val1
          val1 = "0"
        }
        // 13
        if(line[pos] != ".") {
          err("No decimal separator (\".\") found.")
          return
        }
        pos++
        // 14-16
        val4 = collect(/\d/)
        if(val4.length != 3) {
          err("Milliseconds must be given in three digits.")
          return
        }
        // 17
        if(parseInt(val2, 10) > 59) {
          err("You cannot have more than 59 minutes.")
          return
        }
        if(parseInt(val3, 10) > 59) {
          err("You cannot have more than 59 seconds.")
          return
        }
        return parseInt(val1, 10) * 60 * 60 + parseInt(val2, 10) * 60 + parseInt(val3, 10) + parseInt(val4, 10) / 1000
      }

      /* http://dev.w3.org/html5/webvtt/#parse-the-webvtt-settings */
      function parseSettings(input, cue) {
        var settings = input.split(SPACE),
            seen = []
        for(var i=0; i < settings.length; i++) {
          if(settings[i] == "")
            continue

          var index = settings[i].indexOf(':'),
              setting = settings[i].slice(0, index)
              value = settings[i].slice(index + 1)

          if(seen.indexOf(setting) != -1) {
            err("Duplicate setting.")
          }
          seen.push(setting)

          if(value == "") {
            err("No value for setting defined.")
            return
          }

          if(setting == "vertical") { // writing direction
            if(value != "rl" && value != "lr") {
              err("Writing direction can only be set to 'rl' or 'rl'.")
              continue
            }
            cue.direction = value
          } else if(setting == "line") { // line position
            if(!/\d/.test(value)) {
              err("Line position takes a number or percentage.")
              continue
            }
            if(value.indexOf("-", 1) != -1) {
              err("Line position can only have '-' at the start.")
              continue
            }
            if(value.indexOf("%") != -1 && value.indexOf("%") != value.length-1) {
              err("Line position can only have '%' at the end.")
              continue
            }
            if(value[0] == "-" && value[value.length-1] == "%") {
              err("Line position cannot be a negative percentage.")
              continue
            }
            if(value[value.length-1] == "%") {
              if(parseInt(value, 10) > 100) {
                err("Line position cannot be >100%.")
                continue
              }
              cue.snapToLines = false
            }
            cue.linePosition = parseInt(value, 10)
          } else if(setting == "position") { // text position
            if(value[value.length-1] != "%") {
              err("Text position must be a percentage.")
              continue
            }
            if(parseInt(value, 10) > 100) {
              err("Size cannot be >100%.")
              continue
            }
            cue.textPosition = parseInt(value, 10)
          } else if(setting == "size") { // size
            if(value[value.length-1] != "%") {
              err("Size must be a percentage.")
              continue
            }
            if(parseInt(value, 10) > 100) {
              err("Size cannot be >100%.")
              continue
            }
            cue.size = parseInt(value, 10)
          } else if(setting == "align") { // alignment
            var alignValues = ["start", "middle", "end", "left", "right"]
            if(alignValues.indexOf(value) == -1) {
              err("Alignment can only be set to one of " + alignValues.join(", ") + ".")
              continue
            }
            cue.alignment = value
          } else {
            err("Invalid setting.")
          }
        }
      }

      this.parse = function(cue, previousCueStart) {
        skip(SPACE)
        cue.startTime = timestamp()
        if(cue.startTime == undefined) {
          return
        }
        if(cue.startTime < previousCueStart) {
          err("Start timestamp is not greater than or equal to start timestamp of previous cue.")
        }
        if(NOSPACE.test(line[pos])) {
          err("Timestamp not separated from '-->' by whitespace.")
        }
        skip(SPACE)
        // 6-8
        if(line[pos] != "-") {
          err("No valid timestamp separator found.")
          return
        }
        pos++
        if(line[pos] != "-") {
          err("No valid timestamp separator found.")
          return
        }
        pos++
        if(line[pos] != ">") {
          err("No valid timestamp separator found.")
          return
        }
        pos++
        if(NOSPACE.test(line[pos])) {
          err("'-->' not separated from timestamp by whitespace.")
        }
        skip(SPACE)
        cue.endTime = timestamp()
        if(cue.endTime == undefined) {
          return
        }
        if(cue.endTime <= cue.startTime) {
          err("End timestamp is not greater than start timestamp.")
        }

        if(NOSPACE.test(line[pos])) {
          spaceBeforeSetting = false
        }
        skip(SPACE)
        parseSettings(line.substring(pos), cue)
        return true
      }
      this.parseTimestamp = function() {
        var ts = timestamp()
        if(line[pos] != undefined) {
          err("Timestamp must not have trailing characters.")
          return
        }
        return ts
      }
    }
    var WebVTTCueTextParser = function(line, errorHandler, mode) {
      var line = line,
          pos = 0,
          err = function(message) {
            if(mode == "metadata")
              return
            errorHandler(message, pos+1)
          }

      this.parse = function(cueStart, cueEnd) {
        var result = {children:[]},
            current = result,
            timestamps = []

        function attach(token) {
          current.children.push({type:"object", name:token[1], classes:token[2], children:[], parent:current})
          current = current.children[current.children.length-1]
        }
        function inScope(name) {
          var node = current
          while(node) {
            if(node.name == name)
              return true
            node = node.parent
          }
          return
        }

        while(line[pos] != undefined) {
          var token = nextToken()
          if(token[0] == "text") {
            current.children.push({type:"text", value:token[1], parent:current})
          } else if(token[0] == "start tag") {
            if(mode == "chapters")
              err("Start tags not allowed in chapter title text.")
            var name = token[1]
            if(name != "v" && name != "lang" && token[3] != "") {
              err("Only <v> and <lang> can have an annotation.")
            }
            if(
              name == "c" ||
              name == "i" ||
              name == "b" ||
              name == "u" ||
              name == "ruby"
            ) {
              attach(token)
            } else if(name == "rt" && current.name == "ruby") {
              attach(token)
            } else if(name == "v") {
              if(inScope("v")) {
                err("<v> cannot be nested inside itself.")
              }
              attach(token)
              current.value = token[3] // annotation
              if(!token[3]) {
                err("<v> requires an annotation.")
              }
            } else if(name == "lang") {
              attach(token)
              current.value = token[3] // language
            } else {
              err("Incorrect start tag.")
            }
          } else if(token[0] == "end tag") {
            if(mode == "chapters")
              err("End tags not allowed in chapter title text.")
            // XXX check <ruby> content
            if(token[1] == current.name) {
              current = current.parent
            } else if(token[1] == "ruby" && current.name == "rt") {
              current = current.parent.parent
            } else {
              err("Incorrect end tag.")
            }
          } else if(token[0] == "timestamp") {
            if(mode == "chapters")
              err("Timestamp not allowed in chapter title text.")
            var timings = new WebVTTCueTimingsAndSettingsParser(token[1], err),
                timestamp = timings.parseTimestamp()
            if(timestamp != undefined) {
              if(timestamp <= cueStart || timestamp >= cueEnd) {
                err("Timestamp must be between start timestamp and end timestamp.")
              }
              if(timestamps.length > 0 && timestamps[timestamps.length-1] >= timestamp) {
                err("Timestamp must be greater than any previous timestamp.")
              }
              current.children.push({type:"timestamp", value:timestamp, parent:current})
              timestamps.push(timestamp)
            }
          }
        }
        while(current.parent) {
          if(current.name != "v") {
            err("Required end tag missing.")
          }
          current = current.parent
        }
        return result
      }

      function nextToken() {
        var state = "data",
            result = "",
            buffer = "",
            classes = []
        while(line[pos-1] != undefined || pos == 0) {
          var c = line[pos]
          if(state == "data") {
            if(c == "&") {
              buffer = c
              state = "escape"
            } else if(c == "<" && result == "") {
              state = "tag"
            } else if(c == "<" || c == undefined) {
              return ["text", result]
            } else {
              result += c
            }
          } else if(state == "escape") {
            if(c == "&") {
              //err("Incorrect escape.") disable this so that URLS can exist
              result += buffer
              buffer = c
            } else if(/[abglmnsprt]/.test(c)) {
              buffer += c
            } else if(c == ";") {
              if(buffer == "&amp") {
                result += "&"
              } else if(buffer == "&lt") {
                result += "<"
              } else if(buffer == "&gt") {
                result += ">"
              } else if(buffer == "&lrm") {
                result += "\u200e"
              } else if(buffer == "&rlm") {
                result += "\u200f"
              } else if(buffer == "&nbsp") {
                result += "\u00A0"
              } else {
                err("Incorrect escape.")
                result += buffer + ";"
              }
              state = "data"
            } else if(c == "<" || c == undefined) {
              //err("Incorrect escape.") disable this so that URLS can exist
              result += buffer
              return ["text", result]
            } else {
              //err("Incorrect escape.") disable this so that URLS can exist
              result += buffer + c
              state = "data"
            }
          } else if(state == "tag") {
            if(c == "\t" || c == "\n" || c == "\f" || c == " ") {
              state = "start tag annotation"
            } else if(c == ".") {
              state = "start tag class"
            } else if(c == "/") {
              state = "end tag"
            } else if(/\d/.test(c)) {
              result = c
              state = "timestamp tag"
            } else if(c == ">" || c == undefined) {
              if(c == ">") {
                pos++
              }
              return ["start tag", "", [], ""]
            } else {
              result = c
              state = "start tag"
            }
          } else if(state == "start tag") {
            if(c == "\t" || c == "\f" || c == " ") {
              state = "start tag annotation"
            } else if(c == "\n") {
              buffer = c
              state = "start tag annotation"
            } else if(c == ".") {
              state = "start tag class"
            } else if(c == ">" || c == undefined) {
              if(c == ">") {
                pos++
              }
              return ["start tag", result, [], ""]
            } else {
              result += c
            }
          } else if(state == "start tag class") {
            if(c == "\t" || c == "\f" || c == " ") {
              classes.push(buffer)
              buffer = ""
              state = "start tag annotation"
            } else if(c == "\n") {
              classes.push(buffer)
              buffer = c
              state = "start tag annotation"
            } else if(c == ".") {
              classes.push(buffer)
              buffer = ""
            } else if(c == ">" || c == undefined) {
              if(c == ">") {
                pos++
              }
              classes.push(buffer)
              return ["start tag", result, classes, ""]
            } else {
              buffer += c
            }
          } else if(state == "start tag annotation") {
            if(c == ">" || c == undefined) {
              if(c == ">") {
                pos++
              }
              buffer = buffer.split(/[\u0020\t\f\r\n]+/).filter(function(item) { if(item) return true }).join(" ")
              return ["start tag", result, classes, buffer]
            } else {
              buffer +=c
            }
          } else if(state == "end tag") {
            if(c == ">" || c == undefined) {
              if(c == ">") {
                pos++
              }
              return ["end tag", result]
            } else {
              result += c
            }
          } else if(state == "timestamp tag") {
            if(c == ">" || c == undefined) {
              if(c == ">") {
                pos++
              }
              return ["timestamp", result]
            } else {
              result += c
            }
          } else {
            err("Never happens.") // The joke is it might.
          }
          // 8
          pos++
        }
      }
    }
    var WebVTTSerializer = function() {
      function serializeTree(tree) {
        var result = ""
        for (var i = 0; i < tree.length; i++) {
          var node = tree[i]
          if(node.type == "text") {
            result += node.value
          } else if(node.type == "object") {
            result += "<" + node.name
            if(node.classes) {
              for(var y = 0; y < node.classes.length; y++) {
                result += "." + node.classes[y]
              }
            }
            if(node.value) {
              result += " " + node.value
            }
            result += ">"
            if(node.children)
              result += serializeTree(node.children)
            result += "</" + node.name + ">"
          } else {
            result += "<" + node.value + ">"
          }
        }
        return result
      }
      function serializeCue(cue) {
        return cue.startTime + " " + cue.endTime + "\n" + serializeTree(cue.tree.children) + "\n\n"
      }
      this.serialize = function(cues) {
        var result = ""
        for(var i=0;i<cues.length;i++) {
          result += serializeCue(cues[i])
        }
        return result
      }
    }

    $(document).ready(function(){
      var parser = new WebVTTParser();
      var transcript = $('#acf-transcript_raw');
      var suppCont   = $('#acf-supporting_content_raw');

      transcript.attr('wrap', 'off');
      suppCont.attr('wrap', 'off');

      function initVTTField($field, alias){

        function getLineCount($el){
          return $el.height() / parseFloat($el.css('line-height'))|0;
        }

        function handleErrors(){
          var contents = $(this).val();
          var results = parser.parse(contents);
          $('#vtt-error-list-'+alias).remove();
          if(results.errors.length){
            var errorList = $('<div id="vtt-error-list-'+alias+'" class="vtt-error-list"></div>');
            var errorUl = $('<ul></ul>');
            for(var error of results.errors){
              var ln = error.line;
              var msg = error.message.replace('<','&lt;');
              var errorLi = $('<li data-err-line-'+alias+'="'+ln+'"><strong>'+ln+'</strong><em>'+msg+'</em></li>')
              errorUl.append(errorLi);
            }
            errorList.append(errorUl);
            $('body').append(errorList);
            resizeTextArea(function(){
              addLineNumbers();
            });
          }
        }

        function resizeTextArea(cb){
          setTimeout(function(){
            $field.css({height: 'auto', paddingTop: '0'});
            $field.height($field[0].scrollHeight);
            if(cb) cb();
          }, 0);
        }

        function addLineNumbers(){
          var $lines = $('#acf-'+alias+'-lines');
          var lineCount = getLineCount($field);
          var buffer = 3; //47; // for some reason this is always low
          var lineList = '';
          for(var i = 1; i <= lineCount + buffer; i++){
            lineList += '<div data-line-'+alias+'="' + i + '" class="line-number">' + i + '</div>';
          }
          if($lines.length){
            $lines.html(lineList);
          } else {
            $lines = $('<div id="acf-'+alias+'-lines" class="acf-line-numbers"></div>');
            $lines.append(lineList)
            $field.before($lines);
          }
        }
        addLineNumbers();

        function jumpToError(err){
          var newTop = $('[data-line-'+alias+'="'+err+'"]');
          if(!newTop) return;
          offset = newTop.offset().top - 50;
          newTop.addClass('was-jumped-to');
          setTimeout(function(){
            newTop.removeClass('was-jumped-to');
          },50);
          $('body,html').scrollTop(offset);
        }

        $field.keyup(function(){
          handleErrors.bind(this)();
          resizeTextArea(function(){
            addLineNumbers();
          });
        });

        $('body').on('click', '[data-err-line-'+alias+']', function(){
          var ln = $(this).attr('data-err-line-'+alias);
          jumpToError(ln);
        });
      }

      initVTTField(transcript, 'transcript');
      initVTTField(suppCont, 'suppCont');

      $('[data-key="tab_transcript_raw"], [data-key="tab_supporting_content_raw"]').click(function(){
        $('.vtt-error-list').remove();
      });

      $('#publish').click(function(){
        var goToError = function(err){
          var scrollTop = err.offset().top;
          $('body,html').scrollTop(scrollTop - 50);
        }
        var $jump = $('#jump-to-acf-error');
        setTimeout(function(){
          var $errors = $('.acf-input .acf-error-message');
          if(!$errors.length) return;
          if($jump.length){
            $jump.off('click');
          } else {
            $('body').append('<div id="jump-to-acf-error">Jump to Next Error</div>');
          }
          var curError = 0;
          $('#jump-to-acf-error').click(function(){
            var $errors = $('.acf-input .acf-error-message');
            var errLimit = $errors.length;
            curError = curError + 1;
            if(curError >= errLimit) curError = 0;
            var goTo = $($errors[curError]);
            goToError(goTo);
          });
        },1000);
      });

    });
  })(jQuery);
  </script>

<?php } );
