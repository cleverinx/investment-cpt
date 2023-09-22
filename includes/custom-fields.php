<?php
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
function render_investment_text_meta_box($post) {
 $text_values = get_post_meta($post->ID, 'investment_text', true);
 // if $text_values is not an array, set it to an array with an empty string for the first item

 if (!is_array($text_values)) {
        $text_values = array('');
    }

    ?>
    <div id="investment_text_fields">
        <?php
        // render existing text fields with values
        foreach ($text_values as $index => $text) {
            ?>
            <p>
                <label for="investment_text_<?php echo $index; ?>">Text:</label>
                <input type="text" name="investment_text[]" id="investment_text_<?php echo $index; ?>"
                       class="widefat" value="<?php echo esc_attr($text); ?>" />
            </p>
            <?php
        }
        ?>
    </div>
    <p>
        <input type="button" id="add_investment_text_field" class="button" value="Add Text Field" />
    </p>

    <script>
        jQuery(document).ready(function ($) {
            $('#add_investment_text_field').click(function () {
                // add new text field to #investment_text_fields with counter as index
                $('#investment_text_fields').append('<p><label for="investment_text_' + investment_text_counter
                    + '">Text:</label> <input type="text" name="investment_text[]" id="investment_text_' +
                    investment_text_counter + '" class="widefat" /></p>');
                investment_text_counter++;
            });

            let investment_text_counter = <?php echo count($text_values); ?>;
        });
    </script>
    <?php
}

// Save investment custom fields
function save_investment_custom_fields($post_id) {
    // Save investment logo
    if (isset($_POST['investment_logo'])) {
        update_post_meta($post_id, 'investment_logo', sanitize_text_field($_POST['investment_logo']));
    }

    // Save investment text
     if (isset($_POST['investment_text'])) {
        $text_values = array_map('sanitize_text_field', $_POST['investment_text']);
        // if array has empty values, remove them
        $text_values = array_filter($text_values);
        // save investment text as array of strings in post meta
        update_post_meta($post_id, 'investment_text', $text_values);
    }

}

add_action('save_post_investment', 'save_investment_custom_fields');