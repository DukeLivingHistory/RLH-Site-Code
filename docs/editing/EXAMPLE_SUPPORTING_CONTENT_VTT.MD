# Supporting Content VTT

Supporting content can be added as VTT. Each node supports several data keys, represented by an all-caps label. For each node, these do not have to be in a particular order, but the label _must_ be present, correct, and allowable for that content type.

## Open by default

## Relevant code:
Code pertaining to this functionality is found in the following places:
* `lib/manage_raw_supp.php` (converts raw VTT into structured data and saves as ACF content)
* `assets/src/js/buildSuppInner.js` (constructs markup from structured data)

## Examples
### Text
```
00:00:00.000 --> 00:00:01.000
CONTENT Text to be display when expanded
```

### Quote
```
00:00:00.000 --> 00:00:01.000
QUOTE Quote text
ATTRIBUTION Source to whom attribution is given. Optional
```

### Image
```
00:00:00.000 --> 00:00:01.000
IMAGE URL of image. Must be from the site's media library
TITLE Name of image
```

### Gallery
```
00:00:00.000 --> 00:00:01.000
GALLERY WordPress ID of gallery to display
DESCRIPTION Description of content
```

### Map
```
00:00:00.000 --> 00:00:01.000
TITLE Name of content
```

### Internal link
```
00:00:00.000 --> 00:00:01.000
TITLE Name of content
INTERNAL_URL URL of page to link to
TIMESTAMP Specific timestamp to link to. Optional.
LINK_TEXT Text to display as link
```

### External link
```
00:00:00.000 --> 00:00:01.000
TITLE Name of content
URL URL of page to link to
DESCRIPTION Description of content. Optional
LINK_TEXT Text to display as link
```

### File
```
00:00:00.000 --> 00:00:01.000
TITLE Name of content
DESCRIPTION Description of content. Optional
FILE URL of file to link to
```

### Video
```
00:00:00.000 --> 00:00:01.000
TITLE Name of content
VIDEO Iframe embed code
```

### Open by default
```
00:00:00.000 --> 00:00:01.000
CONTENT This node will be closed

NOTE open by default

00:00:00.000 --> 00:00:01.000
CONTENT This node will be open
```
