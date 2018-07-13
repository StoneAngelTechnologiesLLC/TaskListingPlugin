<?php   

//------------------------------------------------------------------------------------------------------

//$original_template: is the template wordpress was about to use prior to this function call
//function to load a themes style and structure, to custom post-type's UI
function sat_load_templates( $original_template ) 
{
    /*
        get_query_var( $var, $default )
        
        Retrieves public query variable in the WP_Query class of the global $wp_query object.

        $var (string) (required) The variable key to retrieve. Default: None
        
        $default (mixed) (optional) Value to return if the query variable is not set. 
        Default: empty string

        returns $default if var is not set
    */
    //when a page loads, if it is not equal to 'task', return nothing and exits function immediatly.
       if ( get_query_var( 'post_type' ) !== 'task' ) 
       {
               return;
       }

    /*
        is_search();

        This Conditional Tag checks if search result page archive is being displayed. This is a boolean function, meaning it returns either TRUE or FALSE.

        is_archive();

        This Conditional Tag checks if any type of Archive page is being displayed. An Archive is a Category, Tag, Author, Date, Custom Post Type or Custom Taxonomy based pages. This is a boolean function, meaning it returns either TRUE or FALSE

        is_archive() does not accept any parameters. If you want to check if this is the archive of a custom post type, use is_post_type_archive( $post_type )
    */
       //using Conditional Tags to trigger code if page is an archive or search page
       if ( is_archive() || is_search() ) 
       {
            //If the user adds custom-archive page for 'task'-custom post-type to their site's current theme by adding archive-task.php to the root file of the theme, use it!
               if ( file_exists( get_stylesheet_directory(). '/archive-task.php' ) ) 
               {
                     return get_stylesheet_directory() . '/archive-task.php';
               }

            //If not use the archive-task.php file located within this plugin's file directory
               else 
               {
                     return plugin_dir_path( __FILE__ ) . 'templates/archive-task.php';
               }

       } 

       /*
       is_singular( $post_types );

       This conditional tag checks if a singular post is being displayed, which is the case when one of the following returns true: is_single(), is_page() or is_attachment(). If the $post_types parameter is specified, the function will additionally check if the query is for one of the post types specified.

       $post_types (string/array) (optional) Post type or types to check in current query. Default: None
       */

       //using Conditional Tags to trigger code if page is a single post
       elseif(is_singular('task')) 
       {
               //If the user adds custom-single post for 'task'-custom post-type to their site's current theme by adding archive-task.php to the root file of the theme, use it!
               if (  file_exists( get_stylesheet_directory(). '/single-task.php' ) ) 
               {
                       return get_stylesheet_directory() . '/single-task.php';
               } 

               //If not use the single-task.php file located within this plugin's file directory
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

//(hook which is used is Word Press's template hierarchy, function to be called)
    //add_action( 'template_include', 'sat_load_templates' );