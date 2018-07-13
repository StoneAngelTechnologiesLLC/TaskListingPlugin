<?php 
//-------------------------------------------------------------------------------------------------------


function sat_add_department_social_metadata()
{
    wp_nonce_field( basename( __FILE__ ), 'sat_department_social_nonce' );

    $social_networks= array(
                    'facebook' => 'FaceBook', 
                    'linkedin' => 'LinkedIn',
                    'github' => 'GitHub'
                );



?>
<!-- //passing default WP attributes to table head-->
    <th scope="row" valign="top" colspan="2">
        <!-- //escaping, sanitizing and echoing string-->
        <h3><?php esc_html_e('Social Newtwork Options','text-domain'); ?></h3>
    </th>

<?php
    foreach ( $social_networks as $network => $value ) 
    {
?>
        <div class="form-field location-metadata">
            
            <label for="<?php printf( esc_html__( '%s-metadata', 'text-domain' ), $network ); ?>">
                <?php printf( esc_html__( '%s URL', 'text-domain' ), esc_html__( $value ) ); ?>
            </label>
            
            <input type="text" name="<?php printf( esc_html__( 'department_%s_metadata', 'text-domain' ), esc_attr( $network ) ); ?>" id="<?php printf( esc_html__( '%s-metadata', 'text-domain' ), esc_attr( $network ) ); ?>" value="" class="social-metadata-field" />
        </div>
<?php
    } 
}
//-------------------------------------------------------------------------------------------
function sat_edit_department_social_metadata( $term )
{
    wp_nonce_field( basename( __FILE__ ), 'sat_department_social_nonce' );
    $social_networks= array(
                    'facebook' => 'FaceBook', 
                    'linkedin' => 'LinkedIn',
                    'github' => 'GitHub'
                	);


?>
<!-- //passing default WP attributes to table head-->
    <th scope="row" valign="top" colspan="2">
        <!-- //escaping, sanitizing and echoing string-->
        <h3><?php esc_html_e('Social Newtwork Options','text-domain'); ?></h3>
    </th>
    
    <?php
    foreach ( $social_networks as $network => $value ) 
    {
        // creating term-key for social network
        $term_key = 'department_'.$network.'_metadata';
		/*
		get_term_meta( int $term_id, string $key = '', bool $single = false )

		$term_id (int) (Required) Term ID.

		$key (string) (Optional) The meta key to retrieve. If no key is provided, fetches all metadata for the term. Default value: ''

		$single (bool) (Optional) Whether to return a single value. If false, an array of all values matching the $term_id/$key pair will be returned. Default: false.

Default value: false
		*/


        $metadata = get_term_meta( $term ->term_id , $term_key, true);

         

?>
    <tr class = "form-field department-metadata" >
        <th scope="row"> 
            <label for="<?php printf( esc_html__( '%s-metadata', 'text-domain' ), $network ); ?>">
                <?php printf( esc_html__( '%s URL', 'text-domain' ), esc_html__( $value ) ); ?>
            </label>
        </th>   
           
        <td>
            <input type="text" name="<?php printf( esc_html__( 'department_%s_metadata', 'text-domain' ), esc_attr( $network ) ); ?>" id="<?php printf( esc_html__( '%s-metadata', 'text-domain' ), esc_attr( $network ) ); ?>" value="<?php echo( ! empty( $metadata ) ) ? esc_attr( $metadata) : ''; ?> "class= social-metadata-field" />
        </td>
    </tr>
<?php
    } 
}

//-----------------------------------------------------------------------------------------------

//when working with terms $term_id is a available global variable-object to call
function save_department_social_metadata( $term_id )
{
    
    //to check if nonce-field is set.
    if( !isset( $_POST[ 'sat_department_social_nonce' ] ) )
    {
       return;
    }

    //to verify nonce is valid(associated to this plugin)
    if( ! wp_verify_nonce($_POST['sat_department_social_nonce'], basename( __FILE__ )))
    {
        return;
    }

// declaring a local-variable to hold the array of social network names.
    $social_networks= array(
                    'facebook' => 'FaceBook', 
                    'linkedin' => 'LinkedIn',
                    'github' => 'GitHub'
                	);

// Looping through the array of social network names.
    foreach ( $social_networks as $network => $value ) 
    {
        // creating term-key for social network
        $term_key = $term_key = 'department_'.$network.'_metadata';
        
        // If input-value has been entered into field by user
        if( isset( $_POST[ $term_key ] ) )
        {
            /*
            update_term_meta( int $term_id, string $meta_key, mixed $meta_value, mixed $prev_value = '' )

            Use the $prev_value parameter to differentiate between meta fields with the same key and term ID.  If the meta field for the term does not exist, it will be added.

            $term_id (int) (Required) Term ID.

            $meta_key (string) (Required) Metadata key.

            $meta_value (mixed) (Required) Metadata value.

            $prev_value (mixed) (Optional) Previous value to check before removing. Default value: ''

            #Return (int|WP_Error|bool) Meta ID if the key didn't previously exist. True on successful update. WP_Error when term_id is ambiguous between taxonomies. False on failure.
            */
            //saving input-value to the db, to the associated post.
            update_term_meta( $term_id, esc_attr( $term_key ), $_POST[ $term_key ]  );
        }
    }
}

//(dynamic-hook to add custom fields to the 'Add Department' UI, function called)
   add_action( 'department_add_form_fields' , 'sat_add_department_social_metadata' );

   //(dynamic-hook to add custom fields to the 'Edit Department' UI, function called)
   add_action( 'department_edit_form_fields' , 'sat_edit_department_social_metadata' );

    //(dynamic-hook for department taxonomy, function called)
   add_action('create_department', 'save_department_social_metadata');

   //(dynamic-hook for department taxonomy, function called)
   add_action('edit_department', 'save_department_social_metadata');