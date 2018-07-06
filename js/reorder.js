jQuery(document).ready(function($) 
{

	//Storing DOM elements corrosponding with their ID-name, in variables
	var sortList = $( 'ul#custom-type-list' );
	var animation = $( '#loading-animation' );
	var pageTitle = $( 'div h2' );

//https://api.jqueryui.com/sortable/#event-update	
// update( event, ui )Type: sortupdate 
//This event is triggered when the user stopped sorting and the DOM position has changed.
	sortList.sortable(
		{ 
			update: function( event, ui ) //ajax update method
			{ 
				animation.show(); // to show loading wheel

				//ajax request
				$.ajax(
				{                                          //wp-admin/admin-ajax.php
					url: ajaxurl,     // global variable holding url above, to the WP file which handles ajax requests
					type: 'POST',     // type of request - In this case we are sending data to the server
					dataType: 'json', // format of the data returning from request.
					data:             // data we are sending to server.
						{
							action: 'save_sort', //custom function to store changes after the 'update event' is triggered.
							order: sortList.sortable( 'toArray' ), //passing new ID order of tasks on task list to database table
							security: SAT_LOCALIZED_DATA.security // Local varible created with Dynamic PHP variable using wp_localize_script() function located in Main plugin file.
						},

					// Triggered if Ajax request was recieved by server.
					success: function( response ) //Success callback-function
					{

						$( 'div#message' ).remove(); //to remove previous response message, if already present.

						animation.hide(); //to hide loading wheel

						// if database succesfully accepted the data sent in Ajax request, 
						//after the 'save_sort'-fuction processed it and updated wp_post table 
						if( true === response.success ) 
						{
							//appends below page title a div with id 'message' & class 'updated'
							//(which are both linked to core WP CSS) & the 'success'-string, 
							//located in the wp_local_script() of the main plugin file( sat-task-listing.php )
							pageTitle.after( '<div id="message" class="updated"><p>' + SAT_LOCALIZED_DATA.success + '</p></div>' );
						}
						
						// if database succesfully accepted the data sent in Ajax request, 
						//but the 'save_sort'-fuction's operations, on the data sent in Ajax request had failed
						else 	
						{
							//appends below page title a div with id 'message' & class 'error'
							//(which are both linked to core WP CSS) & the 'failureAjax'-string, 
							//located in the wp_local_script() of the main plugin file( sat-task-listing.php )
							pageTitle.after( '<div id="message" class="error"><p>' + SAT_LOCALIZED_DATA.failureAjax + '</p></div>' );
						}													
				
					},

					// Triggered if Ajax request was not accepted by server.
					error: function( error ) //Error function
					{
						
						$( 'div#message' ).remove(); //to remove previous response message, if already present.
						
						animation.hide(); //to hide loading wheel

						//appends below page title a div with id 'message' & class 'error'
						//(which are both linked to core WP CSS) & the 'failureToSave'-string, 
						//located in the wp_local_script() of the main plugin file( sat-task-listing.php )
						pageTitle.after( '<div id="message" class="error"><p>' + SAT_LOCALIZED_DATA.failureToSave + '</p></div>' );
					}													
				});
			}
		});

});