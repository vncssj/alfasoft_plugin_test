<?php

/**
 * Plugin Name: Alfasoft Test
 * Plugin URI: http://www.blackdevelopment.com.br/
 * Description: Test.
 * Version: 0.1
 * Author: Vinícius Santos de Jesus
 * Author URI: http://www.blackdevelopment.com.br/
 **/

add_action('admin_menu', 'link_menu');

function link_menu()
{
    add_menu_page('Alfasoft Test', 'Alfasoft Test', 'manage_options', 'alfasoft-plugin', 'header_plugin');
}
