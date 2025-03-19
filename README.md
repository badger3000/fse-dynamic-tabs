# FSE Dynamic Tabs

A lightweight WordPress plugin that enables dynamic, no-refresh content tabs for Full Site Editing (FSE) themes using native block editor components and patterns.

![FSE Dynamic Tabs Demo](assets/images/dynamic-tabs-demo.gif)

## Description

FSE Dynamic Tabs allows you to create tab-based navigation that dynamically updates page content without refreshing the browser or redirecting users. When visitors click a tab, the content area smoothly transitions to show new content without disrupting their browsing experience.

Built specifically for WordPress Full Site Editing (FSE) themes, this plugin uses the native block editor components, vanilla JavaScript (no jQuery), and is fully customizable through the WordPress block editor and pattern system.

## Features

- **Dynamic Content Loading**: Update page content without page refreshes
- **Native Block Editor Integration**: Tabs are fully editable blocks
- **Pattern Support**: Use any WordPress block pattern for tab content
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

The plugin includes a dedicated settings page to choose content for each tab:

1. Go to Settings → Dynamic Tabs
2. For each tab, select your preferred content source:
   - **Default Content**: Uses the built-in pattern
   - **Block Pattern**: Select any registered pattern
   - **Page Content**: Use content from any WordPress page
3. Save your changes

### Customizing Tab Appearance

You can customize the appearance of the tabs directly in the block editor:

1. Select a tab button
2. Use the block settings panel to change:
   - Button colors
   - Typography
   - Border styles
   - Padding and spacing
   - And more

## Working with Patterns

### Using Existing Patterns

1. Go to Settings → Dynamic Tabs
2. Select "Block Pattern" for any tab
3. Choose from the dropdown of available patterns
4. Save your changes

### Creating Custom Patterns for Tabs

1. Go to the WordPress editor and create a new page or post
2. Design your content using blocks
3. Select all the blocks you want in your pattern
4. Click the three-dot menu in the toolbar and select "Create pattern"
5. Name your pattern and save it
6. Go to Settings → Dynamic Tabs to assign it to a tab

### Using the Dedicated Pattern Category

The plugin creates a special "Dynamic Tab Content" pattern category that contains:

- Default tab content patterns
- Your custom patterns for tabs (if you choose to save them there)

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
2. Add a new tab option to the settings array in `fse_dynamic_tabs_settings_page()` function:

```php
$options = get_option('fse_dynamic_tabs_options', array(
    'tabs' => array(
        'products' => array(...),
        'services' => array(...),
        'about' => array(...),
        'your-tab-name' => array(
            'content_type' => 'default',
            'pattern_name' => '',
            'content_id' => 0
        )
    )
));
```

3. Add a new section to the settings table:

```php
<tr>
    <th scope="row"><?php _e('Your Tab Content', 'fse-dynamic-tabs'); ?></th>
    <td>
        <select name="fse_dynamic_tabs_options[tabs][your-tab-name][content_type]" id="your-tab-name-content-type">
            <option value="default" <?php selected($options['tabs']['your-tab-name']['content_type'], 'default'); ?>><?php _e('Default Content', 'fse-dynamic-tabs'); ?></option>
            <option value="pattern" <?php selected($options['tabs']['your-tab-name']['content_type'], 'pattern'); ?>><?php _e('Block Pattern', 'fse-dynamic-tabs'); ?></option>
            <option value="page" <?php selected($options['tabs']['your-tab-name']['content_type'], 'page'); ?>><?php _e('Page Content', 'fse-dynamic-tabs'); ?></option>
        </select>

        <!-- Add pattern and page selectors like the other tabs -->
    </td>
</tr>
```

4. Update the JavaScript event listeners to include your tab:

```javascript
["products", "services", "about", "your-tab-name"].forEach(function (tabId) {
  const selector = document.getElementById(tabId + "-content-type");
  if (selector) {
    selector.addEventListener("change", function () {
      toggleContentSelectors(tabId);
    });
  }
});
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

### Creating a Default Pattern for New Tab

You can register a default pattern for your custom tab:

```php
register_block_pattern(
    'fse-dynamic-tabs/your-tab-name-pattern',
    array(
        'title'       => __('Your Tab Content', 'fse-dynamic-tabs'),
        'description' => __('Content for your custom tab', 'fse-dynamic-tabs'),
        'categories'  => array('fse-dynamic-tabs-patterns'),
        'content'     => '
        <!-- wp:heading -->
        <h2>Your Custom Tab Content</h2>
        <!-- /wp:heading -->

        <!-- wp:paragraph -->
        <p>This is the content for your custom tab.</p>
        <!-- /wp:paragraph -->',
    )
);
```

### Using Custom Post Types for Tab Content

You can modify the plugin to include data from custom post types:

```php
// First, add an option for custom post type in your tab settings
// Then modify the AJAX handler to process this content type

// For example, to add WooCommerce products:
case 'products_cpt':
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

    $content = ob_get_clean();
    break;
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

### Pattern Not Appearing in Dropdown

If your created pattern doesn't appear in the settings dropdown:

1. Refresh your admin page
2. Ensure the pattern was saved correctly
3. Check that you have sufficient permissions
4. Try creating the pattern from the dedicated Patterns admin screen (WordPress 6.0+)

## FAQ

### How do patterns differ from reusable blocks for tab content?

Patterns are more flexible than reusable blocks. When you edit a reusable block, it changes everywhere it's used. Patterns, however, are templates that you can insert and then customize independently. They're perfect for tab content because you can create a consistent starting point but customize each instance as needed.

### Can I create my own patterns for tab content?

Yes! You can create custom patterns in two ways:

1. In the block editor: select blocks, click the menu (three dots), and choose "Create pattern"
2. In WordPress 6.0+: go to Appearance → Editor → Patterns and create patterns from there

### Can I have different sets of tabs on different pages?

Yes. The tabs themselves are added as a pattern to your pages, so you can have different tab sets on different pages. Each set will use the content sources you've configured in the settings.

### Will this work with Elementor or other page builders?

The plugin is designed for the native WordPress block editor. It may work with some page builders that fully support WordPress blocks, but compatibility is not guaranteed.

### What happens if I edit a pattern that's used in a tab?

If you edit a pattern that's used as tab content, the changes will be reflected whenever that tab is viewed. This makes it easy to update your tab content in one place.

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
