# FSE Dynamic Tabs

A lightweight WordPress plugin that enables dynamic, no-refresh content tabs for Full Site Editing (FSE) themes using native block editor components.

## Description

FSE Dynamic Tabs allows you to create tab-based navigation that dynamically updates page content without refreshing the browser or redirecting users. When visitors click a tab, the content area smoothly transitions to show new content without disrupting their browsing experience.

Built specifically for WordPress Full Site Editing (FSE) themes, this plugin uses the native block editor components, vanilla JavaScript (no jQuery), and is fully customizable through the WordPress block editor.

## Features

- **Dynamic Content Loading**: Update page content without page refreshes
- **Native Block Editor Integration**: Tabs are fully editable blocks
- **No jQuery Dependency**: Uses vanilla JavaScript for better performance
- **Full Site Editing Compatible**: Works seamlessly with FSE themes
- **Customizable Design**: Style tabs using WordPress's native controls
- **Smooth Transitions**: Content changes with elegant fade effects
- **URL Parameter Support**: Bookmarkable tabs with URL parameters
- **Mobile-Friendly**: Responsive design works on all devices
- **Lightweight**: Minimal impact on page load times

## Installation

### Automatic Installation

1. Log in to your WordPress dashboard
2. Navigate to Plugins → Add New
3. Search for "FSE Dynamic Tabs"
4. Click "Install Now" and then "Activate"

### Manual Installation

1. Download the plugin ZIP file
2. Log in to your WordPress dashboard
3. Navigate to Plugins → Add New → Upload Plugin
4. Click "Choose File" and select the ZIP file
5. Click "Install Now" and then "Activate"

## Usage

### Adding Tabs to a Page

1. Create or edit a page using the block editor
2. Click the "+" button to add a new block
3. Navigate to the "Patterns" tab
4. Find and select "Dynamic Content Tabs"
5. The tab pattern will be inserted into your page

### Customizing Tab Content

The plugin includes three default tab content sections: Products, Services, and About Us. To customize the content displayed for each tab:

1. Open the plugin file at `wp-content/plugins/fse-dynamic-tabs/fse-dynamic-tabs.php`
2. Locate the functions `get_products_content()`, `get_services_content()`, and `get_about_content()`
3. Edit the HTML content within these functions to display your desired content
4. Save the file

### Customizing Tab Appearance

You can customize the appearance of the tabs directly in the block editor:

1. Select a tab button
2. Use the block settings panel to change:
   - Button colors
   - Typography
   - Border styles
   - Padding and spacing
   - And more

## Adding Custom Tabs

To add additional tabs beyond the default three:

### Step 1: Add a new tab button to your page

1. Select the buttons container in the tabs
2. Add a new button block
3. Set these classes in the Advanced panel:
   - `fse-tab-button`
   - `your-tab-name-tab` (replace "your-tab-name" with your tab identifier)
4. Save the page

### Step 2: Add a content function to the plugin

1. Open `fse-dynamic-tabs.php`
2. Add a new content function:

```php
function get_your_tab_name_content() {
    // Generate HTML content for your tab
    $content = '
    <!-- wp:heading -->
    <h2>Your New Tab Content</h2>
    <!-- /wp:heading -->

    <!-- wp:paragraph -->
    <p>This is the content for your new custom tab.</p>
    <!-- /wp:paragraph -->';

    return $content;
}
```

3. Update the switch statement in the `fse_ajax_get_tab_content` function:

```php
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
    case 'your-tab-name':  // Add this case
        $content = get_your_tab_name_content();
        break;
    default:
        wp_send_json_error(array('message' => 'Invalid tab ID'));
        break;
}
```

### Step 3: Update the JavaScript detection

1. Open `assets/js/dynamic-tabs.js`
2. Find the `getTabIdFromClasses` function
3. Add your new tab class:

```javascript
// Check for specific tab classes
if (parentElement.classList.contains("products-tab")) return "products";
if (parentElement.classList.contains("services-tab")) return "services";
if (parentElement.classList.contains("about-tab")) return "about";
if (parentElement.classList.contains("your-tab-name-tab"))
  return "your-tab-name";
```

## Advanced Customization

### Using Reusable Blocks for Tab Content

Instead of hardcoding content in PHP functions, you can use WordPress reusable blocks:

1. Create reusable blocks for each tab's content
2. Note the block IDs
3. Modify the content functions to use those blocks:

```php
function get_products_content() {
    $block_id = 123; // Replace with your reusable block ID
    $block = get_post($block_id);
    return apply_filters('the_content', $block->post_content);
}
```

### Using Custom Post Types for Tab Content

You can also pull content from custom post types:

```php
function get_products_content() {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC'
    );

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        echo '<!-- wp:columns --><div class="wp-block-columns">';
        while ($query->have_posts()) {
            $query->the_post();
            echo '<!-- wp:column --><div class="wp-block-column">';
            echo '<!-- wp:heading --><h2>' . get_the_title() . '</h2><!-- /wp:heading -->';
            echo '<!-- wp:paragraph --><p>' . get_the_excerpt() . '</p><!-- /wp:paragraph -->';
            echo '</div><!-- /wp:column -->';
        }
        echo '</div><!-- /wp:columns -->';
    }
    wp_reset_postdata();

    return ob_get_clean();
}
```

## Troubleshooting

### Block Validation Errors

If you see "Block contains unexpected or invalid content" when adding the pattern:

1. Click "Attempt to recover" to fix the validation issues
2. If problems persist, use the direct HTML method:
   - Create a new HTML block
   - Paste the HTML code provided in the plugin's documentation
   - Convert to blocks

### Content Not Loading

If tab content doesn't load when clicked:

1. Check browser console for JavaScript errors
2. Verify that the tab class names in HTML match those in JavaScript
3. Ensure the AJAX nonce is properly set in the plugin initialization
4. Check that your server allows AJAX requests to admin-ajax.php

### Styling Issues

If tabs don't appear correctly:

1. Make sure your theme isn't overriding the plugin's CSS
2. Check that the plugin's CSS file is being loaded
3. Try adding custom CSS in the WordPress Customizer

## FAQ

### Does this work with any WordPress theme?

The plugin is designed specifically for Full Site Editing (FSE) themes that use the block editor. It may work with classic themes that support the block editor, but full compatibility is not guaranteed.

### Can I use this with WooCommerce products?

Yes! You can modify the `get_products_content()` function to display WooCommerce products by using the appropriate WooCommerce functions and template parts.

### Will this slow down my website?

The plugin is designed to be lightweight and only loads its scripts and styles on pages where the tabs are used. The dynamic content loading actually improves user experience by eliminating full page reloads.

### Can I have multiple tab sets on one page?

The current version supports one tab set per page. Future updates may add support for multiple tab instances.

### Does this work with page builders?

The plugin is designed for the native WordPress block editor. It may work with some page builders that fully support WordPress blocks, but compatibility is not guaranteed.

## Support and Contribution

For support requests, feature suggestions, or to report bugs:

- Create an issue on the [GitHub repository](https://github.com/yourusername/fse-dynamic-tabs)
- Visit the [WordPress.org support forum](https://wordpress.org/support/plugin/fse-dynamic-tabs/)

If you'd like to contribute to the development of this plugin:

1. Fork the repository
2. Create a feature branch
3. Submit a pull request

## License

FSE Dynamic Tabs is licensed under the GPLv2 or later:

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
