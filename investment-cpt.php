<?php
/*
Plugin Name: Investment Custom Post Type
Description: Custom post type and taxonomy for managing investments.
Version: 1.0
*/

// Register Custom Post Type for Investments
function create_investment_post_type() {
    $labels = array(
        'name' => 'Investments',
        'singular_name' => 'Investment',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'investment'),
        'supports' => array('title', 'editor', 'custom-fields'),
    );

    register_post_type('investment', $args);
}

add_action('init', 'create_investment_post_type');

// Register Custom Taxonomy for Investments
function create_investment_taxonomy() {
    $labels = array(
        'name' => 'Investment Categories',
        'singular_name' => 'Investment Category',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'rewrite' => array('slug' => 'investment-category'),
    );

    register_taxonomy('investment_category', 'investment', $args);
}

add_action('init', 'create_investment_taxonomy');

// Add custom meta boxes for logo and repeatable text fields
function add_investment_custom_meta_boxes() {
    add_meta_box(
        'investment_logo_meta_box',
        'Investment Logo',
        'render_investment_logo_meta_box',
        'investment',
        'normal',
        'high'
    );

    add_meta_box(
        'investment_text_meta_box',
        'Investment Text',
        'render_investment_text_meta_box',
        'investment',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'add_investment_custom_meta_boxes');

// Render image upload and preview for investment logo
function render_investment_logo_meta_box($post) {

    $image_url = get_post_meta($post->ID, 'investment_logo', true);
    ?>
    <div>
        <label for="investment_logo">Upload Investment Logo:</label>
        <input type="text" name="investment_logo" id="investment_logo" class="widefat"
               value="<?php echo esc_url($image_url); ?>"/>
        <input type="button" id="upload_logo_button" class="button" value="Upload Logo"/>

        <div id="investment_logo_preview">
                <?php if ($image_url): ?>
                <?php
                $image_id = attachment_url_to_postid($image_url);
                echo '<img src="' . wp_get_attachment_image_src($image_id, 'thumbnail')[0] . '"\
                         class="investment-logo-preview" style="max-width:100px;" alt="Investment Image" />';
                ?>
                <?php endif; ?>
        </div>
    </div>

    <script>
        jQuery(document).ready(function ($) {

            // if #investment_logo changes, update preview
            $('#investment_logo').change(function () {
                const image_url = $(this).val();
            $("#investment_logo_preview").html('<img src="' + image_url + '" class="investment-logo-preview" ' +
                'style="max-width:100px;" alt="Investment Image" />');
            });

            $('#upload_logo_button').click(function () {
                const custom_uploader = wp.media({
                    title: 'Upload Investment Logo',
                    button: {
                        text: 'Use this logo'
                    },
                    multiple: false
                });

                custom_uploader.on('select', function () {
                    const attachment = custom_uploader.state().get('selection').first().toJSON();
                    // update #investment_logo url value
                    $('#investment_logo').val(attachment.url);
                    // update preview
                   $("#investment_logo_preview").html('<img src="' + attachment.url + '"' +
                       ' class="investment-logo-preview" style="max-width:100px;" alt="Investment Image" />');
                });

                custom_uploader.open();
            });
        });
    </script>
    <?php
}

// Render repeatable text fields for investment text
function render_investment_text_meta_box() {
    // output form fields
}

// Save investment custom fields
function save_investment_custom_fields($post_id) {
    // Save investment logo
    if (isset($_POST['investment_logo'])) {
        update_post_meta($post_id, 'investment_logo', sanitize_text_field($_POST['investment_logo']));
    }

    // Save investment text

}

add_action('save_post_investment', 'save_investment_custom_fields');