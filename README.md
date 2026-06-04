# DR Enhance

Lightweight WordPress plugin for code snippets and style overrides for DR (Digitálny Radca).

## Features

### ACF Thumbnail Position (Single Posts)
- Reads the custom field `umiestnenie_obrazka` on single post pages
- Adds one CSS class to `.post-thumbnail` based on the selected value
- Supports exactly 3 values:
	- `Hore` -> `position-Hore`
	- `Stred` -> `position-Stred`
	- `Dole` -> `position-Dole`
- Uses CSS `object-position` to control the visible focal point of the featured image

This helps editors avoid awkward crops (for example, cut-off faces) without template changes.

### Digi Posts Carousel Typography (Plugin-only)
- Styles carousel card titles and descriptions from the plugin CSS only (no direct theme file edits)
- Targets title links inside the carousel and limits them to 2 lines with ellipsis
- Targets description paragraphs inside the carousel and limits them to 3 lines with ellipsis
- Scope is limited to `.digi-posts-wrapper .slick-carousel` to avoid affecting other components

## ACF Setup

Install and activate **Advanced Custom Fields (ACF)**, then create this field:

- Field Group: `Blog` (or your existing post field group)
- Field Label: `Umiestnenie obrázka`
- Field Name: `umiestnenie_obrazka`
- Field Type: `Radio Button`
- Choices:
	- `Hore`
	- `Stred`
	- `Dole`

Location rule: show this field group for post type `post`.

## Installation

1. Upload the `dr-enhance` folder to `/wp-content/plugins/`
2. Activate the plugin in WordPress admin

## How It Works

1. On single posts, the plugin reads `umiestnenie_obrazka` from post meta.
2. If the value is `Hore`, `Stred`, or `Dole`, it prints a small inline script in `<head>`.
3. The script adds one class to `.post-thumbnail` after DOM load.
4. `assets/css/frontend.css` maps that class to `object-position`:
	 - `.post-thumbnail.position-Hore img { object-position: top; }`
	 - `.post-thumbnail.position-Stred img { object-position: center; }`
	 - `.post-thumbnail.position-Dole img { object-position: bottom; }`

5. For the Digi Posts carousel, plugin CSS applies text clamping rules:
	 - `.digi-posts-wrapper .slick-carousel .card .card-title a` -> 2 lines
	 - `.digi-posts-wrapper .slick-carousel .card .card-text` -> 3 lines

No theme template edits are required.

## Editor Workflow

1. Open or create a post.
2. Set a featured image.
3. Choose `Hore`, `Stred`, or `Dole` in `Umiestnenie obrázka`.
4. Publish or update the post.

The featured image crop focus is adjusted automatically on the frontend.

## Author

Aron Meszaros

## License

GPL-2.0-or-later
