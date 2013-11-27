<?php


class RadAtomWordpressPosts {
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
            'RadAtom Post Settings', 
            'RadAtom Post Settings', 
            'manage_options', 
            'radatom-wordpress-posts', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'radatom_wordpress_posts_option' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>RadAtom Wordpress Posts Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'radatom_wordpress_posts' );   
                do_settings_sections( 'radatom-wordpress-posts' );
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
            'radatom_wordpress_posts', // Option group
            'radatom_wordpress_posts_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'radatom_wordpress_post_id', // ID
            'RadAtom Wordpress Posts', // Title
            array( $this, 'print_section_info' ), // Callback
            'radatom-wordpress-posts' // Page
        );  

        add_settings_field(
            'enable_thumbnails', // ID
            'Enable Wordpress Post Thumbnails', // Title 
            array( $this, 'enable_thumbnails_callback' ), // Callback
            'radatom-wordpress-posts', // Page
            'radatom_wordpress_post_id' // Section           
        );    
    }

    /*
    * Check to make sure that each setting is added into the wordpress isntallation appropriately
    if the setting is not set then nothing will be done, but if the setting is set then it will add or remove actions from wordpress accordingly

    */
    public function do_settings(){
        $this->options = get_option( 'radatom_wordpress_posts_option' );
        if($this->options){
            //at this point it didnt return false so we know that the settings exist, but we have to check what they actually are.
            if($this->options['enable_thumbnails'] == 0){
                RadAtomWordpressPosts::remove_thumbnails();
            }else if($this->options['enable_thumbnails'] == 1){
                RadAtomWordpressPosts::enable_thumbnails();
            }
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
        if( isset( $input['enable_thumbnails'] ) )
            $new_input['enable_thumbnails'] = absint( $input['enable_thumbnails'] );


        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'These settings will help keep your website safe. The "show admin bar" setting below is wether or not to show the admin bar when you are viewing your website, more of a personal preferance than actual security. The "show generator tag" setting will remove the generator tag from your websites source code, this is a "security by obscurity" feature. ';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function enable_thumbnails_callback()
    {
        if( isset($this->options['enable_thumbnails'] ) ){
            printf(
            '<select  id="enable_thumbnails" name="radatom_wordpress_posts_option[enable_thumbnails]" ><option %s value="1">Yes</option ><option %s value="0">No</option></select>', ($this->options['enable_thumbnails']) ? 'selected="selected"' : '', ($this->options['enable_thumbnails']) ? 'selected="selected"' : ''
            );
        }else{
            print '<select  id="enable_thumbnails" name="radatom_wordpress_posts_option[enable_thumbnails]" ><option value="1">Yes</option ><option selected="selected" value="0">No</option></select>';
        }
    }


    public static function enable_thumbnails(){
    	if( is_admin() )
    		add_theme_support( 'post-thumbnails' ); 
    }

    public static function remove_thumbnails(){
    	if( is_admin() )
    		remove_theme_support( 'post-thumbnails' ); 
    }

    public static function get_posts( $postType = 'post'){
    	
    }


}