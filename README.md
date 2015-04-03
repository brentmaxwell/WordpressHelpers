# WordpressHelpers
WordPress helper classes

## CustomPostType
Create a constructor with the following properties:

*   `$this->post_type` **string**  
    `$post_type` parameter for [`register_post_type()`](https://codex.wordpress.org/Function_Reference/register_post_type)
*   `$this->options` **array**  
    `$args` parameter for [`register_post_type()`](https://codex.wordpress.org/Function_Reference/register_post_type)
*   `$this->meta_boxes` **array**  
    Array of arguments for creating meta_boxes.
	*   `id` **string**  
	    `$id` parameter for [`add_meta_box()`](https://codex.wordpress.org/Function_Reference/add_meta_box)
	*   `title` **string**  
	    `$title` parameter for [`add_meta_box()`](https://codex.wordpress.org/Function_Reference/add_meta_box)
	*   `context` **string**  
	    `$context` parameter for [`add_meta_box()`](https://codex.wordpress.org/Function_Reference/add_meta_box)
	*   `priority` **string**  
	    `$priority` parameter for [`add_meta_box()`](https://codex.wordpress.org/Function_Reference/add_meta_box)
	*   `fields` **array**  
	    Array of fields for the meta box.
		*   `id` **string**  
		    Id of the meta_field to display
		*   `title` **string**  
		    Title to display
*   `$this->columns` **array**  
    Array of columns to add in the format `meta_id => display_title`
	
The following methods can be overridden:

*   `register_post_type()`
*   `add_meta_box()`
*   `render_meta_box($post,$metabox)`
*   `generate_metabox_field($meta_id,$meta_value,$label)`
*   `add_columns($columns)`
*   `display_columns($column_name,$id)`

## CustomTaxonomy

Create a constructor with the following properties:

*   `$this->taxonomy_type` **string**  
    `$taxonomy` parameter for [`register_taxonomy()`](https://codex.wordpress.org/Function_Reference/register_taxonomy)
*   `$this->post_type` **string**  
    `$object_type` parameter for [`register_taxonomy()`](https://codex.wordpress.org/Function_Reference/register_taxonomy)
*   `$this->options` **array**  
    `$args` parameter for [`register_taxonomy()`](https://codex.wordpress.org/Function_Reference/register_taxonomy)

## GeoMetabox

Meta box to show geo content

## GoogleStaticMapsShortcode

Shortcode to display a google static map.

*   `class`  
    CSS class for the image
*   `height`  
    Height of the image
*   `width`  
    Width of the image
*   `polyline`  
    [Encoded polyline](https://developers.google.com/maps/documentation/staticmaps/#EncodedPolylines)
*   `markers`
    Space delimited string of markers
*   `maptype`  
    [Type of map](https://developers.google.com/maps/documentation/staticmaps/#MapTypes)
*   [`scale`](https://developers.google.com/maps/documentation/staticmaps/#scale_values)