# This is the public repo for the RLH project.

This repository contains a WordPress theme that contains the template files for the project, library code to make the back-end functionality work, and API endpoint setup.

## Dependencies

This theme requires the [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/) plugin to work. Note that all necessary fields for client-side functionality are instantiated in the `/models` directory – the exception are the supporting content fields, which are found in `lib/get_supp_cont_fields.php` so that they can be re-used.

There are several fields used by the homepage which (on the production site) are set up through the site's dashboard.

The theme also requires [Posts2Posts](https://wordpress.org/plugins/posts-to-posts/) to create relationships between pieces of content.

## Getting Started

First, you'll need to create an application through the [Google Developers' Console](https://console.developers.google.com). This is necessary to for YouTube integration and Google Maps functionality.

Create a set of credentials. Select 'Oauth Client ID' and 'Web application.' Leave the authorized Javascript origins field blank, but add `[YOUR SITE URL]/wp-admin/` to Authorized Redirect URIs.

Copy and paste your `Client ID` and `Client Secret` into the appropriate fields on the Options page of the WordPress dashboard.

Create a separate set of credentials for Google Maps, and paste your Client ID into the appropriate field.

For Facebook sharing functionality (which uses Facebook's SDK) to work, you'll need to set up an application through Facebook and provide the client ID.

### Your first interview

To add a video and grab the transcript from YouTube, add the 11-character video ID (found in the URL) in the 'YouTube Video ID' field and check the box by 'Pull transcript from YouTube?'. Upon saving the post, you'll be redirected and asked to authenticate through Google's OAuth2 – make sure you do this with the account that owns the video you've added. Upon authenticating, you'll be redirected back to the admin screen, and your transcript will be saved as a WebVTT file in the 'Transcript' field!

You can also freely upload your own WebVTT files to interviews – through either a directy file upload, or the drag-and-drop or raw transcript fields. If you'd like to rely on this exclusively for transcripts, there's no need to add Google API credentials. Please note that files should be uploaded in WebVTT format and follow the [standards](https://w3c.github.io/webvtt/).

### Updating Interviews

To update a transcript, check the appropriate "Update here?" checkbox by the field you're editing and click save. Note that because of the time needed to chunk a transcript and write it to the database, the drag-and-drop contents are deferred. (The code that handles this is in `lib/update_transcript_field.php`).

### Adding supplementary content

After a transcript has been saved, check the "Sync supplementary content?" checkbox and resave. This will cause the timestamps in the transcript to be set as timestamp options for the Supplementary Content metabox – note that this may erase existing content if the current timestamp is not present in the transcript.

## Under The hood

The `lib` directory contains several files that make the site's back-end functionality work. A brief overview of what they do is below:

* `add_confirm_to_delete.php` – Extends ACF repeater and layout fields to ask for user confirmation before deletion
* `admin_css.php` – Adds CSS to dashboard to make it more functional/friendly. Also contains scripts necessary to create a timestamp picker that can reference other content.
* `assets.php` – Enqueues stylesheets and scripts for front-end
* `body_attr.php` – Returns data attributions needed to be attached to body for JavaScript to work
* `connections.php` – Instantiates Posts2Posts relationships
* `fetch_transcript.php` – Includes OAuth2 code needed to retrieve transcripts from YouTube
* `get_app_part.php` – Helper function to grab static HTML from `app` submodule to include in template file. Contains path rewrites for static resources
* `get_og.php` – Writes OpenGraph tags to <head>
* `get_supp_cont_fields.php` – As mentioned above, returns information for ACF to instantiate supporting content fields`
* `icon.php` – Helper function to write svg icons to page using <use>
* `images.php` – Creates image sizes for site based on contents of `lib/img`
* `manage_raw_transcript.php` – Handles saving .vtt transcript and populating drag-and-drop upon edits to raw transcript field
* `photo_credits.php` – Creates image authorship fields for media library
* `sanitize_timestamps.php` – Normalizes video timestamps to number of seconds
* `save_timestamp.php` – Saved timestamps from a transcript as separate meta field so that they can be accessed by supporting content fields
* `save_sliced_transcript.php` – Calls regex from `models/Transcript.php` to parse a transcript and save it an a format usable by drag-and-drop
* `save_transcript_from_fields.php` – Creates .vtt transcript upon edits to drag-and-drop interface
* `save_txt_from_vtt.php` – Creates a plaintext version of .vtt transcripts (without timestamps)
* `save_vtt_from_fields.php` – Saves .vtt transcript to media library
* `site_options.php` – Creates ACF fields to site-wide options
* `sync_supp.php` – Passes timestamp meta to supporting content fields
* `update_transcript_field.php` – Handles deferred saving for drag-and-drop contents
* `wrapper.php` – Theme wrapper taken from [Sage](https://roots.io/sage/)

## Endpoints

### `/collections`

Returns information about collections template page, and an array of all collections.

```
{
  "name": string,
  "image": id,
  "items": array
}
```

### `/collections/:id`

Returns information about one collection, including an array of interviews or timelines in that collection.

```
{
  "id": id,
  "name": string,
  "image": id,
  "description": string,
  "link": string,
  "content": array
}
```

### `/interviews/` and `/timelines/`

Returns an array of all interviews or timelines. `collections` is an array of collection ids for collections that an interview belongs to. Accepts `count=[int]` and `offset=[int]` query params for the purpose of paginating results.

```
[
  {    
    "id": id
    "date": string
    "excerpt": string
    "img": id
    "link": string
    "title": string
    "type": Interview|Timeline
  }
]
```

### `/interviews/:id`

Returns information about one interview. `video_id` is the YouTube id of a video. `description` is used on other pages, `introduction` is used on the header of the interview itself.

```
{
  "id": id
  "name": string
  "link": string
  "image": id
  "description": string
  "collections": array
  "related" array
  "introduction": string
  "video_id": string
  "transcript_url": string
}
```

### `/interviews/:id/transcript`

Returns either the url of transcript for a video or the contents.

To return contents, add `?return=contents` query param to your request.

### `/interviews/:id/supp/`

Returns all supplementary content for an interview – see below.

```
[
  {
    "timestamp": timestamp,
    "type": type,
    "data": object
  }
]
```

### `/interviews/:id/timestamsp/`

Returns timestamp information about a piece of content – note that this works for both interviews and timelines. This endpoint is used by the back-end of the site for internal links, to link directly to a hash in another piece of content.

```
[
  {
    "hash": int,
    "title": string
  }
]
```


### `/timelines/`

Returns information about one timeline. `video_id` is the YouTube id of a video. `description` is used on other pages, `introduction` is used on the header of the interview itself.

```
{
  "id": id
  "name": string
  "link": string
  "image": id
  "description": string
  "collections": array
  "related" array
  "intro": string
  "events": array
}
```

### `/search/`

Returns information for a site-wide search. Accepts `term`, `offset`, and `count` query params.
