# Plaintext to VTT converter

One of the pieces of functionality needed by the site is to transform arbitrary plaintext into transcript files that can be consumed by the site's interactive elements. Each sentence will be treated as a single node.

Note that while this functionality _may_ work with simple HTML content, this is not officially supported behavior (see below)

The rules are as follows:
* Any punctuation (`.`, `!`, `?`) followed by a space _or_ a quotation mark will be treated as a sentence delineator
* Any punctuation _preceded_ by a capital letter will be parsed as an initial, _not_ as sentence delineator

Because of the site's internal system for handling paragraphs and notes within transcripts, any set of two new lines will be replaced with `NOTE paragraph` and any sentence starting with `NOTE` will be treated as a VTT note.

## Relevant code
Code pertaining to this functionality is found in the following places:

* lib/rich-text-format.php (creates and outputs Javascript in admin for this functionality)
* api/microservices/nlbaas.php (API endpoint for running regex with a PHP engine, allowing Javascript to use negative lookbehinds)

## Examples
Given the following input:

```
Testing. Testing! "Testing." C.R.E.A.M. is a song by the Wu-Tang Clan, not Doug E. Fresh.
```

the following output will be produced:

```
00:00:00.000 --> 00:00:01.000
Testing.

00:00:01.000 --> 00:00:02.000
Testing!

00:00:02.000 --> 00:00:03.000
"Testing."

00:00:03.000 --> 00:00:04.000
C.R.E.A.M. is a song by the Wu-Tang Clan, not Doug E. Fresh.
```

## Whitelisted Abbrevations
In the site's options page, comma-delineated abbreviations can be entered. Any abbreviations entered will _not_ trigger a new sentence, although they may match a pattern

For instance, given the input:
```
Mr.,Mrs.,Dr.
```

the text `Fred Rogers was the host of Mr. Rogers' Neighborhood.` would be treated as a single sentence.

## Why no HTML support?
* HTML is not a regular language and cannot be reliably parsed with regex. Simple tags will _probably_ be handled okay, but absolutely correct parsing is impossible given the number of any number of arbitrary data attributes the HTML5 spec supports.
* Sentenced-based parsing depends on characters like periods and exclamation points that _may_ be included inside of valid, non-text node, HTML tags. For instance, consider an `<img />` tag. The `src` property would contain a period in the file name. This increases the complexity of parsing substantially.
* HTML, by design, is divorced from grammatical structures. Consider the following example:

```
<div>
A list of <a href="https://en.wikiquote.org/wiki/Yogi_Berra">Yogi Berry</a> quotes:
<ul>
  <li>It ain't over till it's over</li>
  <li>The future ain't what it used to be.<li>
  <li>In theory there is no difference between theory and practice. In practice there is.</li>
</ul>
</div>
```
Semantically, this is not a collection of sentences. This is a piece of text, some of which is a link containing punctuation, and then a list. Puncutation-based parsing cannot produce content that can be reassembled back into the original, meaningful, markup.
* Finally, in order for the site's front-end functionality to function, each transcript "node" must be wrapped in a `span` tag with certain data attributes. We cannot reliably guarantee that this will produce valid HTML if the HTML is included.
