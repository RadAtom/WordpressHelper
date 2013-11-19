<?php

class RadAtomWordpressSecurity {

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
            'RadAtom Security', 
            'RadAtom Security', 
            'manage_options', 
            'radatom-wordpress-security', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'radatom_wordpress_security_option' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>RadAtom Wordpress Security Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'radatom_wordpress_security' );   
                do_settings_sections( 'radatom-wordpress-security' );
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
            'radatom_wordpress_security', // Option group
            'radatom_wordpress_security_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'radatom_wordpress_security_id', // ID
            'RadAtom Wordpress Security', // Title
            array( $this, 'print_section_info' ), // Callback
            'radatom-wordpress-security' // Page
        );  

        add_settings_field(
            'show_admin_bar', // ID
            'Show Wordpress Admin Bar', // Title 
            array( $this, 'show_admin_bar_callback' ), // Callback
            'radatom-wordpress-security', // Page
            'radatom_wordpress_security_id' // Section           
        );      

        add_settings_field(
            'show_generator_tag', 
            'Show Wordpress Generator Tag', 
            array( $this, 'show_generator_tag_callback' ), 
            'radatom-wordpress-security', 
            'radatom_wordpress_security_id'
        );      
    }

    /*
    * Check to make sure that each setting is added into the wordpress isntallation appropriately

    by default we want to show the admin bar
    by default we want to hide the WP-Generator tag
    */
    public function do_settings(){

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['show_admin_bar'] ) )
            $new_input['show_admin_bar'] = absint( $input['show_admin_bar'] );

        if( isset( $input['show_generator_tag'] ) )
            $new_input['show_generator_tag'] = sanitize_text_field( $input['show_generator_tag'] );

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
    public function show_admin_bar_callback()
    {
        if( isset($this->options['show_admin_bar'] ) ){
            printf(
            '<select  id="show_admin_bar" name="radatom_wordpress_security_option[show_admin_bar]" ><option %s value="1">Yes</option ><option %s value="0">No</option></select>', ($this->options['show_admin_bar']) ? 'selected="selected"' : '', ($this->options['show_admin_bar']) ? 'selected="selected"' : ''
            );
        }else{
            print '<select  id="show_admin_bar" name="radatom_wordpress_security_option[show_admin_bar]" ><option value="1">Yes</option ><option selected="selected" value="0">No</option></select>';
        }
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function show_generator_tag_callback()
    {   
        if( isset($this->options['show_generator_tag'] ) ){
            printf(
            '<select  id="show_generator_tag" name="radatom_wordpress_security_option[show_generator_tag]" ><option %s value="1">Yes</option ><option %s value="0">No</option></select>', ($this->options['show_generator_tag']) ? 'selected="selected"' : '', ($this->options['show_generator_tag']) ? 'selected="selected"' : ''
            );
        }else{
            print '<select  id="show_generator_tag" name="radatom_wordpress_security_option[show_generator_tag]" ><option value="1">Yes</option ><option selected="selected" value="0">No</option></select>';
        }
        
    }
}

if( is_admin() )
    $my_settings_page = new RadAtomWordpressSecurity();