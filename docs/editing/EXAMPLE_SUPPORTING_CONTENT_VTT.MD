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
_(Note: images will pull titles, captions, alt text, and photographer credits from the metadata attached to the image within the Media Library.)_
```
00:00:00.000 --> 00:00:01.000
IMAGE URL of image. Must be from the site's media library
```

### Gallery
```
00:00:00.000 --> 00:00:01.000
GALLERY WordPress ID of gallery to display
DESCRIPTION Description of content
```

### Map marker
_(Note: this is not functional, because how we need to store structured data is not easily attainable from human-friendly input. This can be revisited. This is how content should be correctly formatted, though.)_
```
00:00:00.000 --> 00:00:01.000
TITLE Name of content
ADDRESS Map address
```

### Internal link
_(Note: due to a mistake, the TITLE field for this piece of content was formerly output as LABEL when saving from the GUI supporting content editor. In order to provide backwards compatibility for content that was produced as a result of this mistake, the title field of this content type can be provided by LABEL instead. This functionality will not be deprecated.)_
```
00:00:00.000 --> 00:00:01.000
TITLE Name of content. Optional - defaults to the name of the content.
INTERNAL_URL URL of page to link to
DESCRIPTION description of content. Optional - defaults to the description of the content.
TIMESTAMP Specific timestamp to link to. Optional.
LINK_TEXT Text to display as link. Optional.
```

### External link
```
00:00:00.000 --> 00:00:01.000
TITLE Name of content
URL URL of page to link to
DESCRIPTION Description of content. Optional.
LINK_TEXT Text to display as link. Optional.
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
