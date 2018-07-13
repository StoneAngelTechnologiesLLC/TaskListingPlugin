<?php  
/*--------------------------------------------------------------------------------------------------------
                                                    Taxonomies

When registering a post type, always register your taxonomies using the taxonomies argument.
If you don't, the taxonomies and post-type will not be recognized as connected, when using filters such as parse_query or pre_get_posts. This can lead to unexpected results and failures.

Even if you register a taxonomy while creating the post-type, you must still explicitly register and define the taxonomy using 'register_taxonomy()' function.
--------------------------------------------------------------------------------------------------------*/
/*
                                                  Usage

<?php register_taxonomy( $taxonomy, $object_type, $args ); ?> 

Use the init action to call this function. Calling it outside of an action can lead to troubles. 

                                                Parameters

$taxonomy
(string) (required) The name of the taxonomy. Name should only contain lowercase letters and the underscore character, and not be more than 32 characters long (database structure restriction).
Default: None

$object_type
(array/string) (required) Name of the object type for the taxonomy object. Object-types can be built-in Post Type or any Custom Post Type that may be registered.
Default: None

$args
(array/string) (optional) An array of Arguments.
Default: None

Better be safe than sorry when registering custom taxonomies for custom post types. Use register_taxonomy_for_object_type() right after the function to interconnect them. Else you could run into minetraps where the post type isn't attached inside filter callback that run during parse_request or pre_get_posts.


*/
function sat_register_taxonomy() 
{
    //                  ** Variable Declarations **

	$plural = _x( 'Departments', 'taxonomy general name' );

	$singular = _x( 'Department', 'taxonomy singular name');

    //(string) (optional) A plural descriptive name for the taxonomy marked for translation.
    //Default: overridden by $labels->name
	$labels = array(
        //general name for the taxonomy, usually plural. The same as and overridden by $tax->label. Default is _x( 'Post Tags', 'taxonomy general name' ) or _x( 'Categories', 'taxonomy general ' ). When internationalizing this string, please use a gettext context matching your post type. Example: _x('Writers', 'taxonomy general name');
		'name'                       => $plural,

        //name for one object of this taxonomy. Default is _x( 'Post Tag', 'taxonomy singular name' ) or _x( 'Category', 'taxonomy singular name' ). When internationalizing this string, please use a gettext context matching your post type. Example: _x('Writer', 'taxonomy singular name');
        'singular_name'              => $singular,
        
        //the search items text. Default is __( 'Search Tags' ) or __( 'Search Categories' )
        'search_items'               => __( 'Search Departments' ),

        // the popular items text. This string is not used on hierarchical taxonomies. Default is __( 'Popular Tags' ) or null
        'popularname_items'              => __( 'Popular Departments' ), 

        //the all items text. Default is __( 'All Tags' ) or __( 'All Categories' )
        'all_items'                  => __( 'All Departments' ),
        // the parent item text. This string is not used on non-hierarchical taxonomies such as post tags. Default is null or __( 'Parent Category' )
        'parent_item'                => null,

        //The same as parent_item, but with colon : in the end null, __( 'Parent Category:' )
        'parent_item_colon'          => null,

        //The same as parent_item, but with colon : in the end null, __( 'Parent Category:' )
        'edit_item'                  => __( 'Edit Department' ),

        //the update item text. Default is __( 'Update Tag' ) or __( 'Update Category' )
        'update_item'                => __( 'Update Department' ),

        //the add new item text. Default is __( 'Add New Tag' ) or __( 'Add New Category' )
        'add_new_item'               => __( 'Add New Department' ),

        //the new item name text. Default is __( 'New Tag Name' ) or __( 'New Category Name' )
        'new_item_name'              => __( 'New Department' ),

        //the separate item with commas text used in the taxonomy meta box. This string is not used on hierarchical taxonomies. Default is __( 'Separate tags with commas' ), or null
        'separate_items_with_commas' => __( 'Separate Departments with commas' ),

        //the add or remove items text and used in the meta box when JavaScript is disabled. This string is not used on hierarchical taxonomies. Default is __( 'Add or remove tags' ) or null
        'add_or_remove_items'        => __( 'Add or remove Departments' ),

        //the choose from most used text used in the taxonomy meta box. This string is not used on hierarchical taxonomies. Default is __( 'Choose from the most used tags' ) or null
        'choose_from_most_used'      => __( 'Choose from the most used Departments' ),

        //(3.6+) - the text displayed via clicking 'Choose from the most used tags' in the taxonomy meta box when no tags are available and (4.2+) - the text used in the terms list table when there are no items for a taxonomy. Default is __( 'No tags found.' ) or __( 'No categories found.' )
        'not_found'                  => __( 'No Departments Found.' ),

        //the menu name text. This string is the name to give menu items. If not set, defaults to value of name label.
        'menu_name'                  => __('Add New Department' )
	);

	$args = array(
    //(boolean) (optional) Is this taxonomy hierarchical (have descendants) like categories or not hierarchical like tags.  Default: false
		'hierarchical'          => true,

    //(array) (optional) labels - An array of labels for this taxonomy. By default tag labels are used
    //for non-hierarchical types and category labels for hierarchical ones.
    //Default: if empty, name is set to label value, and singular_name is set to name value
        'labels'                => $labels,

    //(boolean) (optional) Whether to generate a default UI for managing this taxonomy.
    //Default: if not set, defaults to value of public argument. As of 3.5, setting this to false for 
    //attachment taxonomies will hide the UI.
        'show_ui'               => true,

    //(boolean) (optional) Whether to allow automatic creation of taxonomy columns on associated 
    //post-types table. (Available since 3.5)  Default: false
        'show_admin_column'     => true,

    //(string) (optional) A function name that will be called when the count of an associated $object_type, such as post, is updated. Works much like a hook.
        'update_count_callback' => __( '_update_post_term_count' ),

    //(boolean or string) (optional) False to disable the query_var, set as string to use custom 
    //query_var instead of default which is $taxonomy, the taxonomy's "name". True is not seen as a valid
    //entry and will result in 404 issues.  Default: $taxonomy
        'query_var'             => true,

    //(boolean/array) (optional) Set to false to prevent automatic URL rewriting a.k.a. 
    //"pretty permalinks". Pass an $args array to override default URL settings for permalinks 
    //in the case below, I am declaring only 1 of the array of 'rewrite'-indexes(properties)
        'rewrite'               => array( 'slug' => 'department' ),
	);

	register_taxonomy( 'department', 'task', $args );

    /*
    Better be safe than sorry when registering custom taxonomies for custom post types. Use register_taxonomy_for_object_type() right after the function to interconnect them. Else you could run into minetraps where the post type isn't attached inside filter callback that run during parse_request or pre_get_posts.

    <?php register_taxonomy_for_object_type( $taxonomy, $object_type ); ?> 

    $taxonomy = (string) (required) The name of the taxonomy.  Default: None
    
    $object_type = (string) (required) A name of the object type for the taxonomy object.  Default: None
    */

    register_taxonomy_for_object_type( 'department', 'task' );
}

 add_action( 'init', 'sat_register_taxonomy' );