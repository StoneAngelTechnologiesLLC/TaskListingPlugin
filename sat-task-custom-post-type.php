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





//--------------------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------

    /*
    All 'register_post_type'-functions should only be 'called' through the 'init' action-hook.
    Register-post-types will not work if called before the 'init' WP-operation,
    and the operations of the 'register-post-type' will work incorrectly if called after the 'init' operation.

*** The 'init' action-hook fires after WordPress has finished loading but before any headers are sent. ***

    WP's add_action()-function (triggering operation, fires responding function)*/
    add_action( 'init', 'sat_register_post_type' );




 