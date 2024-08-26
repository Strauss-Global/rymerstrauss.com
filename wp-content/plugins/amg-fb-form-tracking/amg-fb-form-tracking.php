<?php
/**
 * Plugin Name: AMG|FB Form Tracking via GA4
 * Description: Allows you to send custom tracking parameters to GA4 to mark an event for each form.
 * Version: 4.0
 * Author: Steven Janiak (Advantage Media Group | Forbes Books)
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

//Create a Settings Page
function custom_ga4_gravityforms_settings_page() {
    add_menu_page(
        'GA4 Form Tracking Settings',
        'GA4 Form Tracking',
        'manage_options',
        'ga4_form_tracking_settings',
        'custom_ga4_gravityforms_settings_page_callback'
    );
}
add_action('admin_menu', 'custom_ga4_gravityforms_settings_page');

//Create the Settings Page Callback
function custom_ga4_gravityforms_settings_page_callback() {
    ?>
    <div class="wrap">
        <h2>GA4 Form Tracking Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('ga4_form_tracking_options'); ?>
            <?php do_settings_sections('ga4_form_tracking_settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

//Register Settings
function custom_ga4_gravityforms_register_settings() {
    register_setting('ga4_form_tracking_options', 'ga4_form_tracking_value_id');
    register_setting('ga4_form_tracking_options', 'ga4_form_tracking_value1');
    register_setting('ga4_form_tracking_options', 'ga4_form_tracking_value2');
    register_setting('ga4_form_tracking_options', 'ga4_form_tracking_value3');
    register_setting('ga4_form_tracking_options', 'ga4_form_tracking_value4');
    register_setting('ga4_form_tracking_options', 'ga4_form_tracking_value5');
}
add_action('admin_init', 'custom_ga4_gravityforms_register_settings');

//Add Settings Fields:
function custom_ga4_gravityforms_settings_fields() {
    // GA4 ID
    add_settings_section(
        'ga4_form_tracking_section_id',
        'GA4 Setting',
        'custom_ga4_gravityforms_section_id_callback',
        'ga4_form_tracking_settings'
    );

    add_settings_field(
        'ga4_form_tracking_value_id',
        'GA4 ID',
        'custom_ga4_gravityforms_value_id_callback',
        'ga4_form_tracking_settings',
        'ga4_form_tracking_section_id'
    );

    // Section 1
    add_settings_section(
        'ga4_form_tracking_section1',
        'Assessment Form Settings',
        'custom_ga4_gravityforms_section1_callback',
        'ga4_form_tracking_settings'
    );

    add_settings_field(
        'ga4_form_tracking_value1',
        'Form ID',
        'custom_ga4_gravityforms_value1_callback',
        'ga4_form_tracking_settings',
        'ga4_form_tracking_section1'
    );

    // Section 2
    add_settings_section(
        'ga4_form_tracking_section2',
        'Contact Form Settings',
        'custom_ga4_gravityforms_section2_callback',
        'ga4_form_tracking_settings'
    );

    add_settings_field(
        'ga4_form_tracking_value2',
        'Form ID',
        'custom_ga4_gravityforms_value2_callback',
        'ga4_form_tracking_settings',
        'ga4_form_tracking_section2'
    );

    // Section 3
    add_settings_section(
        'ga4_form_tracking_section3',
        'Newsletter Subscribe Settings',
        'custom_ga4_gravityforms_section3_callback',
        'ga4_form_tracking_settings'
    );

    add_settings_field(
        'ga4_form_tracking_value3',
        'Form ID',
        'custom_ga4_gravityforms_value3_callback',
        'ga4_form_tracking_settings',
        'ga4_form_tracking_section3'
    );

    // Section 4
    add_settings_section(
        'ga4_form_tracking_section4',
        'Media Contact Form Settings',
        'custom_ga4_gravityforms_section4_callback',
        'ga4_form_tracking_settings'
    );

    add_settings_field(
        'ga4_form_tracking_value4',
        'Form ID',
        'custom_ga4_gravityforms_value4_callback',
        'ga4_form_tracking_settings',
        'ga4_form_tracking_section4'
    );

    // Section 5
    add_settings_section(
        'ga4_form_tracking_section5',
        'Free Chapter Download Form Settings',
        'custom_ga4_gravityforms_section5_callback',
        'ga4_form_tracking_settings'
    );

    add_settings_field(
        'ga4_form_tracking_value5',
        'Form ID',
        'custom_ga4_gravityforms_value5_callback',
        'ga4_form_tracking_settings',
        'ga4_form_tracking_section5'
    );
}
add_action('admin_init', 'custom_ga4_gravityforms_settings_fields');

//Settings Fields Callback Functions for GA4 ID
function custom_ga4_gravityforms_section_id_callback() {
    echo 'Enter the GA4 ID for the analytics property (ie. G-XXXXXXXXXX):';
}

function custom_ga4_gravityforms_value_id_callback() {
    $value_id = get_option('ga4_form_tracking_value_id');
    echo '<input type="text" id="ga4_form_tracking_value_id" name="ga4_form_tracking_value_id" value="' . esc_attr($value_id) . '" />';
}


//Settings Fields Callback Functions for Assessment Form
function custom_ga4_gravityforms_section1_callback() {
    echo 'Enter the form ID for the Assessment Form:';
}

function custom_ga4_gravityforms_value1_callback() {
    $value1 = get_option('ga4_form_tracking_value1');
    echo '<input type="text" id="ga4_form_tracking_value1" name="ga4_form_tracking_value1" value="' . esc_attr($value1) . '" />';
}

//Settings Fields Callback Functions for Contact Form
function custom_ga4_gravityforms_section2_callback() {
    echo 'Enter the form ID for the Contact Form:';
}

function custom_ga4_gravityforms_value2_callback() {
    $value2 = get_option('ga4_form_tracking_value2');
    echo '<input type="text" id="ga4_form_tracking_value2" name="ga4_form_tracking_value2" value="' . esc_attr($value2) . '" />';
}

//Settings Fields Callback Functions for Newsletter Form
function custom_ga4_gravityforms_section3_callback() {
    echo 'Enter the form ID for the Newsletter Form:';
}

function custom_ga4_gravityforms_value3_callback() {
    $value3 = get_option('ga4_form_tracking_value3');
    echo '<input type="text" id="ga4_form_tracking_value3" name="ga4_form_tracking_value3" value="' . esc_attr($value3) . '" />';
}

//Settings Fields Callback Functions for Media Form
function custom_ga4_gravityforms_section4_callback() {
    echo 'Enter the form ID for the Media Contact Form:';
}

function custom_ga4_gravityforms_value4_callback() {
    $value4 = get_option('ga4_form_tracking_value4');
    echo '<input type="text" id="ga4_form_tracking_value4" name="ga4_form_tracking_value4" value="' . esc_attr($value4) . '" />';
}

//Settings Fields Callback Functions for Download Form
function custom_ga4_gravityforms_section5_callback() {
    echo 'Enter the form ID for the Free Chapter Dodwnload Form:';
}

function custom_ga4_gravityforms_value5_callback() {
    $value5 = get_option('ga4_form_tracking_value5');
    echo '<input type="text" id="ga4_form_tracking_value5" name="ga4_form_tracking_value5" value="' . esc_attr($value5) . '" />';
}


// // // Add Gtag.js
// function enqueue_gtag_script() {
//     $ga4_id = get_option('ga4_form_tracking_value_id');
//     wp_enqueue_script('gtag', 'https://www.googletagmanager.com/gtag/js?id=' . $ga4_id, array('jquery'), null, false);
// }
// add_action('wp_enqueue_scripts', 'enqueue_gtag_script');

// Add action to send to GA4
add_action('gform_after_submission', 'custom_ga4_gravityforms_after_submission', 10, 2);

function custom_ga4_gravityforms_after_submission($entry, $form) {
    // Retrieve the stored information from your plugin's settings
    $ga4_id = get_option('ga4_form_tracking_value_id');
    $assessment_id = get_option('ga4_form_tracking_value1');
    $contact_id = get_option('ga4_form_tracking_value2');
    $newsletter_id = get_option('ga4_form_tracking_value3');
    $media_id = get_option('ga4_form_tracking_value4');
    $download_id = get_option('ga4_form_tracking_value5');

    // Extract the form ID
    $form_id = $form['id'];

    echo'
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=' . $ga4_id . '"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag("js", new Date());

    gtag("config", "' . $ga4_id . '");
    </script>
    ';

    // Your custom logic using the retrieved information
    if ( $form_id == $assessment_id ) {
        // Send the custom event to GA4 using gtag.js
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Tracking Assessment Form Submission');
            gtag('event', 'assessment_form_submission', {
                'event_category': 'Form Submission',
                'event_label': 'Gravity Forms'
            });
        });
        </script>
        <?php
    }
    if ( $form_id == $contact_id ) {
        // Send the custom event to GA4 using gtag.js
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Tracking Contact Form Submission');
            gtag('event', 'contact_form_submission', {
                'event_category': 'Form Submission',
                'event_label': 'Gravity Forms'
            });
        });
        </script>
        <?php
    }
    if ( $form_id == $newsletter_id ) {
        // Send the custom event to GA4 using gtag.js
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Tracking Newsletter Subscribe Submission');
            gtag('event', 'newsletter_subscribe_submission', {
                'event_category': 'Form Submission',
                'event_label': 'Gravity Forms'
            });
        });
        </script>
        <?php
    }
    if ( $form_id == $media_id ) {
        // Send the custom event to GA4 using gtag.js
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Tracking Media Contact Form Submission');
            gtag('event', 'media_contact_form_submission', {
                'event_category': 'Form Submission',
                'event_label': 'Gravity Forms'
            });
        });
        </script>
        <?php
    }
    if ( $form_id == $download_id ) {
        // Send the custom event to GA4 using gtag.js
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Tracking Free Chapter Download Submission');
            gtag('event', 'free_chapter_form_submission', {
                'event_category': 'Form Submission',
                'event_label': 'Gravity Forms'
            });
        });
        </script>
        <?php
    }
}