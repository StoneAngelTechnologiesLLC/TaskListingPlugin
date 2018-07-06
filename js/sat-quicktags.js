//sat-quicktags.js
//https://codex.wordpress.org/Quicktags_API

jQuery(document).ready(function()
{
	
//The Quicktags API allows for additional buttons in the Text (HTML) mode of the WordPress editor.
	
//QTags.addButton( $id, $display, $arg1, $arg2, $access_key, $title, $priority, $instance );

/*
Parameters

$id (string) (required) The html id for the button. Default: None

$display (string) (required) The html value for the button. Default: None

$arg1 (string) (required) Either a starting tag to be inserted like "<span>" or a callback that is 
executed when the button is clicked. Default: None

$arg2 (string) (optional) Ending tag like "</span>". Leave empty if tag doesn't need to be closed 
(i.e. "<hr />"). Default: None

$access_key (string) (optional) Shortcut access key for the button. Default: None

$title (string) (optional) The html title value for the button. Default: None

$priority (int) (optional) A number representing the desired position of the button in the toolbar. 
1 - 9 = first, 11 - 19 = second, 21 - 29 = third, etc. Default: None

$instance (string) (optional) Limit the button to a specific instance of Quicktags, 
add to all instances if not present. Default: None
	*/
	
	quicktags(
	{
		id:'minimum_requirements',
		buttons: 'em,strong,link,ui,li'
	});

	quicktags(
	{
		id:'preferred_requirements',
		buttons: 'em,strong,link,ui,li'
	});

});