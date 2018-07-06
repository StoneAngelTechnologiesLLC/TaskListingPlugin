<?php  
/**
*Plugin Name: Company Task Listings
*Text Domain: sat-task-listing
*Plugin URI: http://www.stoneangeltechnologies.com
*Description: This plugin adds a Task Listing option to the Admin Menu. Authorized users will have ability to create, edit, delete tasks which will be stored in database and can be sorted/reordered through a sub-menu page.  When creating or editing a task posting, the Task ID is automatically assinged(using the post's ID#); User Can: add Creation-Date, Completion-Deadline, Principle Duties, Preferred Requirements, indicate if Management Supervision is needed on task and catigorize tasks by departments and sub-departments.  The user can add short-code to WordPress Post or Page to display departments with open tasks, which are linked to a display of the postings held within that deparment. 

*Author: John Joseph Pietrangelo III
*Author URI: http://www.stoneangeltechnologies.com
*Version: 1.0
*/

/*
Copyright (C) 2018 Stone Angel Technologies

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

//Searching for a Global variable.
defined('ABSPATH') or die("Sorry, you cannot access this page directly.");

//Searching for a WP Core Function.
if( ! function_exists('add_action'))
{
    die("WordPress wasn't initiated correctly");
}

//there are 4 ways to include a file on your page? There is include(), include_once(), require() and require_once(). 

/*
The 'include' function is used in PHP when you want to include a file within the current process. It takes one argument which will be a string to the file path you want to include.

include 'main_page.php';

The code inside the included file will then run when the include function is called. This can be used in a php templating system where you have a header at the top of the page and the header is going to be the same on all pages. You will put this code inside it's own file and use the include function to add the file to the page.

include 'header.php';

<div id="content">

</div>

include 'footer.php';

If the file that you want to include is not found then this function to return a PHP warning, which is different to the require function which will return a fatal error. The file path given to the function can be either absolute starting with a / or relative by placing a .. before the file.

The include function will allow you to include the same file multiple times so you can use it within a loop.

foreach($products as $product)
{
    // will display all products.php
    include 'product.php';
}

This will include the product.php file as many times as it loops through the $products array, but if this was using a include_once function it will only display the product.php file once.

foreach($products as $product)
{
    // will only display one product
    include_once 'product.php';
}

The 'include_once' function is exactly the same as the include function except it will limit the file to be used once.

include_once 'main_page.php';

A more practical use of this function is if you define any functions in the included file to avoid redefinition of a function you should include it with a include_once.

The require function acts just like the include function except if the file can not be found it will throw a PHP error. As the name suggests this file is required for the application to work correctly. This will be a fatal error E_COMPILE_ERROR which will stop the application continuing, where the include function will just return a warning but will continue with the application. The require function is used exactly the same as the include function.

require 'main_page.php';

The last of the four functions is the require_once, which is a combination of the require and include_once function. It will make sure that the file exists before adding it to the page if it's not there it will throw a fatal error. Plus it will make sure that the file can only be used once on the page. This function is the most strict out of the four functions and is the function I use most when constructing the page. Going back to the example used in the include function the require_once function is what you should use when displaying things like the website header and footer. This is because you always want these files to be here if they are not here you want the site to error, and you only want these to appear once on the page.

require_once 'header.php';

<div id="content">

</div>

require_once 'footer.php';
*/

//$var = (plugin_dir_path(__FILE__);

//var_dump($var);

$dir = plugin_dir_path(__FILE__);

require_once( $dir . 'sat-task-custom-post-type.php' );
require_once( $dir . 'sat-task-metabox-fields.php' );
require_once( $dir . 'sat-task-sorting.php');
require_once( $dir . 'sat-task-shortcode.php');


function sat_enqueue_scripts_styles()
{
	global $pagenow, $typenow;

	$screen = get_current_screen();

	if($typenow == 'task')
	{
		// Linking local CSS file to plugin's configuration
		wp_enqueue_style( 'sat-task-listing-style', plugins_url( 'css/admin-tasks.css', __FILE__ ) );
	}

	if($pagenow == 'edit.php' && $typenow == 'task')
	{
		/*
			wp_enqueue_script( string $handle, string $src = '', array $deps = array(), string|bool|null $ver = false, bool $in_footer = false )

			$handle (string) (Required) Name of the script. Should be unique.

			$src (string) (Optional) Full URL of the script, or path of the script relative to the WordPress root directory.  Default value: ''

			$deps (array) (Optional) An array of registered script handles this script depends on.
			Default value: array()

			$ver (string|bool|null) (Optional) String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.  Default value: false

			$in_footer (bool) (Optional) Whether to enqueue the script before </body> instead of in the <head>. Default 'false'. Default value: false
		*/

		// Linking local JavaScript file to plugin's configuration
		//($script_name, $script_location, $script_dependancies, $version, $load@footer)
		wp_enqueue_script( 'reorder-js', plugins_url('js/reorder.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable' ), '20180619', true ) ;
		/*

			wp_localize_script( $handle, $name, $data );

			Description
			Localizes a registered script with data for a JavaScript variable.

			This lets you offer properly localized translations of any strings used in your script. This is necessary because WordPress currently only offers a localization API in PHP, not directly in JavaScript (but see ticket #20491).

			Though localization is the primary use, it can be used to make any data available to your script that you can normally only get from the server side of WordPress.

			

			$handle (string) (required) The registered script handle you are attaching the data for. Default: None

			$name (string) (required) The name of the variable which will contain the data. Note that this should be unique to both the script and to the plugin or theme. Thus, the value here should be properly prefixed with the slug or another unique value, to prevent conflicts. However, as this is a JavaScript object name, it cannot contain dashes. Use underscores or camelCasing.  Default: None

			$data  (array) (required) The data itself. The data can be either a single- or multi- (as of 3.3) dimensional array. Like json_encode(), the data will be a JavaScript object if the array is an associate array (a map), otherwise the array will be a JavaScript array. Default: None
			
			IMPORTANT! wp_localize_script() MUST be called after the script has been registered using wp_register_script() or wp_enqueue_script().

			Furthermore, the actual output of the JavaScript <script> tag containing your localization variable occurs at the time that the enqueued script is printed (output/included on the page). This has some significant repercussions if you enqueue your script as you should using the appropriate actions (wp_enqueue_scripts and admin_enqueue_scripts), but wish to localize later using data that is not available at enqueue time.

			In this case, consider enqueueing your script with the in_footer argument set to true, to delay the printing of your script include until much later in the page build (ie: wp_enqueue_script( $slug, $URL, $deps, $ver, true ); ). The last chance to localize your script would then be on the 'wp_print_footer_scripts' hook.
		*/
		// ($script_name of file where this localize_script() will be accessable, $object_name, array of name-value paired properties) 
		wp_localize_script( 'reorder-js', 'SAT_LOCALIZED_DATA', array( 'security' => wp_create_nonce('sat_task_order_nonce'), 'siteUrl' => get_bloginfo('url'), 'success' => 'The Task Sorting Order Has Been Successfully Saved To The Database!', 'failureAjax' => 'There Was An Error Sending Ajax Request To The Database, You Don\'t Have proper permissions.', 'failureToSave' => 'There Was An Error Saving Sorting Order To The Database, You Don\'t Have proper permissions.' ) );
	}

	if( ( $pagenow == 'post.php' || $pagenow == 'post-new.php') && ($typenow == 'task' || $screen->post_type == 'task' ) )
	{

		// Linking local Java-Script file to plugin's configuration.  array() holds 'quicktags' as dependancy, which is a function built into Word Press Core. 
		wp_enqueue_script( 'sat-custom-quicktags', plugins_url('js/sat-quicktags.js', __FILE__ ), array( 'quicktags' ), '20180616', true ) ;
		
		// Linking local Java-Script file to plugin's configuration.  array() holds 'jquery' & 'date picker' as dependancies, which is are libraries already built into Word Press Core. 	
		wp_enqueue_script( 'sat-admin-javascript', plugins_url( 'js/admin-tasks.js', __FILE__ ), array( 'jquery', 'jquery-ui-datepicker' ), '20180616', true);

		// Linking external styling jQuery API source for Date Picker.
		// If actual production.  Download file and connect it as an internal file for this plugin.
		wp_enqueue_style( 'jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
	}

}

add_action( 'admin_enqueue_scripts','sat_enqueue_scripts_styles' );



