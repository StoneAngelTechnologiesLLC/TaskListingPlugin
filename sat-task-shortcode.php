<?php
/*
    For security, I have incorporated the escaping technique when displaying data. To escape is to take the data you may already have and help secure it prior to rendering it for the end user. WordPress thankfully has a few helper functions we can use for most of what we'll commonly need to do:

    esc_html() we should use anytime our HTML element encloses a section of data we're outputting.

        <h4><?php echo esc_html( $title ); ?></h4>
                    
    esc_url() should be used on all URLs, in the 'src' and 'href' attributes of an HTML element.

        <img src="<?php echo esc_url( $great_user_picture_url ); ?>" />

    esc_js() is intended for inline Javascript.

        <a href="#" onclick="<?php echo esc_js( $custom_js ); ?>">Click me</a>
                    
    esc_attr() can be used on everything else that's printed into an HTML element's attribute.

        <ul class="<?php echo esc_attr( $stored_class ); ?>">

    esc_textarea() encodes text for use inside a textarea element.

        <textarea><?php echo esc_textarea( $text ); ?></textarea>

    It's important to note that most WordPress functions properly prepare the data for output, and you don't need to escape again.

        <h4><?php the_title(); ?></h4>
*/


//Function to query WordPress and find all the department taxonomies that exsist with tasks associated with it and return that list to the page/post the short-code is added to.
function sat_task_taxonomy_list( $atts, $content = null ) 
{
    /*
    shortcode_atts( $pairs, $atts, $shortcode );

    Combines user shortcode attributes with known attributes and fills in defaults when needed. The result will contain every key from the known attributes, merged with values from shortcode attributes.
    
    $pairs (array) (required) Entire list of supported attributes and their defaults.  Default: None

    $atts (array) (required) User defined attributes in shortcode tag.  Default: None

    $shortcode (string) (optional) Shortcode name to be used by the shortcode_atts_{$shortcode} filter. If this is present, it makes a "shortcode_atts_$shortcode" filter available for other code to filter the attributes. It should always be included for maximum compatibility, however it is an optional variable.
    Default: None

    Return Values (array) Combined and filtered attribute list.
    */

	$atts = shortcode_atts( array( 'title' => 'Title Not Added To Short-Code'), $atts);

    /*
    get_terms( $taxonomy, $args );
    
    Retrieve the terms of a taxonomy or list of taxonomies. Returns an array object with the terms or a WP_error if one of the taxonomies does not exist.

    $taxonomy = array( 
                        'department',
                      );

    $args = array(
                    'orderby'           => 'name', 
                    'order'             => 'ASC',
                    'hide_empty'        => true, 
                    'exclude'           => array(), 
                    'exclude_tree'      => array(), 
                    'include'           => array(),
                    'number'            => '', 
                    'fields'            => 'all', 
                    'slug'              => '',
                    'parent'            => '',
                    'hierarchical'      => true, 
                    'child_of'          => 0,
                    'childless'         => false,
                    'get'               => '', 
                    'name__like'        => '',
                    'description__like' => '',
                    'pad_counts'        => false, 
                    'offset'            => '', 
                    'search'            => '', 
                    'cache_domain'      => 'core'
                  ); 

        $departments = get_terms($taxonomy, $args);

    */

    //storing all Task-posts tagged with this plugin's customized 'department' taxonomy.
	$departments = get_terms( 'Department' ); 
    //If we were searching for Standard 'Post' categories, we would enter 'Category' or tags would be 'Tag'


    // If posts are tagged with 'department'-taxonomy and variable is not populated by the WP_Error class.
	if( ! empty( $departments ) && ! is_wp_error( $departments ) ) 
    {
        //a single variable to hold the div to display tagged departments 
		$displaylist = '<div id="tasks-department-list">';

        //adding a heading to the div by appending the $displaylist-variable
		$displaylist .= '<h3>' . esc_html__( $atts[ 'title' ] ) . '</h3>';

        //adding an unordered list to the div by appending the $displaylist-variable
		$displaylist .= '<ul>';

        //loop through each department with a task associated to it
		foreach( $departments as $department ) 
        {
            //adding an unordered list-item to the list by appending the $displaylist-variable
			$displaylist .= '<li class="task-department">';

            //adding an anchor-tag to the list-item 
			$displaylist .= '<a href="' . esc_url( get_term_link( $department ) ) . '">';

            //adding the name of the department, to be displayed as the list-item
			$displaylist .= esc_html__( $department->name ) . '</a></li>';
		}

    //adding the closing brakets to both the unordered-list and the div holding it.
	$displaylist .= '</ul></div>';

	}
//using 'return', instead of 'echo'. This will asure the shortcode-output is displayed were shortcode is placed, within the structure of the UI display.
	return $displaylist;
}

//           ( shortcode's tag-name, function to execute when shortcode's tag-name is called.
add_shortcode( 'tasks_department_list', "sat_task_taxonomy_list" );

//------------------------------------------------------------------------------------------------------

function sat_list_task_by_department( $atts, $content = null ) 
{
	
?>
<style>
h3
{
 padding-bottom: 10px;
 padding-top: 20px;
}

#tasks-department-list
{
        margin: 20px 0; 
}
#tasks-department-list li
{
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color:#f7f7f7;
    max-width: 400px;
    padding: 10px;
}

#tasks-by-department 
{ 
    margin: 20px 0; 
}
#tasks-by-department li
{
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color:#f7f7f7;
    max-width: 400px;
    padding: 10px;
    
}

</style>
<?php

//triggered if no department is set for the Query request.
    if ( ! isset( $atts['department'] ) )
    {//core WP class, to be able to target error with CSS.
       return '<p class="task-error">You must provide a department for this shortcode to work.</p>'; 
    }

	$atts = shortcode_atts( array(
                'title'      => 'Current Open Tasks In...  ',
                'count'      => 3,
                'department'   => '',
                'pagination' => 'off'
        ), $atts );
//using count value instead of total posts listed in department as max amount of posts to display per page
	$pagination = $atts[ 'pagination' ]  == 'on' ? false : true;

//Used in all pages that use pagination(multiple pages to hold posts)
	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

    //the arguments for the WP_Query() parameter.
    $args = array(
            'post_type' 		=> 'task',
            'post_status'       => 'publish',
            'no_found_rows'     => $pagination,
            'posts_per_page'    => $atts[ 'count' ],
            'paged'			    => $paged,
            'tax_query' 		=> array(
                    array(
                            'taxonomy' => 'Department',
                            'field'    => 'slug',
                            'terms'    => $atts[ 'department' ],
                    ),
            )
    );

    $tasks_by_department = new WP_Query( $args );

    
/*
Using the core WordPress Loop

The Loop is PHP code used by WordPress to display posts. Using The Loop, WordPress processes each post to be displayed on the current page, and formats it according to how it matches specified criteria within The Loop tags. Any HTML or PHP code in the Loop will be processed on each post.

When WordPress documentation says "This tag must be within The Loop", such as for specific Template Tags or plugins, the tag will be repeated for each post. For example, The Loop displays the following information by default for each post:

Title (the_title())
Time (the_time())
Categories (the_category()).

You can display other information about each post using the appropriate Template Tags or (for advanced users) by accessing the $post variable, which is set with the current post's information while The Loop is running.

The two important global variables for loops are:

$wp_query which is an object of class WP_Query, holding a WP database query result amongst which $wp_query->posts, an array of individual WP_Posts.

$post which is the current object of class WP_Post

have_posts() and the_post() are global functions calling the corresponding $wp_query->have_posts() and $wp_query->the_post() methods of the $wp_query global variable.

the_post() looks like a template tag, but it isn't. It does not produce output, but instead changes the state of the $wp_query and $post global variables: the_post() tells WordPress to move to the next post. It changes $wp_query->current_position, and initialises the $post global variable to the next post contained in $wp_query->posts array.

Remember: All the template tags rely on the $post global variable by default and the $post global variable is set/modified by the_post(), which gets its data from the $wp_query global variable. $post is also set/modified by WP_Query::the_post() as used in secondary loops.
*/
    if ( $tasks_by_department-> have_posts() ) :
    	
        //storing requested department into a variable, to be able to replace the string's (-) spacers, with a blank spacers (what to replace, what to replace with, where to look for what to replace)
        $department = str_replace( '-', ' ', $atts['department'] );
    	
        //String to return, for function output.
        $display_by_department = '<div id="tasks-by-department">';
        //appending department-name to string while making sure the letters in each word of the department are capitalized using ucwords().
    	$display_by_department .= '<h3>' . esc_html__( $atts[ 'title' ] ) . '&nbsp' . esc_html__( ucwords( $department ) ) . '</h3>';
        //appending unodered list to return string.
        $display_by_department .= '<ul>';

        //while there is a task present in department, the_post() changes the state of the $wp_query and $post global variables: the_post() tells WordPress to move to the next post. 
        
        //It changes $wp_query->current_position, and initialises the $post global variable to the next post contained in $wp_query->posts array.
        while ( $tasks_by_department->have_posts() ) : $tasks_by_department->the_post();
        	
            //referencing WordPress global variable $post, which is the current object(task listing) of class WP_Post
            global $post;
        	
            
            /*get_post_meta( int $post_id, string $key = '', bool $single = false )

                $post_id (int) (Required) Post ID.

                $key (string) (Optional) The meta key to retrieve. By default, returns data for all keys. Default value: ''

                $single (bool) (Optional) Whether to return a single value. Default value: false

                Return: (mixed) Will be an array if $single is false. 
                                Will be value of meta data field if $single is true.
            */
            //storing the post's meta-data from it's completion_deadline field. 
            $deadline = get_post_meta( get_the_ID(), 'completion_deadline', true );

            //storing the post's meta-data from it's title field.
        	$title = get_the_title();

            //storing the post's URL
        	$slug = get_permalink();

            //appending list item to the return string
        	$display_by_department .= '<li class="task-listing">';
            
            /*        wp_sprintf( string $pattern )
            $pattern (string) (Required) The string which formatted args are inserted.
            $args (mixed) (Required) ,... Arguments to be formatted into the $pattern string. 
            Return: $pattern (string) (Required) The string which formatted args are inserted.
            */
            //appending a href link to the post. This creates a hyperlink for the list item
            //                                       %s = argument space-holders   arg-1 anchor tag
            $display_by_department .= sprintf( '<a href="%s">%s</a>&nbsp&nbsp', esc_url( $slug ), esc_html__( $title ) );// arg-2 content displayed to screen.

            //appending the $deadline-value to the retyrn string
            $display_by_department .= '<span>' . esc_html( $deadline ) . '</span>';

            //appending closing list-item tag to return string
            $display_by_department .= '</li>';
        //ends while-loop
        endwhile;

    //one loop is complete this line appends closing unordered-list tag to the return string
    $display_by_department .= '</ul>';

    //appends closing div tag to the return string
    $display_by_department .= '</div>';
    
    //else statement is triggered if posts are not present in selected department
    else:
    	$display_by_department = sprintf( __( '<p class="task-error">Sorry, no tasks listed in %s where found.</p>' ), esc_html__( ucwords( str_replace( '-', ' ', $atts[ 'Department' ] ) ) ) );
    //ends if-statement
    endif;

    /*                      wp_reset_postdata();

    Use this function to restore the context of the template tags from a secondary query loop back to the main query loop.

    Differences between the main query loop and secondary query loops are:

    the main query loop is based on the URL request and is initialised before theme templates are processedsecondary query loops are queries (using new WP_Query) in theme template or plugin files

    A secondary query loop using $sec_query = new WP_Query() and $sec_query->the_post() affects the global $post variable. The global $post variable is used by template tags by default. 

    wp_reset_postdata() restores the global $post variable to the current post in the main query (contained in the global $wp_query variable as opposed to the $sec_query variable), so that the template tags refer to the main query loop by default again.
    */
    wp_reset_postdata();

//checking to see if WP_Query object-variable has more posts than one page holds and is a single page
    if ( $tasks_by_department->max_num_pages > 1  && is_page() ) 
    {
        //appends a navigation element, to the return string.
    	$display_by_department .= '<nav class="prev-next-posts">';

        //sets up a div to hold pagination arrow
    	$display_by_department .= '<div call="nav-pervious">';

        //appends display of pagination arrow(&larr;) and the word "previous" which is linked to previous posts URL and uses max_num_pages value to only show pagination arrow and text, if needed.
    	$display_by_department .= get_next_posts_link( __( '<span class="meta-nav">&larr;</span> Previous' ), $tasks_by_department->max_num_pages );

        //appends closing tag for the div directly above
    	$display_by_department .= '</div';

        //appends a navigation element, to the return string.
    	$display_by_department .= '<div class="next-posts-link">';

         //appends display of pagination arrow(&rarr;) and the word "next" which is linked to next posts URL 
    	$display_by_department .= get_previous_posts_link( __( '<span class="meta-nav">&rarr;</span> Next' ) );

        //appends closing tag for the div directly above
    	$display_by_department .= '</div>';

        //appends a closing tag for the navigation element, to the return string.
    	$display_by_department .= '</nav>';
    }

    ////using 'return', instead of 'echo'. This will asure the shortcode-output is displayed were shortcode is placed, within the structure of the UI display.
    return $display_by_department;


 }

//name of shortcode ,  function is called whenever the 'tasks_by_department'-shortcode is executed
add_shortcode( 'tasks_by_department', 'sat_list_task_by_department' );
?>
