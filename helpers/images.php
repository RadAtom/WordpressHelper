<?php


class RadAtomWordpressImages {
	function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
        add_action( 'admin_init', array( $this, 'do_settings' ) );
   }

   /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'RadAtom Image Settings',
            'RadAtom Image Settings',
            'manage_options',
            'radatom-wordpress-images',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'radatom_wordpress_image_options' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>RadAtom Wordpress Posts Settings</h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'radatom_wordpress_images' );
                do_settings_sections( 'radatom-wordpress-images' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'radatom_wordpress_images', // Option group
            'radatom_wordpress_image_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        //Section for Post Media Manager Default Options
        add_settings_section(
            'radatom_wordpress_media_options_id', // ID
            'RadAtom Wordpress Post Media Options', // Title
            array( $this, 'print_section_info' ), // Callback
            'radatom-wordpress-images' // Page
        );

        // Default Image Link
        add_settings_field(
            'image_default_link', // ID
            'Setting the default link for images in posts media manager', // Title
            array( $this, 'image_default_link_callback' ), // Callback
            'radatom-wordpress-images', // Page
            'radatom_wordpress_media_options_id' // Section
        );
        // Default Image Size
        add_settings_field(
            'image_default_size', // ID
            'Setting the default image size in posts media manager', // Title
            array( $this, 'image_default_size_callback' ), // Callback
            'radatom-wordpress-images', // Page
            'radatom_wordpress_media_options_id' // Section
        );
        // Default Image Align
        add_settings_field(
            'image_default_align', // ID
            'Setting the default link for images in posts media manager', // Title
            array( $this, 'image_default_align_callback' ), // Callback
            'radatom-wordpress-images', // Page
            'radatom_wordpress_media_options_id' // Section
        );
    }

    /*
    * Check to make sure that each setting is added into the wordpress isntallation appropriately
    if the setting is not set then nothing will be done, but if the setting is set then it will add or remove actions from wordpress accordingly

    */
    public function do_settings(){
        $this->options = get_option( 'radatom_wordpress_image_options' );
        if($this->options){
            //at this point it didnt return false so we know that the settings exist, but we have to check what they actually are.

            // Since we know that it didn't return false. Call each function to get new option or reset to WordPress defaults.
            RadAtomWordpressImages::image_links_file($this->options['image_default_link']);
            RadAtomWordpressImages::image_default_size($this->options['image_default_size']);
            RadAtomWordpressImages::image_default_align($this->options['image_default_align']);
        }
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['image_default_link'] ) )
        {
            $new_input['image_default_link'] = absint( $input['image_default_link'] );
        }
        if( isset( $input['image_default_size'] ) )
        {
            $new_input['image_default_size'] = absint( $input['image_default_size'] );
        }

        if( isset( $input['image_default_align'] ) )
        {
            $new_input['image_default_align'] = absint( $input['image_default_align'] );
        }

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'These setting will help you with image options.';
    }

    /**
     * Default Image Link
     * Get the settings option array and print one of its values
     */
    public function image_default_link_callback()
    {
        if( isset($this->options['image_default_link'] ) ){
            printf(
            '<select  id="image_default_link" name="radatom_wordpress_image_options[image_default_link]" ><option %s value="file">Link to File</option><option %s value="post">Link to Attachement Page</option><option %s value="custom">Custom Link</option><option %s value="none">No Image Link</option></select>', ($this->options['image_default_link']) ? 'selected="selected"' : '', ($this->options['image_default_link']) ? 'selected="selected"' : '', ($this->options['image_default_link']) ? 'selected="selected"' : '', ($this->options['image_default_link']) ? 'selected="selected"' : ''
            );
        }
        else{
            print '<select  id="image_default_link" name="radatom_wordpress_image_option[image_default_link]" ><option value="file">Link to File</option ><option selected="selected" value="post">Link to Attachement Page</option><option value="custom">Custom Link</option><option value="none">No Image Link</option></select>';
        }
    }

    /**
     * Default Image Size
     * Get the settings option array and print one of its values
     */
    public function image_default_size_callback()
    {
        if( isset($this->options['image_default_size'] ) ){
            printf(
            '<select  id="image_default_size" name="radatom_wordpress_image_options[image_default_size]" ><option %s value="thumbnail">Thumbnail</option><option %s value="medium">Medium</option><option %s value="full">Full Size</option></select>', ($this->options['image_default_size']) ? 'selected="selected"' : '', ($this->options['image_default_size']) ? 'selected="selected"' : '', ($this->options['image_default_size']) ? 'selected="selected"' : ''
            );
        }
        else{
            print '<select  id="image_default_size" name="radatom_wordpress_image_option[image_default_size]" ><option value="thumbnail">Thumbnail</option></option ><option selected="selected" value="medium">Medium</option><option value="full">Full Size</option></select>';
        }
    }
     /**
     * Default Image Align
     * Get the settings option array and print one of its values
     */
    public function image_default_align_callback()
    {
        if( isset($this->options['image_default_align'] ) ){
            printf(
            '<select  id="image_default_align" name="radatom_wordpress_image_options[image_default_align]" ><option %s value="left">Left</option><option %s value="center">Center</option><option %s value="right">Right</option><option %s value="none">none</option></select>', ($this->options['image_default_align']) ? 'selected="selected"' : '', ($this->options['image_default_align']) ? 'selected="selected"' : '', ($this->options['image_default_align']) ? 'selected="selected"' : '', ($this->options['image_default_align']) ? 'selected="selected"' : ''
            );
        }
        else{
            print '<select id="image_default_align" name="radatom_wordpress_image_option[image_default_align]" ><option value="left">Left</option><option value="center">Center</option><option value="right">Right</option><option selected="selected" value="none">None</option></select>';
        }
    }

    /**
     * Default Image Link in Media Popup
     */
    public static function image_links_file($image_link) {
        //$image_settting = get_option( 'image_default_link_type' );
        // Image File url
        if ($image_link == 'file') {
            update_option('image_default_link_type', 'file');
        }
        // Attachment Page
        else if ($image_link == 'post') {
            update_option('image_default_link_type', 'post');
        }
        // Custom Url
        else if ($image_link == 'custom') {
            update_option('image_default_link_type', 'custom');
        }
        // No Link
        else if ($image_link == 'none') {
            update_option('image_default_link_type', 'none');
        }
        else {
            // Attachment Page
            update_option('image_default_link_type', 'post');
        }
    }
    /**
    * Default Image Size
    */
    // image_default_size
    // Default: NULL
    // Make it so that the options from the Media Popup are avaiable
    public static function image_default_size($image_size) {
        if ($image_size == 'thumbnail') {
            update_option('image_default_size', 'thumbnail');
        }
        else if ($image_size == 'medium') {
            update_option('image_default_size', 'medium');
        }
        else if ($image_size == 'full') {
            update_option('image_default_size', 'full');
        }
        else {
            update_option('image_default_size', 'medium');
        }
    }
    /*
    * Default Image Align
    */
    public static function image_default_align($image_align) {
        if ($image_align == 'left') {
            update_option('image_default_align', 'left');
        }
        else if ($image_align == 'center') {
            update_option('image_default_align', 'center');
        }
        else if ($image_align == 'right') {
            update_option('image_default_align', 'right');
        }
        else {
            update_option('image_default_align', 'none');
        }
    }


}