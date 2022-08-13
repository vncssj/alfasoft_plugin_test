<?php

/**
 * Plugin Name: Alfasoft Test
 * Plugin URI: http://www.blackdevelopment.com.br/
 * Description: Test.
 * Version: 0.1
 * Author: Vinícius Santos de Jesus
 * Author URI: http://www.blackdevelopment.com.br/
 **/

global $first_screen;
include(WP_PLUGIN_DIR . '/alfasoft/person.php');

// Create table when plugin actived
function create_tables()
{
    global $wpdb;
    $person_name = $wpdb->prefix . "alfasoft_person";
    $contact_name = $wpdb->prefix . "alfasoft_contact";
    $charset_collate = $wpdb->get_charset_collate();

    $person = "CREATE TABLE if not exists $person_name (
                id INT UNSIGNED auto_increment NOT NULL,
                name varchar(100) NOT NULL,
                email varchar(255) NOT NULL,
                deleted INT UNSIGNED DEFAULT 0 NOT NULL,
                CONSTRAINT alfasoft_person_PK PRIMARY KEY (id),
                CONSTRAINT alfasoft_person_UN UNIQUE KEY (email)
            ) $charset_collate;
            ";
    $contact = " CREATE TABLE if not exists $contact_name (
                id INT UNSIGNED auto_increment NOT NULL,
                country_code varchar(100) NOT NULL,
                number varchar(255) NOT NULL,
                person_id INT UNSIGNED NOT NULL,
                CONSTRAINT alfasoft_contact_PK PRIMARY KEY (id),
                CONSTRAINT alfasoft_contact_PK FOREIGN KEY (person_id) REFERENCES $person_name(id),
                CONSTRAINT alfasoft_contact_UN UNIQUE KEY (`number`,country_code)
            ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($person);
    dbDelta($contact);
}
register_activation_hook(__FILE__, 'create_tables');

// Set style file to Wordpress recognize
add_action('admin_enqueue_scripts', 'styles');
function styles()
{
    wp_register_style('alfasoft_plugin_style', plugins_url('alfasoft/style.css'));
    wp_enqueue_style('alfasoft_plugin_style');
}


// Add plugin at Menu
add_action('admin_menu', 'link_menu');
function link_menu()
{
    add_menu_page('Alfasoft Test', 'Alfasoft Test', 'manage_options', 'alfasoft-plugin', 'person');
}


add_action('admin_footer', 'ajax_calls');
function ajax_calls()
{ ?>
    <script type="text/javascript">
        function redirect(screen, params = null) {
            var data = {
                'action': screen,
            };

            if (params) {
                data.params = params
            }

            jQuery.get(ajaxurl, data, function(response) {
                jQuery("#content").empty()
                jQuery("#content").append(response)
            });
        }
    </script>
<?php }
