<?php

/*
                            *** To Create or Modify a Register-Post-Type ***

You can use this type of function in themes and plugins.
However, if you use it in a theme, your post-type will disappear from the Dashboard's Admin Menu, if a user switches away from your theme.

If you want to keep your changes e.g. post type, even if you switch between themes -> store Custom Postype in it's own plugin.
--------------------------------------------------------------------------------------------------------

                                        ** Reserved Post-Types **

The following post-types are reserved and used by WordPress already:

post
page
attachment
revision
nav_menu_item
custom_css
customize_changeset

In addition, the following post types should not be used as they interfere with other WordPress functions.

action
author
order
theme

                                                  Usage

<?php register_post_type( $post_type, $args ); ?>

                                                Parameters

$post_type:  (string) Post-type's name (maximum of 20 characters, it cannot contain capital letters or spaces)

$args: (array) An array of arguments.
*/

//The function which registers the customized, 'task' post-type.
function sat_register_post_type() 
{
    //                  ** Variable Declarations **

	$singular = __( 'Task Listing' );
	$plural = __( 'Task Listings' );

    //Used for the rewrite slug below.
    $plural_slug = str_replace( ' ', '_', $plural );

    //Setups all the labels to accurately reflect this post type.
    //Associative Array (key-value pairs)
	$labels = array(

    //General name for the post type, usually plural.
		'name' 					=> $plural,

    //Name for one object of this post type.
		'singular_name' 		=> $singular,

    //String for the submenu. Default is All Posts/All Pages.
        'all_items' =>  'All Task Listings',

    //The add new text. The default is "Add New" for both hierarchical and non-hierarchical post types.
		'add_new' 				=> 'Add New Task',

    // Default is Add New Post/Add New Page.
		'add_new_item' 			=> 'Add New ' . $singular,
		'edit'		        	=> 'Edit',

    //labels below use concatenation.
		'edit_item'	        	=> 'Edit ' . $singular,
		'new_item'	        	=> 'New ' . $singular,
		'view' 					=> 'View ' . $singular,
		'view_item' 			=> 'View ' . $singular,
		'search_term'   		=> 'Search ' . $plural,
		'parent' 				=> 'Parent ' . $singular,

    //The first default value is for non-hierarchical post types (like posts)
    //The second one is for hierarchical post types (like pages).
		'not_found' 			=> 'No ' . $plural .' found',
		'not_found_in_trash' 	=> 'No ' . $plural .' in Trash'

	);//End of $labels-array
        
    //To define all the arguments for the 'Task Listing' post type.
    //Below are the 'sat_register_post_type()'-function's arguments.
    //Associative Array (key-value pairs)
	$args = array(

    //Associate Array(value-pairs declared from above).
		'labels' 			  => $labels,

    //(boolean) (optional) Controls how the type is visible to authors(show_in_nav_menus, show_ui)
    //and readers(exclude_from_search, publicly_queryable). 
		'public'              => true,

    //boolean) (optional) Whether queries can be performed on the front end as part of parse_request()
    //Do you want this post-type to be part of the WP-loop.
        'publicly_queryable'  => true,

    //(boolean) (importance) Whether to exclude posts with this post type from front end search results.
    //'true' - site/?s=search-term will not include posts of this post type.
    //'false' - site/?s=search-term will include posts of this post type.
        'exclude_from_search' => false,

    //(boolean) (optional) Whether post_type is available for selection in navigation menus
        'show_in_nav_menus'   => true,

    //(boolean) (optional) Whether to generate a default UI for managing this post type in the admin.
    //built-in post types, such as post and page, are intentionally set to false
        'show_ui'             => true,

    //(boolean or string) (optional) Where to show the post type in the admin menu. show_ui must be true.
        'show_in_menu'        => true,

    //(boolean) (optional) Whether to make this post type available in the WordPress admin bar.
    //Default: value of the 'show_in_menu' property.
        'show_in_admin_bar'   => true,
    
    //The position in the menu order the post type should appear. show_in_menu must be true.
        'menu_position'       => 6,

    //The url to the icon to be used for this menu or the name of the icon from the iconfon.
        'menu_icon'           => 'dashicons-clipboard',

    //Will show-up with exportable items.
        'can_export'          => true,

    //When deleting a user-account, do you want their previous postings of 'this' post-type
    // to be deleted along with them.
        'delete_with_user'    => false,

    //Do you want this post-type to act like a page(true) or act like a post(false)
        'hierarchical'        => false,

    //(boolean or string) (optional) Enables post-type archive-page.
    // Will use $post_type as archive-slug by default.
        'has_archive'         => true,

    //changes url after '?' default uses post-type's slug
        'query_var'           => true,

    //declares who can have access to modify this custom register-post-type   
        'capability_type'     => 'page',

        'map_meta_cap'        => true,
    

    //      'capabilities' => array(),
    
    //(boolean or array) (optional) Triggers the handling of rewrites for this post type. To prevent rewrites, set to false.  Default: true and uses $post_type as slug
     
    // sets pre-perm-links   
        'rewrite' => array(
        	'slug' => strtolower( $plural_slug ),
        	'with_front' => true,
        	'pages' => true,
        	'feeds' => false,
        ),
        /*
         supports(array/boolean) (optional) An alias for calling add_post_type_support() directly.
         As of 3.5, boolean false can be passed as value instead of an array to prevent default (title and editor) behavior.
         Default: title and editor
         */
        //'supports' => false,
        
        'supports' => array(
            'title',
           // 'editor',
          //  'author',
           // 'custom-fields',
            'thumbnail'
        )
        
	); //End of $args-array
    
    //register_post_type( $post_type, $args );
    //register_post_type( name of post-type(slug), function's arguments );
    //Creating the post-type, using the above $args array, which is declared above.
	register_post_type( 'task', $args);
}

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

	register_taxonomy( 'Department', 'task', $args );

    /*
    Better be safe than sorry when registering custom taxonomies for custom post types. Use register_taxonomy_for_object_type() right after the function to interconnect them. Else you could run into minetraps where the post type isn't attached inside filter callback that run during parse_request or pre_get_posts.

    <?php register_taxonomy_for_object_type( $taxonomy, $object_type ); ?> 

    $taxonomy = (string) (required) The name of the taxonomy.  Default: None
    
    $object_type = (string) (required) A name of the object type for the taxonomy object.  Default: None
    */

    register_taxonomy_for_object_type( 'Department', 'task' );
}

//function to load theme style and structure to custom post-type's UI
function sat_load_templates( $original_template ) 
{
    //when page loads in wordpress if it is not equal to 'task', return nothing.
       if ( get_query_var( 'post_type' ) !== 'task' ) 
       {
               return;
       }
//using template-object methods to fire code if page is an archive or search page
       if ( is_archive() || is_search() ) 
       {
               if ( file_exists( get_stylesheet_directory(). '/archive-task.php' ) ) 
               {
                     return get_stylesheet_directory() . '/archive-task.php';
               } 

               else 
               {
                     return plugin_dir_path( __FILE__ ) . 'templates/archive-task.php';
               }

       } 

       elseif(is_singular('task')) 
       {
               if (  file_exists( get_stylesheet_directory(). '/single-task.php' ) ) 
               {
                       return get_stylesheet_directory() . '/single-task.php';
               } 

               else 
               {
                       return plugin_dir_path( __FILE__ ) . 'templates/single-task.php';
               }
       }

       else
       {
       		return get_page_template();
       }

       return $original_template;
}

/*
All 'register_post_type'-functions should only be 'called' through the 'init' action-hook.
Register-post-types will not work if called before the 'init' WP-operation,
and the operations of the 'register-post-type' will work incorrectly if called after the 'init' operation.
*** The 'init' action-hook fires after WordPress has finished loading but before any headers are sent. ***

WP's add_action()-function (triggering operation, fires responding function)*/
add_action( 'init', 'sat_register_post_type' );


add_action( 'init', 'sat_register_taxonomy' );

//(hook which is used is Word Press's template hierarchy, function to be called)
add_action( 'template_include', 'sat_load_templates' );

 