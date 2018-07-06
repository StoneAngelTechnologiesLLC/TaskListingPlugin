<?php
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
                'count'      => 5,
                'department'   => '',
                'pagination' => 'false'
        ), $atts );

	$pagination = $atts[ 'pagination' ]  == 'on' ? false : true;

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
        //appending department-name to string while making sure the fisrt letter in each word is capitalized.
    	$display_by_department .= '<h3>' . esc_html__( $atts[ 'title' ] ) . '&nbsp' . esc_html__( ucwords( $department ) ) . '</h3>';
        //appending unodered list to return string.
        $display_by_department .= '<ul>';

        while ( $tasks_by_department->have_posts() ) : $tasks_by_department->the_post();
        	
            global $post;
        	
            $deadline = get_post_meta( get_the_ID(), 'completion_deadline', true );

        	$title = get_the_title();

        	$slug = get_permalink();

        	$display_by_department .= '<li class="task-listing">';

            $display_by_department .= sprintf( '<a href="%s">%s</a>&nbsp&nbsp', esc_url( $slug ), esc_html__( $title ) );

            $display_by_department .= '<span>' . esc_html( $deadline ) . '</span>';

            $display_by_department .= '</li>';

        endwhile;

    $display_by_department .= '</ul>';

    $display_by_department .= '</div>';
    
    else:
    	$display_by_department = sprintf( __( '<p class="task-error">Sorry, no tasks listed in %s where found.</p>' ), esc_html__( ucwords( str_replace( '-', ' ', $atts[ 'Department' ] ) ) ) );

    endif;

    wp_reset_postdata();

    if ( $tasks_by_department->max_num_pages > 1  && is_page() ) 
    {
    	$display_by_department .= '<nav class="prev-next-posts">';

    	$display_by_department .= '<div call="nav-pervious">';

    	$display_by_department .= get_next_posts_link( __( '<span class="meta-nav">&larr;</span> Previous' ), $tasks_by_department->max_num_pages );

    	$display_by_department .= '</div';

    	$display_by_department .= '<div class="next-posts-link">';

    	$display_by_department .= get_previous_posts_link( __( '<span class="meta-nav">&rarr;</span> Next' ) );

    	$display_by_department .= '</div>';

    	$display_by_department .= '</nav>';
    }

    ////using 'return', instead of 'echo'. This will asure the shortcode-output is displayed were shortcode is placed, within the structure of the UI display.
    return $display_by_department;


 }

//name of shortcode ,  function is called whenever the 'tasks_by_department'-shortcode is executed
add_shortcode( 'tasks_by_department', 'sat_list_task_by_department' );
?>
