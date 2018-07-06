<?php

//swp-custom-fields-for-tasks

//to create custom meta-box
function sat_add_custom_metabox()
{

/*
add_meta_box( string $id, string $title, callable $callback, string|array|WP_Screen $screen = null, string $context = 'advanced', string $priority = 'default', array $callback_args = null )

Parameters #Parameters

$id
(string) (Required) Meta box ID (used in the 'id' attribute for the meta box).

$title
(string) (Required) Title of the meta box.

$callback
(callable) (Required) Function that fills the box with the desired content. The function should echo its output.

$screen
(string|array|WP_Screen) (Optional) The screen or screens on which to show the box (such as a post type, 'link', or 'comment'). Accepts a single screen ID, WP_Screen object, or array of screen IDs. Default is the current screen. If you have used add_menu_page() or add_submenu_page() to create a new screen (and hence screen_id), make sure your menu slug conforms to the limits of sanitize_key() otherwise the 'screen' menu may not correctly render on your page.
Default value: null

$context
(string) (Optional) The context within the screen where the boxes should display. Available contexts vary from screen to screen. Post edit screen contexts include 'normal', 'side', and 'advanced'. Comments screen contexts include 'normal' and 'side'. Menus meta boxes (accordion sections) all use the 'side' context. Global
Default value: 'advanced'

$priority
(string) (Optional) The priority within the context where the boxes should show ('high', 'low').
Default value: 'default'

$callback_args
(array) (Optional) Data that should be set as the $args property of the box array (which is the second parameter passed to your callback).
Default value: null
*/

//2nd action 
//(HTML id,    title     , callback-function to display HTML, post-type where it will display, screen position,priority within screen position)
add_meta_box('sat_meta', 'Task Listing Editor','sat_meta_callback','task');
}

//1st action        (  WP-Hook(event) , Custom-Function(called on event) )
add_action( 'add_meta_boxes', 'sat_add_custom_metabox');

//------------------------------------------------------------------------------------------------

//Called by 'add_meta_box()'(A WP provided function) held in the 'swp_add_custom_metabox()'(custome function) above

//Using the variable '$post', passes the 'Global Post'-object, as an argument to the function's parameter
function sat_meta_callback($post)
{
	/*
	wp_nonce_field()(WP function):

	The nonce field is used to validate that the contents of the form, has come from the location of the current site and not somewhere else. The nonce does not offer absolute protection, but should protect against most cases. It is very important to use the 'nonce field' in forms.

	*** The $action and $name parameters are optional, but if you want to have better security, 
	    it is strongly suggested to set them.  

	    It is easier to call the function without any parameters.  However, because validation of the nonce doesn’t require any parameters, and the fact that crackers know that is the default, it won’t be difficult for them to find a way around your nonce and cause damage.
----------------------------------------------------

Parameters:

$action
(int|string) (Optional) Action's Name.
Default value: $action = -1

$name
(string) (Optional) Nonce's Name. 
Default value: $name = "_wpnonce"

$referer
(bool) (Optional) Whether to set the referer-field for validation.
Default value: $referer = true

$echo
(bool) (Optional) Whether to display or return hidden form field.
Default value: $echo = true 

Makes sure the data being stored in the Database is acctually coming from the from, instead of another source.

----------------------
Return-Value:

(string) Nonce field HTML markup.
-----------------------------------
Usage

<?php wp_nonce_field( $action, $name, $referer, $echo ) ?>
-----------------------------------------------
*/

	wp_nonce_field( basename( __FILE__ ), 'sat_tasks_nonce' );
/*
-----------------------------------------------------------------------------------------------------------------------------
  
  'get_post_meta()' Function: 

   Returns the values of custom fields, with the specified keys from the specified post'
   it is a wrapper for: get_metadata('post')
   
   To query database and retrive its result (the value of the designated key(string) in the designated post(int)) 

   The $single-parameter (optional) is a Boolean-value.  When set to true, the function will return a single result(as a string), if set to false or not set, the function returns an array of the custom fields.

   When dealing with retrieving serialized arrays 

                                           (manditory,optional,optional)
   Usage: <?php $meta_values = get_post_meta($post_id, $key, $single); ?>

*/ 

    //Retrieves the $post-variable's value from the database which match the 'Global-Post'-objects 'ID'-property's value, by using it as parameter's acceptable arugment. 
    
    //which is than assigned as the value of the $swp_stored_meta-variable (right-to-left operation process, of course)
	$sat_stored_meta = get_post_meta( $post -> ID);
	/*
	var_dump($sat_stored_meta);
	*/
	?>
	<div>

		<div class="meta-row">

			<div class="meta-th"> 

				<label for="task_id" class="sat-row-title"><?php _e('Task Id','sat-task-listing'); ?> </label>

			</div>

			<div class="meta-td">

				<input type="text" class="sat-content-row" name="task_id" id="task_id" readonly="true"value="<?php if(!empty($sat_stored_meta['task_id'])) 
				{
					/*
					For security on the other end of the spectrum, we have escaping. To escape is to take the data you may already have and help secure it prior to rendering it for the end user. WordPress thankfully has a few helper functions we can use for most of what we'll commonly need to do:

					esc_html() we should use anytime our HTML element encloses a section of data we're outputting.

						<h4><?php echo esc_html( $title ); ?></h4>
					
					esc_url() should be used on all URLs, including those in the 'src' and 'href' attributes of an HTML element.

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
					echo esc_attr( $sat_stored_meta['task_id'][0] );
				} 
				
				else
				{
					echo esc_attr( $post -> ID );
				}

			?>"/>

			</div>

		</div>

		<div class="meta-row">

			<div class="meta-th">

				<label for="date_assigned" class="sat-row-title"><?php _e( 'Date Assigned', 'sat-task-listing' ); ?></label>

			</div>

			<div class="meta-td">

				<input type="text" size=10  class="sat-row-content datepicker" name="date_assigned" id="date_assigned" value="<?php if (!empty ( $sat_stored_meta['date_assigned']))
				{ 
					echo esc_attr( $sat_stored_meta['date_assigned'][0] );
				}?>"/>

			</div>

		</div>

		<div class="meta-row">

			<div class="meta-th">

				<label for="completion_deadline" class="sat-row-title"><?php _e( 'Completion Deadline', 'sat-task-listing' ) ?></label>

			</div>

			<div class="meta-td">

				<input type="text" size=10 class="sat-row-content datepicker" name="completion_deadline" id="completion_deadline" value="<?php if (!empty ( $sat_stored_meta['completion_deadline']))
				{ 
					echo esc_attr( $sat_stored_meta['completion_deadline'][0] );
				}?>"/>

			</div>

		</div>

		<div class="meta">

			<div class="meta-th">

				<span><?php _e( 'Principle Duties', 'sat-task-listing' ) ?></span>

			</div>

		</div>

		<div class="meta-editor">

		<?php

		//Initializing and declaring variables and their values.
		//Varibles will be used as arguments to the 'wp_editor()' core Word Press function
		$content =  get_post_meta( $post -> ID, 'content', true );

		$editorID = 'principle_duties';

		$settings = array( 
							'wpautop' => true,//Whether to use wpautop for adding in paragraphs.
							'textarea_rows' => 8, //The number of rows to display for the textarea
							'media_buttons' => true, // Whether to display media insert/upload buttons
							'textarea_name' => 'content', //The name assigned to the generated textarea and passed parameter when the form is submitted.
							'drag_drop_upload' => 'true' //Enable Drag & Drop Upload Support
						  );
		/*
		\wp_editor( $content, $editor_id, $settings = array() );

		$content (string) (required) Initial content for the editor. Default: None

        $editor_id (string) (required) HTML id attribute value for the textarea and TinyMCE. (may only contain lowercase letters and underscores...hyphens will cause editor to not display properly)Default: None

		$settings (array) (optional) An array of arguments. Default: array()
		*/
		//Function to create customized editor
		wp_editor( $content, $editorID, $settings);

		?>

		</div>

		<div class="meta-row">

	        <div class="meta-th">

	          <label for="minimum_requirements" class="sat-row-title"><?php _e( 'Minimum Requirements', 'sat-task-listing')?></label>
	        
	        </div>

	        <div class="meta-td">

	          	<textarea class = "sat-textarea" name="minimum_requirements" id="minimum_requirements"><?php if (!empty( $sat_stored_meta['minimum_requirements'] ) ) 
	          	{
		          	echo esc_attr( $sat_stored_meta['minimum_requirements'][0] );
	          	} ?>
	          		
	          	</textarea>

	        </div>

	    </div>

	    <div class="meta-row">

        	<div class="meta-th">

	          <label for="preferred-requirements" class="sat-row-title"><?php _e( 'Preferred Requirements', 'sat-task-listing' )?></label>

	        </div>

	        <div class="meta-td">

	          	<textarea name="preferred_requirements" class ="sat-textarea" id="preferred_requirements"><?php
			          	if ( ! empty ( $sat_stored_meta['preferred_requirements'] ) ) 
			          	{
			            	echo esc_attr( $sat_stored_meta['preferred_requirements'][0] );
			          	}?>   
		     	</textarea>
	        
	        </div>

	    </div>

	    <div class="meta-row">

	        <div class="meta-th">

	          <label for="management_supervision" class="sat-row-title"><?php _e( 'Management Supervision', 'sat-task-listing'  )?></label>
	        
	        </div>

	        <div class="meta-td">

	          <select name="management_supervision" id="management_supervision">

	            <option value="yes" <?php if( ! empty ( $sat_stored_meta['management_supervision'] ) ) selected( $sat_stored_meta['management_supervision'][0], 'Yes' ); ?>><?php _e( 'Yes', 'sat-task-listing' )?></option>';

	              <option value="No" <?php if ( ! empty ( $sat_stored_meta['management_supervision'] ) ) selected( $sat_stored_meta['management_supervision'][0], 'No' ); ?>><?php _e( 'No', 'sat-task-listing' )?></option>';

	          </select>

	    	</div>

		</div>	 
<?php
}



function swp_meta_save($post_id)
{
	
	//Determines if the specified post is an autosave. 
	//(boolean|int) False if not a revision. Otherwise, returns ID of autosave's parent.
	$is_autosave = wp_is_post_autosave($post_id);

	//Determines if the specified post is a revision.
	//(boolean|int) False if not a revision. Otherwise, returns ID of revision's parent.
	$is_revision = wp_is_post_revision($post_id);

	// When form is being submitted, is there a task value for 'sat_tasks_nonce' && Verify that a nonce is correct and unexpired, with the respect to a specified action. The parameters for 

	//'wp_verify_nonce($nonce, $action)':

	//$nonce(string) (required) Name of the Nonce, to verify. Default: No defualt

	//$action(string/int) (optional) Action name. Should give the context to what is taking place and be the same when the nonce was created. Default: -1

	//Return Values (boolean/integer) Boolean false if the nonce is invalid. Otherwise, returns an integer with the value of:

    //1 – if the nonce has been generated in the past 12 hours or less.
    //2 – if the nonce was generated between 12 and 24 hours ago.

	$is_valid_nonce = (isset( $_POST['sat_tasks_nonce']) && wp_verify_nonce($_POST['sat_tasks_nonce'], basename(__FILE__))) ? 'true' : 'false';
	
	// if it is an autosave or a revison or the nonce is not valid, return without saving
	if($is_autosave || $is_revision || !$is_valid_nonce)
	{
		return;
	}

    //If there is data in 'task_id'-field(textbox) 
    	if ( isset( $_POST[ 'task_id' ] ) ) 
	{
		//  sanitize_text_field( string $str )Sanitizes a string from user input or from the database.
		/*
				Description: Checks for invalid UTF-8, Converts single < characters to entities
				Strips all tags, Removes line breaks, tabs, and extra whitespace, Strips octets

				!!!!  research following core functions in codex: 
				wp_check_invalid_utf8(), wp_strip_all_tags()

				The sanitize_*() class of helper functions are super nice for us, as they ensure we're ending up with safe data and require minimal effort on our part:

					sanitize_email()
					sanitize_file_name()
					sanitize_html_class()
					sanitize_key()
					sanitize_meta()
					sanitize_mime_type()
					sanitize_option()
					sanitize_sql_orderby()
					sanitize_text_field()
					sanitize_textarea_field()
					sanitize_title()
					sanitize_title_for_query()
					sanitize_title_with_dashes()
					sanitize_user()

				To recap: Follow the whitelist philosophy with data validation, and only allow the user to input data of your expected type. If it's not the proper type, discard it. When you have a range of data that can be entered, make sure you sanitize it. Escape data as much as possible on output to avoid XSS and malformed HTML.

Take a look through /wp-includes/formatting.php to see all of the sanitization and escaping functions WordPress has to offer.
		*/
		//updates the value of an existing meta key (custom field) for the specified post, to database
    	update_post_meta( $post_id, 'task_id', sanitize_text_field( $_POST[ 'task_id' ] ) );
    }

    //If there is data in 'date_assigned'-field(textbox) 
    if ( isset( $_POST[ 'date_assigned' ] ) ) 
    {
    	//  sanitize_text_field( string $str )Sanitizes a string from user input or from the database.
		/*
				Description: Checks for invalid UTF-8, Converts single < characters to entities
				Strips all tags, Removes line breaks, tabs, and extra whitespace, Strips octets
		*/
    	//updates the value of an existing meta key (custom field) for the specified post, to database
    	update_post_meta( $post_id, 'date_assigned', sanitize_text_field( $_POST[ 'date_assigned' ] ) );
    }

    //If there is data in 'completion_deadline'-field(textbox)  
    if ( isset( $_POST[ 'completion_deadline' ] ) ) 
    {
    	//  sanitize_text_field( string $str )Sanitizes a string from user input or from the database.
		/*
				Description: Checks for invalid UTF-8, Converts single < characters to entities
				Strips all tags, Removes line breaks, tabs, and extra whitespace, Strips octets
		*/
    	//updates the value of an existing meta key (custom field) for the specified post, to database
    	update_post_meta( $post_id, 'completion_deadline', sanitize_text_field( $_POST[ 'completion_deadline' ] ) );
    }

    //If there is data in 'principle_duties'-field(editor textbox) 
    if ( isset( $_POST[ 'content' ] ) ) 
    {
    	//  sanitize_text_field( string $str )Sanitizes a string from user input or from the database.
		/*
				Description: Checks for invalid UTF-8, Converts single < characters to entities
				Strips all tags, Removes line breaks, tabs, and extra whitespace, Strips octets
		*/
    	//updates the value of an existing meta key (custom field) for the specified post, to database
    	update_post_meta( $post_id, 'content', sanitize_text_field( $_POST[ 'content' ] ) );
    }
	
	//If there is data in 'preferred_requirements'-field(textbox) 
	if ( isset( $_POST[ 'preferred_requirements' ] ) ) 
	{

//  wp_kses_post( $data ); Sanitizes and Filters post content with allowed HTML tags and attributes intact. 
		
		//updates the value of an existing meta key (custom field) for the specified post, to database
		update_post_meta( $post_id, 'preferred_requirements', wp_kses_post( $_POST[ 'preferred_requirements' ] ) );
	}

	//If there is data in 'minimum_requirements'-field(textbox) 
	if ( isset( $_POST[ 'minimum_requirements' ] ) ) 
	{
		//  wp_kses_post( $data ); Sanitizes and Filters post content with allowed HTML tags and attributes intact. 

		//updates the value of an existing meta key (custom field) for the specified post, to database
		update_post_meta( $post_id, 'minimum_requirements', wp_kses_post( $_POST[ 'minimum_requirements' ] ) );
	}

	//If there is data in 'management_supervision'-field(dropbox)
	if ( isset( $_POST[ 'management_supervision' ] ) ) 
	{
		//  sanitize_text_field( string $str )Sanitizes a string from user input or from the database.
		/*
				Description: Checks for invalid UTF-8, Converts single < characters to entities
				Strips all tags, Removes line breaks, tabs, and extra whitespace, Strips octets
		*/
		//updates the value of an existing meta key (custom field) for the specified post, to database
		update_post_meta( $post_id, 'management_supervision', sanitize_text_field( $_POST[ 'management_supervision' ] ) );
	}

}

//3rd action      (  WP-Hook(event) , Custom-Function(called on event) )
add_action( 'save_post', 'swp_meta_save' );