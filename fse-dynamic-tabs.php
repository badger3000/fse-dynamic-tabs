<?php
/**
 * Plugin Name: FSE Dynamic Tabs
 * Description: Add dynamic tab functionality with editable block tabs
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
 * Add custom block category for our blocks
 */
function fse_dynamic_tabs_block_category($categories) {
    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'fse-dynamic-tabs',
                'title' => __('Dynamic Tabs', 'fse-dynamic-tabs'),
            ),
        )
    );
}
add_filter('block_categories_all', 'fse_dynamic_tabs_block_category', 10, 1);

/**
 * Register block pattern with editable tab buttons
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
    
    // Get content based on tab ID
    switch ($tab_id) {
        case 'products':
            $content = get_products_content();
            break;
        case 'services':
            $content = get_services_content();
            break;
        case 'about':
            $content = get_about_content();
            break;
        default:
            wp_send_json_error(array('message' => 'Invalid tab ID'));
            break;
    }
    
    // Send response
    wp_send_json_success(array('content' => $content));
    
    wp_die();
}

// Add AJAX actions for both logged-in and non-logged-in users
add_action('wp_ajax_get_tab_content', 'fse_ajax_get_tab_content');
add_action('wp_ajax_nopriv_get_tab_content', 'fse_ajax_get_tab_content');

/**
 * Get content for products tab
 */
function get_products_content() {
    // Generate pattern HTML (this could also come from a reusable block or post content)
    $content = '
    <!-- wp:columns -->
    <div class="wp-block-columns">
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:heading -->
            <h2>Featured Products</h2>
            <!-- /wp:heading -->
            
            <!-- wp:image {"align":"center","sizeSlug":"large","linkDestination":"none"} -->
            <figure class="wp-block-image aligncenter size-large">
                <img src="' . plugin_dir_url(__FILE__) . 'assets/images/placeholder-product.jpg" width="310px" height="auto" alt="Product" />
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
    <!-- /wp:columns -->';
    
    return $content;
}

/**
 * Get content for services tab
 */
function get_services_content() {
    // Generate pattern HTML
    $content = '
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
    <!-- /wp:columns -->';
    
    return $content;
}

/**
 * Get content for about tab
 */
function get_about_content() {
    // Generate pattern HTML
    $content = '
    <!-- wp:media-text {"mediaPosition":"left","mediaWidth":40,"align":"wide"} -->
    <div class="wp-block-media-text alignwide" style="grid-template-columns:40% auto">
        <figure class="wp-block-media-text__media">
             <img src="' . plugin_dir_url(__FILE__) . 'assets/images/placeholder-product.jpg" width="645px" height="auto" alt="Our Team" />
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
    <!-- /wp:media-text -->';
    
    return $content;
}