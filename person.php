<?php

include(WP_PLUGIN_DIR . '/alfasoft/view.php');
global $wpdb;

add_action('wp_ajax_new_person', 'new_person');
function new_person()
{
    global $wpdb;
    $table = $wpdb->prefix . 'alfasoft_person';

    if (!empty($_POST)) {

        // Verify fields
        $name = strlen($_POST['name']) > 5 ? $_POST['name'] : '';
        $email = is_email($_POST['email']) ? $_POST['email'] : '';

        if (!empty($name) && !empty($email)) {

            $data = array('name' => $name, 'email' => $email);
            $format = array('%s', '%s');
            // Edit if has id
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                if ($wpdb->update($table, $data, ['id' => intval($_POST['id'])], $format) > 0) {
                    wp_send_json(['status' => 'sucess']);
                }
            } else {
                if ($wpdb->insert($table, $data, $format) > 0) {
                    wp_send_json(['status' => 'sucess']);
                }
            }
            wp_send_json(['status' => 'error']);
        }
    }

    if (isset($_GET['params']) && !empty($_GET['params'])) {
        $id = intval($_GET['params']);
        $person = $wpdb->get_row("SELECT * FROM $table WHERE id = $id");
    }

    render('new_person', $person);
}

add_action('wp_ajax_person', 'person');
function person()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'alfasoft_person';
    $people = $wpdb->get_results("SELECT * FROM $table_name WHERE deleted = 0");
    render('person', $people);
}


add_action('wp_ajax_delete_person', 'delete_person');
function delete_person()
{
    global $wpdb;

    $table = $wpdb->prefix . 'alfasoft_person';
    if (isset($_GET['params']) && !empty($_GET['params'])) {
        $data = ['deleted' => 1];
        $wpdb->update($table, $data, ['id' => intval($_GET['params'])], ['%d']);
    }
    person();
}

add_action('wp_ajax_new_contact', 'new_contact');
function new_contact()
{
    if (!empty($_POST)) {
        global $wpdb;
        $table = $wpdb->prefix . 'alfasoft_contact';


        $country_code = !empty($_POST['country_code']) ? $_POST['country_code'] : '';
        $number = !empty($_POST['number']) ? $_POST['number'] : '';
        $person_id = intval($_POST['person_id']) ? intval($_POST['person_id']) : '';

        $data = array('country_code' => $country_code, 'number' => $number, 'person_id' => $person_id);
        $format = array('%s', '%s', '%d');

        // Edit if has id
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            if ($wpdb->update($table, $data, ['id' => intval($_POST['id'])], $format) > 0) {
                wp_send_json(['status' => 'sucess']);
            }
        } else {
            if ($wpdb->insert($table, $data, $format) > 0) {
                wp_send_json(['status' => 'sucess']);
            }
        }
        wp_send_json(['status' => 'error']);
    }


    if (!isset($_GET['params']) || empty($_GET['params'])) {
        person();
    }

    $person_id = intval($_GET['params']);
    render('new_contact', [
        'person_id' => $person_id
    ]);
}

add_action('wp_ajax_edit_contact', 'edit_contact');
function edit_contact()
{
    global $wpdb;
    $table = $wpdb->prefix . 'alfasoft_contact';

    if (!empty($_POST)) {
        $country_code = !empty($_POST['country_code']) ? $_POST['country_code'] : '';
        $number = !empty($_POST['number']) ? $_POST['number'] : '';
        $person_id = intval($_POST['person_id']) ? intval($_POST['person_id']) : '';

        $data = array('country_code' => $country_code, 'number' => $number, 'person_id' => $person_id);
        $format = array('%s', '%s', '%d');

        // Edit if has id
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            if ($wpdb->update($table, $data, ['id' => intval($_POST['id'])], $format) > 0) {
                wp_send_json(['status' => 'sucess']);
            }
        }

        wp_send_json(['status' => 'error']);
    }


    if (!isset($_GET['params']) || empty($_GET['params'])) {
        person();
    }

    $contact_id = intval($_GET['params']);
    $contact = $wpdb->get_row("SELECT * FROM $table WHERE id = $contact_id");

    render('new_contact', [
        'contact' => $contact
    ]);
}

add_action('wp_ajax_show_person', 'show_person');
function show_person()
{
    $id = $_GET['id'];
    global $wpdb;
    $person_table = $wpdb->prefix . 'alfasoft_person';
    $contact_table = $wpdb->prefix . 'alfasoft_contact';

    if (isset($_GET['params']) && !empty($_GET['params'])) {
        $id = intval($_GET['params']);
        $person = $wpdb->get_row("SELECT * FROM $person_table WHERE id = $id");
        $person->contacts = $wpdb->get_results("SELECT * FROM $contact_table WHERE person_id = $id");
    }

    render('show_person', $person);
}
add_action('wp_ajax_delete_contact', 'delete_contact');

function delete_contact()
{
    global $wpdb;

    $contact_table = $wpdb->prefix . 'alfasoft_contact';
    $wpdb->delete($contact_table, ['id' => intval($_GET['params'])], ['%d']);
    person();
}
