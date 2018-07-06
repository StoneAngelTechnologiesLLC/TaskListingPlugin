<?php
/*
add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '' )

Description:

This function takes a capability which will be used to determine whether or not a page is included in the menu.

The function which is hooked in to handle the output of the page must check that the user has the required capability as well.

Parameters:

$parent_slug (string) (Required)  The slug name for the parent menu, or the file name of a standard WordPress admin file that supplies the top-level menu in which you want to insert your submenu, or your plugin file if this submenu is going into a custom top-level menu.

Examples:
For Dashboard: add_submenu_page('index.php',...)
For Posts: add_submenu_page('edit.php',...)
For Media: add_submenu_page('upload.php',...)
For Pages: add_submenu_page('edit.php?post_type=page',...) !! In Use For This Plugin
For Comments: add_submenu_page('edit-comments.php',...)
For Custom Post Types: add_submenu_page('edit.php?post_type=your_post_type',...)
For Appearance: add_submenu_page('themes.php',...)
For Plugins: add_submenu_page('plugins.php',...)
For Users: add_submenu_page('users.php',...)
For Tools: add_submenu_page('tools.php',...)
For Settings: add_submenu_page('options-general.php',...)

$page_title (string) (Required) The text to be displayed in the title HTML tags of the page when the menu is selected.

$menu_title (string) (Required) The text to be used for the menu.

$capability (string) (Required) The capability required for this menu to be displayed to the user.

$menu_slug (string) (Required) The slug name to refer to this menu by. Should be unique for this menu and only include lowercase alphanumeric, dashes, and underscores characters to be compatible with sanitize_key().

$function (callable) (Optional) The function that displays the page content for the menu page.
Technically, as in the add_menu_page function, the function parameter is optional, but if it is not supplied, then WordPress will basically assume that including the PHP file will generate the administration screen, without calling a function. Most plugin authors choose to put the page-generating code in a function within their main plugin file.
In the event that the function parameter is specified, It's possible to use any string for the menu_slug parameter. This allows usage of pages such as ?page=my_super_plugin_page instead of ?page=my-super-plugin/admin-options.php.Default value: ''

Return: 
(false|string) The resulting page's hook_suffix, or false if the user does not have the capability required.

*/

//function to configure new sub-menu page
function sat_add_submenu_page() 
{
	
	add_submenu_page( 
		'edit.php?post_type=task', //$parent_slug
		__( 'Reorder Tasks' ), //$page_title
		__( 'Reorder Tasks' ), //$menu_title
		__('manage_options'), 
		__('reorder_tasks'), 
		'reorder_admin_tasks_callback' // calling the function below
	);
}        //   WP-Hook         calling the function above
add_action( 'admin_menu', 'sat_add_submenu_page' );


//function to render settings page
function reorder_admin_tasks_callback() 
{
	//Variable holding the array of arguments used in constructing the WP_Query object.
	$args = array(
		'post_type' 			 => 'task',
		'orderby'			   	 => 'menu_order',
		'order'					 => 'ASC',
		'post_status'			 => 'publish',
		'no_found_rows' 		 => true,
		'update_post_term_cache' => false,
		'post_per_post' 		 => 50
	);
	
	//Obstanciating a WP_Query object 
	$task_listing = new WP_Query( $args );
	
	/*
	echo '<pre>';
	var_dump($task_listing);
	echo '</pre>';
	*/
	
	?>
	
	<div id="task-sort" class="wrap">
		
		<div id="icon-task-admin" class="icon32"><br /></div>
		
<!--echoing server-side content-->  <!--localized string-->           <!--WP's stock loading wheel image-->
		<h2><?php _e( 'Sort Task Positions', 'sat-task-listing' ); ?><img src="<?php echo esc_url( admin_url() . '/images/loading.gif' ); ?>" id="loading-animation"></h2>

	<!--if newly created WP_Query object holds posts ( WP-Loop )-->	
	<?php if ( $task_listing->have_posts() ) : ?>
<!--
				<p><?php// _e('<strong>Note:</strong> this only affects the tasks listed using the shortcode functions', 'sat-task-listing'); ?>
				</p>
-->
				<ul id="custom-type-list">

					<!--for each post, while newly created WP_Query object holds posts-->
					<?php while ( $task_listing->have_posts() ) : $task_listing->the_post(); ?>

           <!--assigning post's id value to <li>'s id value-->   <!--echoing out post title within <li> -->
						<li id="<?php esc_attr( the_id() ); ?>"><?php esc_html( the_title() ); ?></li>

					<?php endwhile; ?>

				</ul>

			<?php else: ?>
					<!--echoing localized string, if no posts are found in WP_Query object-->
				<p><?php _e( 'You have no Tasks to sort.', 'sat-task-listing' ); ?></p>

	<?php endif; ?>

	</div>

	<?php
}

// Function to save reordered task list.
function sat_save_reorder() 
{
	/*
		check_ajax_referer( $action, $query_arg, $die )

		This function can be overridden by plugins. If no plugin redefines this function, then the standard functionality will be used.

		The standard function verifies the AJAX request, to prevent any processing of requests which are passed in by third-party sites or systems.

		Nonces should never be relied on for authentication, authorization or access control. Protect your functions using current_user_can() and always assume that nonces can be compromised.

		$action (string) (optional) Action nonce  Default: -1
	
		$query_arg (string) (optional) where to look for nonce in $_REQUEST (since 2.5)  Default: false
	
		$die (boolean) (optional) whether to die if the nonce is invalid  Default: true
	*/
		                // ( name of nonce, location within ajax request where nonce is located )
	if ( ! check_ajax_referer( 'sat_task_order_nonce', 'security' ) ) 
	{
		/*
			wp_send_json_error( $data )

			Send a JSON response back to an Ajax request, indicating failure. The response object will always have a success key with the value false. If anything is passed to the function in the $data parameter, it will be encoded as the value for a data key.

			$data (mixed) (optional) Data to encode as JSON, then print and die.  Default: null
		*/
		return wp_send_json_error( 'Invalid Nonce' );
	}
		/*
			current_user_can( $capability , $object_id );

			Whether current user has a specific capability.  While checking against particular roles in place of a capability is supported in part, this practice is discouraged as it may produce unreliable results.

			$capability (string) (required) Role or capability.  Default: None
			
			$object_id  (int) (optional) Recommended when checking meta capabilities such as the capabilities defined in the `map_meta_cap` function i.e 'edit_post', 'edit_others_posts', 'read_post' etc. If omitted you may receive an 'Undefined offset: 0' warning (this is because the `current_user_can` function eventually calls `map_meta_cap` which when checking against meta capabilities expects an array but is only supplied a single value)  Default: None
		*/
	if ( ! current_user_can( 'manage_options' ) ) 
	{
		return wp_send_json_error( 'You are not allow to do this.' );
	}

	//storing values of the JSON, held in the 'order'-field of the '$_Post', sent via Ajax request 
	// " order: sortList.sortable( 'toArray' ) "
	$order = $_POST['order'];

	//counter used in the loop below.
	$counter = 0;

	//looping through each index of the $order array
	foreach( $order as $item_id ) 
	{
		//array-variable to store task's ID value( the value held by the 'order'-array's index ) & the counter number( for a referance to what order to save the task to the task-list within the database's wp_post table
		$post = array
		(
			'ID' => (int)$item_id,
			'menu_order' => $counter,
		);
		
		/*
			wp_update_post( $post, $wp_error );

			This function updates posts (and pages) in the database. To work as expected, it is necessary to pass the ID of the post to be updated.

			Note that when the post is "updated", the existing Post record is duplicated for audit/revision purposes. The primary record is then updated with the new values. Category associations, custom fields, post meta, and other related entries continue to be linked to the primary Post record.

			$post (array/object) (optional) An array or object representing the elements that make up a post. There is a one-to-one relationship between these elements and the names of columns in the wp_posts table in the database. You must include "ID", otherwise a WP_Error will be thrown.
			Default: An empty array

			$wp_error (Boolean) (optional) A Boolean that can be passed to control what is returned on failure. The default setting (false) will return a 0 if the post fails to update. However, if this is set to true, it will return with a WP_Error object.  Default: false
		*/
			//variable created above is used as argument for this function's parameter
		wp_update_post( $post );

		//increases counter-value by 1
		$counter++;
	}

	/*
		wp_send_json_success( $data );

		Send a JSON response back to an Ajax request, indicating success. The response object will always have a success key with the value true. If anything is passed to the function it will be encoded as the value for a data key.

		$data (mixed) (optional) Data to encode as JSON, then print and die.  Default: null
	*/
	wp_send_json_success( 'Post Saved.' );
}
//(dynamic hook* always starts with 'wp_ajax' then one appendeds it with name of the action defined in ajax request located in the reorder.js file, calling the function above )
add_action( 'wp_ajax_save_sort', 'sat_save_reorder' );