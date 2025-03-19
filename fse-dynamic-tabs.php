<?php
/**
 * Plugin Name: FSE Dynamic Tabs
 * Description: Add dynamic tab functionality with pattern support
 * Version: 1.0.0
 * Author: Your Name
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue scripts and styles
 */
function fse_dynamic_tabs_enqueue_scripts() {    
    // Enqueue vanilla JS script
    wp_enqueue_script(
        'fse-dynamic-tabs-js',
        plugin_dir_url(__FILE__) . 'assets/js/dynamic-tabs.js',
        array(), // No dependencies (no jQuery)
        '1.0.0',
        true
    );
    
    // Pass data to JavaScript
    wp_localize_script(
        'fse-dynamic-tabs-js',
        'fseDynamicTabs',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('fse_dynamic_tabs_nonce'),
            'home_url' => home_url()
        )
    );
    
    // Enqueue CSS
    wp_enqueue_style(
        'fse-dynamic-tabs-css',
        plugin_dir_url(__FILE__) . 'assets/css/dynamic-tabs.css',
        array(),
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'fse_dynamic_tabs_enqueue_scripts');

/**
 * Admin scripts for settings page
 */
function fse_dynamic_tabs_admin_scripts($hook) {
    if ('settings_page_fse-dynamic-tabs-settings' !== $hook) {
        return;
    }
    
    wp_enqueue_style(
        'fse-dynamic-tabs-admin-css',
        plugin_dir_url(__FILE__) . 'assets/css/admin.css',
        array(),
        '1.0.0'
    );
}
add_action('admin_enqueue_scripts', 'fse_dynamic_tabs_admin_scripts');

/**
 * Register custom pattern category for our tab patterns 
 */
function fse_dynamic_tabs_register_pattern_category() {
    if (function_exists('register_block_pattern_category')) {
        register_block_pattern_category(
            'fse-dynamic-tabs-patterns',
            array('label' => __('Dynamic Tab Content', 'fse-dynamic-tabs'))
        );
    }
}
add_action('init', 'fse_dynamic_tabs_register_pattern_category');

/**
 * Register default tab content patterns
 */
function fse_dynamic_tabs_register_content_patterns() {
    if (function_exists('register_block_pattern')) {
        // Products Pattern
        register_block_pattern(
            'fse-dynamic-tabs/products-pattern',
            array(
                'title'       => __('Products Tab Content', 'fse-dynamic-tabs'),
                'description' => __('Content for the Products tab', 'fse-dynamic-tabs'),
                'categories'  => array('fse-dynamic-tabs-patterns'),
                'content'     => '
                <!-- wp:columns -->
                <div class="wp-block-columns">
                    <!-- wp:column -->
                    <div class="wp-block-column">
                        <!-- wp:heading -->
                        <h2>Featured Products</h2>
                        <!-- /wp:heading -->
                        
                        <!-- wp:image {"align":"center","sizeSlug":"large","linkDestination":"none"} -->
                        <figure class="wp-block-image aligncenter size-large">
                            <img src="' . plugin_dir_url(__FILE__) . 'assets/images/placeholder-product.jpg" alt="Product" />
                        </figure>
                        <!-- /wp:image -->
                        
                        <!-- wp:paragraph -->
                        <p>Check out our top-selling products this month. We have a variety of options for you to choose from.</p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:column -->
                    
                    <!-- wp:column -->
                    <div class="wp-block-column">
                        <!-- wp:heading -->
                        <h2>New Arrivals</h2>
                        <!-- /wp:heading -->
                        
                        <!-- wp:list -->
                        <ul>
                            <li>Premium Product A - Our most popular item</li>
                            <li>Exclusive Product B - Limited edition</li>
                            <li>New Collection C - Just released</li>
                        </ul>
                        <!-- /wp:list -->
                    </div>
                    <!-- /wp:column -->
                </div>
                <!-- /wp:columns -->',
            )
        );
        
        // Services Pattern
        register_block_pattern(
            'fse-dynamic-tabs/services-pattern',
            array(
                'title'       => __('Services Tab Content', 'fse-dynamic-tabs'),
                'description' => __('Content for the Services tab', 'fse-dynamic-tabs'),
                'categories'  => array('fse-dynamic-tabs-patterns'),
                'content'     => '
                <!-- wp:columns -->
                <div class="wp-block-columns">
                    <!-- wp:column -->
                    <div class="wp-block-column">
                        <!-- wp:heading -->
                        <h2>Our Services</h2>
                        <!-- /wp:heading -->
                        
                        <!-- wp:paragraph -->
                        <p>We provide top-quality services to meet your needs. Our team of experts is ready to help you succeed.</p>
                        <!-- /wp:paragraph -->
                        
                        <!-- wp:buttons -->
                        <div class="wp-block-buttons">
                            <!-- wp:button -->
                            <div class="wp-block-button"><a class="wp-block-button__link" href="#">Learn More</a></div>
                            <!-- /wp:button -->
                        </div>
                        <!-- /wp:buttons -->
                    </div>
                    <!-- /wp:column -->
                    
                    <!-- wp:column -->
                    <div class="wp-block-column">
                        <!-- wp:heading {"level":3} -->
                        <h3>Service Features</h3>
                        <!-- /wp:heading -->
                        
                        <!-- wp:list -->
                        <ul>
                            <li>Professional consultation with industry experts</li>
                            <li>Ongoing support and maintenance</li>
                            <li>Quality assurance and satisfaction guarantee</li>
                        </ul>
                        <!-- /wp:list -->
                    </div>
                    <!-- /wp:column -->
                </div>
                <!-- /wp:columns -->',
            )
        );
        
        // About Pattern
        register_block_pattern(
            'fse-dynamic-tabs/about-pattern',
            array(
                'title'       => __('About Tab Content', 'fse-dynamic-tabs'),
                'description' => __('Content for the About tab', 'fse-dynamic-tabs'),
                'categories'  => array('fse-dynamic-tabs-patterns'),
                'content'     => '
                <!-- wp:media-text {"mediaPosition":"left","mediaWidth":40,"align":"wide"} -->
                <div class="wp-block-media-text alignwide" style="grid-template-columns:40% auto">
                    <figure class="wp-block-media-text__media">
                        <img src="' . plugin_dir_url(__FILE__) . 'assets/images/team-placeholder.jpg" alt="Our Team" />
                    </figure>
                    <div class="wp-block-media-text__content">
                        <!-- wp:heading -->
                        <h2>About Our Company</h2>
                        <!-- /wp:heading -->
                        
                        <!-- wp:paragraph -->
                        <p>We\'ve been in business for over 20 years, providing excellent products and services to customers worldwide.</p>
                        <!-- /wp:paragraph -->
                        
                        <!-- wp:buttons -->
                        <div class="wp-block-buttons">
                            <!-- wp:button -->
                            <div class="wp-block-button"><a class="wp-block-button__link" href="#">Our Story</a></div>
                            <!-- /wp:button -->
                        </div>
                        <!-- /wp:buttons -->
                    </div>
                </div>
                <!-- /wp:media-text -->',
            )
        );
    }
}
add_action('init', 'fse_dynamic_tabs_register_content_patterns');

/**
 * Register settings
 */
function fse_dynamic_tabs_register_settings() {
    register_setting(
        'fse_dynamic_tabs_options',
        'fse_dynamic_tabs_options',
        array(
            'sanitize_callback' => 'fse_dynamic_tabs_sanitize_options',
            'default' => array()
        )
    );
}
add_action('admin_init', 'fse_dynamic_tabs_register_settings');

/**
 * Sanitize options
 */
function fse_dynamic_tabs_sanitize_options($options) {
    if (isset($options['tabs']) && is_array($options['tabs'])) {
        foreach ($options['tabs'] as $tab_id => $tab_data) {
            $options['tabs'][$tab_id]['content_type'] = sanitize_text_field($tab_data['content_type']);
            
            if ($tab_data['content_type'] === 'pattern') {
                $options['tabs'][$tab_id]['pattern_name'] = sanitize_text_field($tab_data['pattern_name']);
            } elseif ($tab_data['content_type'] === 'page') {
                $options['tabs'][$tab_id]['content_id'] = intval($tab_data['content_id']);
            }
        }
    }
    
    return $options;
}

/**
 * Add settings page
 */
function fse_dynamic_tabs_add_settings_page() {
    add_options_page(
        'FSE Dynamic Tabs Settings',
        'Dynamic Tabs',
        'manage_options',
        'fse-dynamic-tabs-settings',
        'fse_dynamic_tabs_settings_page'
    );
}
add_action('admin_menu', 'fse_dynamic_tabs_add_settings_page');

/**
 * Helper function to get all available patterns
 */
function fse_dynamic_tabs_get_patterns() {
    $patterns = array();
    
    // Method 1: Get patterns from WP_Block_Patterns_Registry
    if (class_exists('WP_Block_Patterns_Registry')) {
        $registry = WP_Block_Patterns_Registry::get_instance();
        $registered_patterns = $registry->get_all_registered();
        
        if (!empty($registered_patterns) && is_array($registered_patterns)) {
            foreach ($registered_patterns as $pattern) {
                if (isset($pattern['name']) && isset($pattern['title'])) {
                    $patterns[$pattern['name']] = $pattern['title'];
                }
            }
        }
    }
    
    // Method 2: Use get_block_patterns() function (available in WP 5.8+)
    if (function_exists('get_block_patterns')) {
        $wp_patterns = get_block_patterns();
        
        if (!empty($wp_patterns) && is_array($wp_patterns)) {
            foreach ($wp_patterns as $pattern) {
                if (isset($pattern['name']) && isset($pattern['title'])) {
                    $patterns[$pattern['name']] = $pattern['title'];
                }
            }
        }
    }
    
    // Method 3: Get user-created patterns from the database
    // User patterns are stored as 'wp_block' post type
    $user_patterns = get_posts(array(
        'post_type'      => 'wp_block',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ));
    
    if (!empty($user_patterns) && !is_wp_error($user_patterns)) {
        foreach ($user_patterns as $pattern) {
            // Add prefix to distinguish from core patterns
            $pattern_key = 'user-pattern/' . $pattern->post_name;
            $patterns[$pattern_key] = $pattern->post_title . ' (User Pattern)';
        }
    }
    
    // Debug: Log the detected patterns
    error_log('Detected patterns: ' . print_r($patterns, true));
    
    return $patterns;
}

/**
 * Settings page content
 */
function fse_dynamic_tabs_settings_page() {
    // Get saved options
    $options = get_option('fse_dynamic_tabs_options', array(
        'tabs' => array(
            'products' => array(
                'content_type' => 'default',
                'pattern_name' => '',  
                'content_id' => 0
            ),
            'services' => array(
                'content_type' => 'default',
                'pattern_name' => '',  
                'content_id' => 0
            ),
            'about' => array(
                'content_type' => 'default',
                'pattern_name' => '',  
                'content_id' => 0
            )
        )
    ));
    // Get available patterns
    $patterns = fse_dynamic_tabs_get_patterns();
    
    // Get available pages
    $pages = get_pages(array(
        'sort_column' => 'post_title',
        'sort_order' => 'ASC'
    ));
    
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p><?php _e('Configure content for each tab in the Dynamic Tabs block.', 'fse-dynamic-tabs'); ?></p>
        
        <form method="post" action="options.php">
            <?php
            settings_fields('fse_dynamic_tabs_options');
            do_settings_sections('fse-dynamic-tabs-settings');
            ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Products Tab Content', 'fse-dynamic-tabs'); ?></th>
                    <td>
                        <select name="fse_dynamic_tabs_options[tabs][products][content_type]" id="products-content-type">
                            <option value="default" <?php selected($options['tabs']['products']['content_type'], 'default'); ?>><?php _e('Default Content', 'fse-dynamic-tabs'); ?></option>
                            <option value="pattern" <?php selected($options['tabs']['products']['content_type'], 'pattern'); ?>><?php _e('Block Pattern', 'fse-dynamic-tabs'); ?></option>
                            <option value="page" <?php selected($options['tabs']['products']['content_type'], 'page'); ?>><?php _e('Page Content', 'fse-dynamic-tabs'); ?></option>
                        </select>
                        
                        <div class="content-selector products-pattern" <?php echo $options['tabs']['products']['content_type'] === 'pattern' ? '' : 'style="display:none;"'; ?>>
                            <select name="fse_dynamic_tabs_options[tabs][products][pattern_name]">
                                <option value=""><?php _e('-- Select a Pattern --', 'fse-dynamic-tabs'); ?></option>
                                <?php foreach ($patterns as $pattern_name => $pattern_title) : ?>
                                    <option value="<?php echo esc_attr($pattern_name); ?>" <?php selected(isset($options['tabs']['products']['pattern_name']) ? $options['tabs']['products']['pattern_name'] : '', $pattern_name); ?>>
                                        <?php echo esc_html($pattern_title); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description">
                                <?php _e('Need to create custom patterns? Use the Pattern Creator in the block editor.', 'fse-dynamic-tabs'); ?>
                            </p>
                        </div>
                        
                        <div class="content-selector products-page" <?php echo $options['tabs']['products']['content_type'] === 'page' ? '' : 'style="display:none;"'; ?>>
                            <select name="fse_dynamic_tabs_options[tabs][products][content_id]">
                                <option value="0"><?php _e('-- Select a Page --', 'fse-dynamic-tabs'); ?></option>
                                <?php foreach ($pages as $page) : ?>
                                    <option value="<?php echo esc_attr($page->ID); ?>" <?php selected($options['tabs']['products']['content_id'], $page->ID); ?>>
                                        <?php echo esc_html($page->post_title); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e('Services Tab Content', 'fse-dynamic-tabs'); ?></th>
                    <td>
                        <select name="fse_dynamic_tabs_options[tabs][services][content_type]" id="services-content-type">
                            <option value="default" <?php selected($options['tabs']['services']['content_type'], 'default'); ?>><?php _e('Default Content', 'fse-dynamic-tabs'); ?></option>
                            <option value="pattern" <?php selected($options['tabs']['services']['content_type'], 'pattern'); ?>><?php _e('Block Pattern', 'fse-dynamic-tabs'); ?></option>
                            <option value="page" <?php selected($options['tabs']['services']['content_type'], 'page'); ?>><?php _e('Page Content', 'fse-dynamic-tabs'); ?></option>
                        </select>
                        
                        <div class="content-selector services-pattern" <?php echo $options['tabs']['services']['content_type'] === 'pattern' ? '' : 'style="display:none;"'; ?>>
                            <select name="fse_dynamic_tabs_options[tabs][services][pattern_name]">
                                <option value=""><?php _e('-- Select a Pattern --', 'fse-dynamic-tabs'); ?></option>
                                <?php foreach ($patterns as $pattern_name => $pattern_title) : ?>
                                    <option value="<?php echo esc_attr($pattern_name); ?>" <?php selected($options['tabs']['services']['pattern_name'], $pattern_name); ?>>
                                        <?php echo esc_html($pattern_title); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="content-selector services-page" <?php echo $options['tabs']['services']['content_type'] === 'page' ? '' : 'style="display:none;"'; ?>>
                            <select name="fse_dynamic_tabs_options[tabs][services][content_id]">
                                <option value="0"><?php _e('-- Select a Page --', 'fse-dynamic-tabs'); ?></option>
                                <?php foreach ($pages as $page) : ?>
                                    <option value="<?php echo esc_attr($page->ID); ?>" <?php selected($options['tabs']['services']['content_id'], $page->ID); ?>>
                                        <?php echo esc_html($page->post_title); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e('About Tab Content', 'fse-dynamic-tabs'); ?></th>
                    <td>
                        <select name="fse_dynamic_tabs_options[tabs][about][content_type]" id="about-content-type">
                            <option value="default" <?php selected($options['tabs']['about']['content_type'], 'default'); ?>><?php _e('Default Content', 'fse-dynamic-tabs'); ?></option>
                            <option value="pattern" <?php selected($options['tabs']['about']['content_type'], 'pattern'); ?>><?php _e('Block Pattern', 'fse-dynamic-tabs'); ?></option>
                            <option value="page" <?php selected($options['tabs']['about']['content_type'], 'page'); ?>><?php _e('Page Content', 'fse-dynamic-tabs'); ?></option>
                        </select>
                        
                        <div class="content-selector about-pattern" <?php echo $options['tabs']['about']['content_type'] === 'pattern' ? '' : 'style="display:none;"'; ?>>
                            <select name="fse_dynamic_tabs_options[tabs][about][pattern_name]">
                                <option value=""><?php _e('-- Select a Pattern --', 'fse-dynamic-tabs'); ?></option>
                                <?php foreach ($patterns as $pattern_name => $pattern_title) : ?>
                                    <option value="<?php echo esc_attr($pattern_name); ?>" <?php selected($options['tabs']['about']['pattern_name'], $pattern_name); ?>>
                                        <?php echo esc_html($pattern_title); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="content-selector about-page" <?php echo $options['tabs']['about']['content_type'] === 'page' ? '' : 'style="display:none;"'; ?>>
                            <select name="fse_dynamic_tabs_options[tabs][about][content_id]">
                                <option value="0"><?php _e('-- Select a Page --', 'fse-dynamic-tabs'); ?></option>
                                <?php foreach ($pages as $page) : ?>
                                    <option value="<?php echo esc_attr($page->ID); ?>" <?php selected($options['tabs']['about']['content_id'], $page->ID); ?>>
                                        <?php echo esc_html($page->post_title); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                </tr>
            </table>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Show/hide content selectors based on content type selection
                    function toggleContentSelectors(tabId) {
                        const contentType = document.getElementById(tabId + '-content-type').value;
                        
                        document.querySelectorAll('.content-selector.' + tabId + '-default, .content-selector.' + tabId + '-pattern, .content-selector.' + tabId + '-page').forEach(function(el) {
                            el.style.display = 'none';
                        });
                        
                        const selector = document.querySelector('.content-selector.' + tabId + '-' + contentType);
                        if (selector) {
                            selector.style.display = 'block';
                        }
                    }
                    
                    // Add event listeners to content type selectors
                    ['products', 'services', 'about'].forEach(function(tabId) {
                        const selector = document.getElementById(tabId + '-content-type');
                        if (selector) {
                            selector.addEventListener('change', function() {
                                toggleContentSelectors(tabId);
                            });
                        }
                    });
                });
            </script>
            
            <?php submit_button(); ?>
        </form>
        
        <div class="postbox">
            <h2 class="hndle"><?php _e('How to Create Custom Patterns', 'fse-dynamic-tabs'); ?></h2>
            <div class="inside">
                <p><?php _e('To create your own custom patterns for use in the tabs:', 'fse-dynamic-tabs'); ?></p>
                <ol>
                    <li><?php _e('Go to the WordPress editor and create a new page or post', 'fse-dynamic-tabs'); ?></li>
                    <li><?php _e('Design your content using blocks', 'fse-dynamic-tabs'); ?></li>
                    <li><?php _e('Select all the blocks you want in your pattern', 'fse-dynamic-tabs'); ?></li>
                    <li><?php _e('Click the three-dot menu in the toolbar and select "Create pattern"', 'fse-dynamic-tabs'); ?></li>
                    <li><?php _e('Name your pattern and click "Create"', 'fse-dynamic-tabs'); ?></li>
                    <li><?php _e('Your pattern will now be available in the settings above', 'fse-dynamic-tabs'); ?></li>
                </ol>
                <p><?php _e('You can also create patterns through the Patterns section in your WordPress admin (visible in 6.0+).', 'fse-dynamic-tabs'); ?></p>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Register block pattern for tabs
 */
function fse_register_dynamic_tabs_pattern() {
    register_block_pattern(
        'fse-dynamic-tabs/tabs-pattern',
        array(
            'title'       => __('Dynamic Content Tabs', 'fse-dynamic-tabs'),
            'description' => __('Tabs that dynamically change page content without refresh', 'fse-dynamic-tabs'),
            'categories'  => array('featured'),
            'content'     => '
            <!-- wp:group {"className":"fse-dynamic-tabs-container"} -->
            <div class="wp-block-group fse-dynamic-tabs-container">
                <!-- wp:group {"className":"fse-tabs-nav"} -->
                <div class="wp-block-group fse-tabs-nav">
                    <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                    <div class="wp-block-buttons">
                        <!-- wp:button {"className":"fse-tab-button products-tab is-active","style":{"border":{"radius":"4px 4px 0 0"}},"fontSize":"medium"} -->
                        <div class="wp-block-button has-custom-font-size fse-tab-button products-tab is-active has-medium-font-size"><a class="wp-block-button__link" style="border-radius:4px 4px 0 0">Products</a></div>
                        <!-- /wp:button -->
                        
                        <!-- wp:button {"className":"fse-tab-button services-tab","style":{"border":{"radius":"4px 4px 0 0"}},"fontSize":"medium"} -->
                        <div class="wp-block-button has-custom-font-size fse-tab-button services-tab has-medium-font-size"><a class="wp-block-button__link" style="border-radius:4px 4px 0 0">Services</a></div>
                        <!-- /wp:button -->
                        
                        <!-- wp:button {"className":"fse-tab-button about-tab","style":{"border":{"radius":"4px 4px 0 0"}},"fontSize":"medium"} -->
                        <div class="wp-block-button has-custom-font-size fse-tab-button about-tab has-medium-font-size"><a class="wp-block-button__link" style="border-radius:4px 4px 0 0">About Us</a></div>
                        <!-- /wp:button -->
                    </div>
                    <!-- /wp:buttons -->
                </div>
                <!-- /wp:group -->
                
                <!-- wp:group {"className":"fse-tab-content"} -->
                <div class="wp-block-group fse-tab-content" id="fse-dynamic-content">
                    <!-- wp:paragraph -->
                    <p>Select a tab to view content.</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->',
        )
    );
}
add_action('init', 'fse_register_dynamic_tabs_pattern');

/**
 * Handle AJAX request for tab content
 */
function fse_ajax_get_tab_content() {
    // Verify nonce
    check_ajax_referer('fse_dynamic_tabs_nonce', 'nonce');
    
    // Get tab ID
    $tab_id = isset($_POST['tab_id']) ? sanitize_text_field($_POST['tab_id']) : '';
    
    // Get plugin options
    $options = get_option('fse_dynamic_tabs_options', array(
        'tabs' => array(
            'products' => array('content_type' => 'default', 'pattern_name' => '', 'content_id' => 0),
            'services' => array('content_type' => 'default', 'pattern_name' => '', 'content_id' => 0),
            'about' => array('content_type' => 'default', 'pattern_name' => '', 'content_id' => 0)
        )
    ));
    
    // Check if the tab exists in options
    if (!isset($options['tabs'][$tab_id])) {
        wp_send_json_error(array('message' => 'Invalid tab ID'));
        wp_die();
    }
    
    // Get tab settings
    $tab_settings = $options['tabs'][$tab_id];
    $content = '';
    
    // Get content based on content type
    switch ($tab_settings['content_type']) {
        case 'pattern':
            if (!empty($tab_settings['pattern_name'])) {
                // Check if it's a user pattern (has the 'user-pattern/' prefix)
                if (strpos($tab_settings['pattern_name'], 'user-pattern/') === 0) {
                    // Extract the post name from the pattern name
                    $post_name = str_replace('user-pattern/', '', $tab_settings['pattern_name']);
                    
                    // Get the pattern by post name
                    $pattern_post = get_page_by_path($post_name, OBJECT, 'wp_block');
                    
                    if ($pattern_post) {
                        $content = apply_filters('the_content', $pattern_post->post_content);
                    }
                } else {
                    // It's a core or theme pattern
                    if (function_exists('WP_Block_Patterns_Registry::get_instance')) {
                        $registry = WP_Block_Patterns_Registry::get_instance();
                        if ($registry->is_registered($tab_settings['pattern_name'])) {
                            $pattern = $registry->get_registered($tab_settings['pattern_name']);
                            $content = $pattern['content'];
                        }
                    } else if (function_exists('get_block_pattern')) {
                        $pattern = get_block_pattern($tab_settings['pattern_name']);
                        if ($pattern && isset($pattern['content'])) {
                            $content = $pattern['content'];
                        }
                    }
                }
            }
            break;
            
        case 'page':
            if ($tab_settings['content_id'] > 0) {
                $page = get_post($tab_settings['content_id']);
                if ($page && 'page' === $page->post_type) {
                    $content = apply_filters('the_content', $page->post_content);
                }
            }
            break;
            
        case 'default':
        default:
            // Fall back to default patterns
            switch ($tab_id) {
                case 'products':
                    $content = fse_dynamic_tabs_get_default_pattern('fse-dynamic-tabs/products-pattern');
                    break;
                case 'services':
                    $content = fse_dynamic_tabs_get_default_pattern('fse-dynamic-tabs/services-pattern');
                    break;
                case 'about':
                    $content = fse_dynamic_tabs_get_default_pattern('fse-dynamic-tabs/about-pattern');
                    break;
                default:
                    wp_send_json_error(array('message' => 'Invalid tab ID'));
                    break;
            }
            break;
    }
    
    // Check if we have content
    if (empty($content)) {
        // Fall back to default patterns if the selected content is empty
        switch ($tab_id) {
            case 'products':
                $content = fse_dynamic_tabs_get_default_pattern('fse-dynamic-tabs/products-pattern');
                break;
            case 'services':
                $content = fse_dynamic_tabs_get_default_pattern('fse-dynamic-tabs/services-pattern');
                break;
            case 'about':
                $content = fse_dynamic_tabs_get_default_pattern('fse-dynamic-tabs/about-pattern');
                break;
        }
    }
    
    // Send response
    wp_send_json_success(array('content' => $content));
    
    wp_die();
}

// Add AJAX actions for both logged-in and non-logged-in users
add_action('wp_ajax_get_tab_content', 'fse_ajax_get_tab_content');
add_action('wp_ajax_nopriv_get_tab_content', 'fse_ajax_get_tab_content');

/**
 * Get default pattern content
 */
function fse_dynamic_tabs_get_default_pattern($pattern_name) {
    if (function_exists('WP_Block_Patterns_Registry::get_instance')) {
        $registry = WP_Block_Patterns_Registry::get_instance();
        if ($registry->is_registered($pattern_name)) {
            $pattern = $registry->get_registered($pattern_name);
            return $pattern['content'];
        }
    }
    
    // Fallback content if pattern registry is not available
    switch ($pattern_name) {
        case 'fse-dynamic-tabs/products-pattern':
            return '
            <!-- wp:columns -->
            <div class="wp-block-columns">
                <!-- wp:column -->
                <div class="wp-block-column">
                    <!-- wp:heading -->
                    <h2>Featured Products</h2>
                    <!-- /wp:heading -->
                    
                    <!-- wp:paragraph -->
                    <p>Check out our top-selling products this month.</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:column -->
                
                <!-- wp:column -->
                <div class="wp-block-column">
                    <!-- wp:heading -->
                    <h2>New Arrivals</h2>
                    <!-- /wp:heading -->
                    
                    <!-- wp:list -->
                    <ul>
                        <li>Product A</li>
                        <li>Product B</li>
                        <li>Product C</li>
                    </ul>
                    <!-- /wp:list -->
                </div>
                <!-- /wp:column -->
            </div>
            <!-- /wp:columns -->';
            
        case 'fse-dynamic-tabs/services-pattern':
            return '
            <!-- wp:columns -->
            <div class="wp-block-columns">
                <!-- wp:column -->
                <div class="wp-block-column">
                    <!-- wp:heading -->
                    <h2>Our Services</h2>
                    <!-- /wp:heading -->
                    
                    <!-- wp:paragraph -->
                    <p>We provide top-quality services to meet your needs.</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:column -->
            </div>
            <!-- /wp:columns -->';
            
        case 'fse-dynamic-tabs/about-pattern':
            return '
            <!-- wp:heading -->
            <h2>About Our Company</h2>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph -->
            <p>We\'ve been in business for over 20 years, providing excellent products and services.</p>
            <!-- /wp:paragraph -->';
            
        default:
            return '<p>No content available.</p>';
    }
}