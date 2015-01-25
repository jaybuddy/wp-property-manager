<?php

/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */

if (!class_exists('admin_folder_Redux_Framework_config')) {

    class admin_folder_Redux_Framework_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {
            //echo '<h1>The compiler hook has run!';
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'wppm'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'wppm'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns        = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'wppm'), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                <?php endif; ?>

                <h4><?php echo $this->theme->display('Name'); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'wppm'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'wppm'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'wppm') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'wppm'), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }




            // ACTUAL DECLARATION OF SECTIONS
            if (file_exists(dirname(__FILE__) . '/../DOCUMENTATION.md')) {
                $this->sections['plugin_docs'] = array(
                    'icon'      => 'el-icon-list-alt',
                    'title'     => __('Getting Started', 'wppm'),
                    'fields'    => array(
                        array(
                            'id'        => '17',
                            'type'      => 'raw',
                            'markdown'  => true,
                            'content'   => file_get_contents(dirname(__FILE__) . '/../DOCUMENTATION.md')
                        ),
                    ),
                );
            }


            $this->sections[] = array(
                'title'     => __('General Settings', 'wppm'),
                'desc'      => __('Put in a description here', 'wppm'),
                'icon'      => 'el-icon-home',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'        => 'opt-company-name',
                        'type'      => 'text',
                        'title'     => __('Company Name', 'wppm'),
                    ),
                    
                    array(
                        'id'        => 'opt-bootstrap',
                        'type'      => 'radio',
                        'title'     => __('How would you like us to load Twitter Bootstrap?', 'wppm'),
                        
                         //Must provide key => value pairs for radio options
                        'options'   => array(
                            '1' => 'Load it from the CDN (//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css/js)', 
                            '2' => 'Load it from within this plugin. (Will overwrite if you already use bootstrap in your theme.)', 
                            '3' => 'I already have it loaded in another theme/plugin. Load it from there!'
                        ),
                        'default'   => '2'
                    ),

                
                     
                    
                ),
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-cogs',
                'title'     => __('Listing Archive Page', 'wppm'),
                'fields'    => array(
                     array(
                        'id'       => 'section-la-opt-start',
                        'type'     => 'section',
                        'title'    => __( 'Archive Page Options', 'wppm' ),
                        'indent'   => false, // Indent all options below until the next 'section' option is set.
                     ),
                        array(
                            'id'        => 'enable-listing-age',
                            'type'      => 'checkbox',
                            'title'     => __('Enable Listing Age', 'wppm'),
                            'subtitle'      => __('This will display the age of the listing at the bottom of the listing.', 'wppm'),
                            'default'   => '1'// 1 = on | 0 = off
                        ),
                    array(
                        'id'       => 'section-la-opts-end',
                        'type'     => 'section',
                        'indent'   => false, // Indent all options below until the next 'section' option is set.
                    ),
                    array(
                        'id'       => 'section-hooks-start',
                        'type'     => 'section',
                        'title'    => __( 'Archive Page Hooks', 'wppm' ),
                        'indent'   => false, // Indent all options below until the next 'section' option is set.
                    ),
                    array(
                        'id'        => 'hook-archive-top',
                        'type'      => 'editor',
                        'args'      => array(),
                        'title'     => __('Top HTML', 'wppm'),
                        'subtitle'  => __('This is text (HTML) that is displayed above the list of listings. It is optional.', 'wppm'),
                        'default'   => '',
                    ),
                    array(
                        'id'        => 'hook-archive-sidebar-bottom',
                        'type'      => 'editor',
                        'args'      => array(),
                        'title'     => __('Sidebar Bottom HTML', 'wppm'),
                        'subtitle'  => __('This is text (HTML) that is displayed below the list of units in the right sidebar of the archive page. It is optional.', 'wppm'),
                        'default'   => '',
                    ),
                    array(
                        'id'        => 'hook-archive-bottom',
                        'type'      => 'editor',
                        'args'      => array(),
                        'title'     => __('Bottom HTML', 'wppm'),
                        'subtitle'  => __('This is text (HTML) that is displayed at the bottom of the archive page. It is optional.', 'wppm'),
                        'default'   => '',
                    ),
                    array(
                        'id'       => 'section-hooks-end',
                        'type'     => 'section',
                        'indent'   => false, // Indent all options below until the next 'section' option is set.
                    ),
                )
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-website',
                'title'     => __('Single Listing Page', 'wppm'),
                'fields'    => array(
                    array(
                        'id'       => 'section-single-opt-start',
                        'type'     => 'section',
                        'title'    => __( 'Single Page Options', 'wppm' ),
                        'indent'   => false, // Indent all options below until the next 'section' option is set.
                     ),
                        array(
                            'id'        => 'enable-google-map',
                            'type'      => 'checkbox',
                            'title'     => __('Globally Enable Google Maps', 'wppm'),
                            'subtitle'      => __('This makes Google Maps show on all listings, this can be overwritten on the individual listing page.', 'wppm'),
                            'default'   => '1'// 1 = on | 0 = off
                        ),
                        array(
                            'id'        => 'enable-sv',
                            'type'      => 'checkbox',
                            'title'     => __('Globally Enable Google Street View', 'wppm'),
                            'subtitle'      => __('This makes Google Street View show on all listings, this can be overwritten on the individual listing page.', 'wppm'),
                            'default'   => '1'// 1 = on | 0 = off
                        ),
                    array(
                        'id'       => 'section-single-opts-end',
                        'type'     => 'section',
                        'indent'   => false, // Indent all options below until the next 'section' option is set.
                    ),
                    array(
                        'id'       => 'section-hooks-start',
                        'type'     => 'section',
                        'title'    => __( 'Single Page Hooks', 'wppm' ),
                        'indent'   => false, // Indent all options below until the next 'section' option is set.
                    ),
                    array(
                        'id'        => 'hook-single-top',
                        'type'      => 'editor',
                        'args'      => array(),
                        'title'     => __('Top HTML', 'wppm'),
                        'subtitle'  => __('This is text (HTML) that is displayed above the listings. It is optional.', 'wppm'),
                        'default'   => '',
                    ),
                    array(
                        'id'        => 'hook-single-bottom',
                        'type'      => 'editor',
                        'args'      => array(),
                        'title'     => __('Bottom HTML', 'wppm'),
                        'subtitle'  => __('This is text (HTML) that is displayed at the bottom of the listing. It is optional.', 'wppm'),
                        'default'   => '',
                    ),
                    array(
                        'id'       => 'section-hooks-end',
                        'type'     => 'section',
                        'indent'   => false, // Indent all options below until the next 'section' option is set.
                    ),
                )
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-list-alt',
                'title'     => __('Document Settings', 'wppm'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-enable-app-download',
                        'type'      => 'checkbox',
                        'title'     => __('Enable Application Downloads', 'wppm'),
                        'subtitle'      => __('This will allow users to download an Application for Rent. (You can upload your application below)', 'wppm'),
                        'default'   => '1'// 1 = on | 0 = off
                    ),
                    array(
                        'id'        => 'opt-default-application',
                        'type'      => 'media',
                        'title'     => __('Fallback Application to Rent', 'wppm'),
                        'preview'   => false,
                        'mode'      => '',
                        'subtitle'      => __('Upload your fallback Application to Rent. This can be overwritten on individual property pages if you have different applications for different properties.', 'wppm')
                    ),
                ),
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-list-alt',
                'title'     => __('Email Settings', 'wppm'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-enable-email-a-friend',
                        'type'      => 'checkbox',
                        'title'     => __('Enable Email To A Friend', 'wppm'),
                        'subtitle'      => __('This will allow users to email properties to a friend.', 'wppm'),
                        'default'   => '1'// 1 = on | 0 = off
                    ),
                     array(
                        'id'        => 'opt-eaf-from',
                        'type'      => 'text',
                        'title'     => __('"From" Email Address', 'wppm'),
                        'subtitle'      => __('Enter a valid email address that the "Email A Friend" emails will be sent from.', 'wppm'),
                        'validate'  => 'email',
                        'msg'       => 'Please enter a valid email address.',
                        'default'   => get_bloginfo('admin_email'),
                    ),
                     array(
                        'id'        => 'opt-eaf-subject',
                        'type'      => 'text',
                        'title'     => __('Email Subject', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-eaf-template',
                        'type'      => 'editor',
                        'args'      => array('media_buttons' => false),
                        'title'     => __('Email A Friend Template', 'wppm'),
                        'subtitle'  => __('You can use the following fields from the Email A Friend Form: {{property-link}}, {{your-name}}, {{your-email}}, {{friends-name}}, {{friends-email}} ', 'wppm'),
                        'default'   => '',
                    ),
                ),
            );






            
            
            /*$this->sections[] = array(
                'icon'      => 'el-icon-check',
                'title'     => __('Radio/Checkbox Fields', 'wppm'),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'wppm'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-checkbox',
                        'type'      => 'checkbox',
                        'title'     => __('Checkbox Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                        'default'   => '1'// 1 = on | 0 = off
                    ),
                    array(
                        'id'        => 'opt-multi-check',
                        'type'      => 'checkbox',
                        'title'     => __('Multi Checkbox Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                        
                        //Must provide key => value pairs for multi checkbox options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ),
                        
                        //See how std has changed? you also don't need to specify opts that are 0.
                        'default'   => array(
                            '1' => '1', 
                            '2' => '0', 
                            '3' => '0'
                        )
                    ),
                    array(
                        'id'        => 'opt-checkbox-data',
                        'type'      => 'checkbox',
                        'title'     => __('Multi Checkbox Option (with menu data)', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                        'data'      => 'menu'
                    ),
                    array(
                        'id'        => 'opt-checkbox-sidebar',
                        'type'      => 'checkbox',
                        'title'     => __('Multi Checkbox Option (with sidebar data)', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                        'data'      => 'sidebars'
                    ),
                    array(
                        'id'        => 'opt-radio',
                        'type'      => 'radio',
                        'title'     => __('Radio Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                        
                         //Must provide key => value pairs for radio options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ),
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'opt-radio-data',
                        'type'      => 'radio',
                        'title'     => __('Multi Checkbox Option (with menu data)', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                        'data'      => 'menu'
                    ),
                    array(
                        'id'        => 'opt-image-select',
                        'type'      => 'image_select',
                        'title'     => __('Images Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                        
                        //Must provide key => value(array:title|img) pairs for radio options
                        'options'   => array(
                            '1' => array('title' => 'Opt 1', 'img' => 'images/align-none.png'),
                            '2' => array('title' => 'Opt 2', 'img' => 'images/align-left.png'),
                            '3' => array('title' => 'Opt 3', 'img' => 'images/align-center.png'),
                            '4' => array('title' => 'Opt 4', 'img' => 'images/align-right.png')
                        ), 
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'opt-image-select-layout',
                        'type'      => 'image_select',
                        'title'     => __('Images Option for Layout', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This uses some of the built in images, you can use them for layout options.', 'wppm'),
                        
                        //Must provide key => value(array:title|img) pairs for radio options
                        'options'   => array(
                            '1' => array('alt' => '1 Column',        'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
                            '2' => array('alt' => '2 Column Left',   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
                            '3' => array('alt' => '2 Column Right',  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
                            '4' => array('alt' => '3 Column Middle', 'img' => ReduxFramework::$_url . 'assets/img/3cm.png'),
                            '5' => array('alt' => '3 Column Left',   'img' => ReduxFramework::$_url . 'assets/img/3cl.png'),
                            '6' => array('alt' => '3 Column Right',  'img' => ReduxFramework::$_url . 'assets/img/3cr.png')
                        ), 
                        'default' => '2'
                    ),
                    array(
                        'id'        => 'opt-sortable',
                        'type'      => 'sortable',
                        'title'     => __('Sortable Text Option', 'wppm'),
                        'subtitle'  => __('Define and reorder these however you want.', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                        'options'   => array(
                            'si1' => 'Item 1',
                            'si2' => 'Item 2',
                            'si3' => 'Item 3',
                        )
                    ),
                    array(
                        'id'        => 'opt-check-sortable',
                        'type'      => 'sortable',
                        'mode'      => 'checkbox', // checkbox or text
                        'title'     => __('Sortable Text Option', 'wppm'),
                        'subtitle'  => __('Define and reorder these however you want.', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                        'options'   => array(
                            'si1' => 'Item 1',
                            'si2' => 'Item 2',
                            'si3' => 'Item 3',
                        )
                    ),
                )
            );*/
            
            /*$this->sections[] = array(
                'icon'      => 'el-icon-list-alt',
                'title'     => __('Select Fields', 'wppm'),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'wppm'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-select',
                        'type'      => 'select',
                        'title'     => __('Select Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                        
                        //Must provide key => value pairs for select options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ),
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'opt-multi-select',
                        'type'      => 'select',
                        'multi'     => true,
                        'title'     => __('Multi Select Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                        
                        //Must provide key => value pairs for radio options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ), 
                        'required'  => array('select', 'equals', array('1', '3')),
                        'default'   => array('2', '3')
                    ),
                    array(
                        'id'        => 'opt-select-image',
                        'type'      => 'select_image',
                        'title'     => __('Select Image', 'wppm'),
                        'subtitle'  => __('A preview of the selected image will appear underneath the select box.', 'wppm'),
                        'options'   => $sample_patterns,
                        // Alternatively
                        //'options'   => Array(
                        //                'img_name' => 'img_path'
                        //             )
                        'default' => 'tree_bark.png',
                    ),
                    array(
                        'id'    => 'opt-info',
                        'type'  => 'info',
                        'desc'  => __('You can easily add a variety of data from WordPress.', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-select-categories',
                        'type'      => 'select',
                        'data'      => 'categories',
                        'title'     => __('Categories Select Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-select-categories-multi',
                        'type'      => 'select',
                        'data'      => 'categories',
                        'multi'     => true,
                        'title'     => __('Categories Multi Select Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-select-pages',
                        'type'      => 'select',
                        'data'      => 'pages',
                        'title'     => __('Pages Select Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-multi-select-pages',
                        'type'      => 'select',
                        'data'      => 'pages',
                        'multi'     => true,
                        'title'     => __('Pages Multi Select Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-select-tags',
                        'type'      => 'select',
                        'data'      => 'tags',
                        'title'     => __('Tags Select Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-multi-select-tags',
                        'type'      => 'select',
                        'data'      => 'tags',
                        'multi'     => true,
                        'title'     => __('Tags Multi Select Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-select-menus',
                        'type'      => 'select',
                        'data'      => 'menus',
                        'title'     => __('Menus Select Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-multi-select-menus',
                        'type'      => 'select',
                        'data'      => 'menu',
                        'multi'     => true,
                        'title'     => __('Menus Multi Select Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-select-post-type',
                        'type'      => 'select',
                        'data'      => 'post_type',
                        'title'     => __('Post Type Select Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-multi-select-post-type',
                        'type'      => 'select',
                        'data'      => 'post_type',
                        'multi'     => true,
                        'title'     => __('Post Type Multi Select Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-multi-select-sortable',
                        'type'      => 'select',
                        'data'      => 'post_type',
                        'multi'     => true,
                        'sortable'  => true,
                        'title'     => __('Post Type Multi Select Option + Sortable', 'wppm'),
                        'subtitle'  => __('This field also has sortable enabled!', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-select-posts',
                        'type'      => 'select',
                        'data'      => 'post',
                        'title'     => __('Posts Select Option2', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-multi-select-posts',
                        'type'      => 'select',
                        'data'      => 'post',
                        'multi'     => true,
                        'title'     => __('Posts Multi Select Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-select-roles',
                        'type'      => 'select',
                        'data'      => 'roles',
                        'title'     => __('User Role Select Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-select-capabilities',
                        'type'      => 'select',
                        'data'      => 'capabilities',
                        'multi'     => true,
                        'title'     => __('Capabilities Select Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                    ),
                    array(
                        'id'        => 'opt-select-elusive',
                        'type'      => 'select',
                        'data'      => 'elusive-icons',
                        'title'     => __('Elusive Icons Select Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('Here\'s a list of all the elusive icons by name and icon.', 'wppm'),
                    ),
                )
            );*/

        
            
            // You can append a new section at any time.
            /*$this->sections[] = array(
                'icon'      => 'el-icon-eye-open',
                'title'     => __('Additional Fields', 'wppm'),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'wppm'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-datepicker',
                        'type'      => 'date',
                        'title'     => __('Date Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm')
                    ),
                    array(
                        'id'    => 'opt-divide',
                        'type'  => 'divide'
                    ),
                    array(
                        'id'        => 'opt-button-set',
                        'type'      => 'button_set',
                        'title'     => __('Button Set Option', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                        
                        //Must provide key => value pairs for radio options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ), 
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'opt-button-set-multi',
                        'type'      => 'button_set',
                        'title'     => __('Button Set, Multi Select', 'wppm'),
                        'subtitle'  => __('No validation can be done on this field type', 'wppm'),
                        'desc'      => __('This is the description field, again good for additional info.', 'wppm'),
                        'multi'     => true,
                        
                        //Must provide key => value pairs for radio options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ), 
                        'default'   => array('2', '3')
                    ),
                    array(
                        'id'        => 'opt-info-field',
                        'type'      => 'info',
                        'required'  => array('18', 'equals', array('1', '2')),
                        'desc'      => __('This is the info field, if you want to break sections up.', 'wppm')
                    ),
                    array(
                        'id'    => 'opt-info-warning',
                        'type'  => 'info',
                        'style' => 'warning',
                        'title' => __('This is a title.', 'wppm'),
                        'desc'  => __('This is an info field with the warning style applied and a header.', 'wppm')
                    ),
                    array(
                        'id'    => 'opt-info-success',
                        'type'  => 'info',
                        'style' => 'success',
                        'icon'  => 'el-icon-info-sign',
                        'title' => __('This is a title.', 'wppm'),
                        'desc'  => __('This is an info field with the success style applied, a header and an icon.', 'wppm')
                    ),
                    array(
                        'id'    => 'opt-info-critical',
                        'type'  => 'info',
                        'style' => 'critical',
                        'icon'  => 'el-icon-info-sign',
                        'title' => __('This is a title.', 'wppm'),
                        'desc'  => __('This is an info field with the critical style applied, a header and an icon.', 'wppm')
                    ),
                    array(
                        'id'        => 'opt-raw_info',
                        'type'      => 'info',
                        'required'  => array('18', 'equals', array('1', '2')),
                        'raw_html'  => true,
                        'desc'      => $sampleHTML,
                    ),
                    array(
                        'id'        => 'opt-info-normal',
                        'type'      => 'info',
                        'notice'    => true,
                        'title'     => __('This is a title.', 'wppm'),
                        'desc'      => __('This is an info notice field with the normal style applied, a header and an icon.', 'wppm')
                    ),
                    array(
                        'id'        => 'opt-notice-info',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'info',
                        'title'     => __('This is a title.', 'wppm'),
                        'desc'      => __('This is an info notice field with the info style applied, a header and an icon.', 'wppm')
                    ),
                    array(
                        'id'        => 'opt-notice-warning',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'warning',
                        'icon'      => 'el-icon-info-sign',
                        'title'     => __('This is a title.', 'wppm'),
                        'desc'      => __('This is an info notice field with the warning style applied, a header and an icon.', 'wppm')
                    ),
                    array(
                        'id'        => 'opt-notice-success',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'success',
                        'icon'      => 'el-icon-info-sign',
                        'title'     => __('This is a title.', 'wppm'),
                        'desc'      => __('This is an info notice field with the success style applied, a header and an icon.', 'wppm')
                    ),
                    array(
                        'id'        => 'opt-notice-critical',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'critical',
                        'icon'      => 'el-icon-info-sign',
                        'title'     => __('This is a title.', 'wppm'),
                        'desc'      => __('This is an notice field with the critical style applied, a header and an icon.', 'wppm')
                    ),
                    array(
                        'id'        => 'opt-custom-callback',
                        'type'      => 'callback',
                        'title'     => __('Custom Field Callback', 'wppm'),
                        'subtitle'  => __('This is a completely unique field type', 'wppm'),
                        'desc'      => __('This is created with a callback function, so anything goes in this field. Make sure to define the function though.', 'wppm'),
                        'callback'  => 'redux_my_custom_field'
                    ),
                )
            );*/

            /*$this->sections[] = array(
                'title'     => __('Import / Export', 'wppm'),
                'desc'      => __('Import and Export your Redux Framework settings from file, text or URL.', 'wppm'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your Redux options',
                        'full_width'    => false,
                    ),
                ),
            ); */                    
                    
            $this->sections[] = array(
                'type' => 'divide',
            );

            $theme_info  = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __('<strong>Plugin URL:</strong> ', 'wppm') . '<a href="http://www.wp-property-manager.com" target="_blank">WP-Property-Manager.com</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __('<strong>Author:</strong> ', 'wppm') . 'Jay Pedersen</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __('<strong>Version:</strong> ', 'wppm') .'0.8</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">WP Property Manger is a Wordpress plugin that allows you to beautifully display properties for rent.</p>';
            $theme_info .= '<p class="redux-framework-theme-data description heme-uri"><a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PXLBAMLFKX8AN">Donate to support further development of this plugin</a></p>';
            $theme_info .= '</div>';

            $this->sections[] = array(
                'icon'      => 'el-icon-info-sign',
                'title'     => __('Plugin Information', 'wppm'),
                'desc'      => __($theme_info, 'wppm'),
            );

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon'      => 'el-icon-book',
                    'title'     => __('Documentation', 'wppm'),
                    'content'   => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'wppm'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'wppm')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'wppm'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'wppm')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'wppm');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                'opt_name' => 'redux_options',
                'display_name' => 'WP Property Manager Settings',
                'page_slug' => '_wppm-settings',
                'page_title' => 'WP Property Manager Settings',
                'update_notice' => true,
                'intro_text' => '<p>intro text</p>',
                'footer_text' => '<p><a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PXLBAMLFKX8AN">Donate to support further development of this plugin</a></p>',
                'menu_type' => 'menu',
                'menu_title' => 'WPPM',
                'page_parent_post_type' => 'your_post_type',
                'default_show' => true,
                'default_mark' => '*',
                'hints' => 
                array(
                  'icon' => 'el-icon-question-sign',
                  'icon_position' => 'right',
                  'icon_size' => 'normal',
                  'tip_style' => 
                  array(
                    'color' => 'light',
                  ),
                  'tip_position' => 
                  array(
                    'my' => 'top left',
                    'at' => 'bottom right',
                  ),
                  'tip_effect' => 
                  array(
                    'show' => 
                    array(
                      'duration' => '500',
                      'event' => 'mouseover',
                    ),
                    'hide' => 
                    array(
                      'duration' => '500',
                      'event' => 'mouseleave unfocus',
                    ),
                  ),
                ),
                'output' => true,
                'output_tag' => true,
                'compiler' => true,
                'page_icon' => 'icon-themes',
                'page_permissions' => 'manage_options',
                'save_defaults' => true,
                'show_import_export' => true,
                'transient_time' => '3600',
                'network_sites' => true,
              );

            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
                'title' => 'Visit us on GitHub',
                'icon'  => 'el-icon-github'
                //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
            );

        }

    }
    
    global $reduxConfig;
    $reduxConfig = new admin_folder_Redux_Framework_config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('admin_folder_my_custom_field')):
    function admin_folder_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('admin_folder_validate_callback_function')):
    function admin_folder_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;
