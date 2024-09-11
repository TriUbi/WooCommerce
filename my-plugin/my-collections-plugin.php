<?php
/*
Plugin Name: My Collections Plugin
Description: A plugin to manage Lookbooks for WooCommerce.
Version: 1.0
Author: Trinidad Ubilla
*/

// CPT för lookbook
function create_lookbook_post_type() {
    register_post_type('lookbook',
        array(
            'labels'      => array(
                'name'          => __('Lookbooks'),
                'singular_name' => __('Outfit'),
            ),
            'public'      => true,
            'has_archive' => true,
            'rewrite'     => array('slug' => 'lookbook'),
            'supports'    => array('title', 'editor', 'thumbnail'),
            'show_in_rest' => true,
        )
    );
}
add_action('init', 'create_lookbook_post_type');

// Hantera formulärdata och skapa lookbook
function handle_lookbook_submission() {
    if (isset($_POST['submit_lookbook'])) {
        // Sanitize form input
        $lookbook_title = sanitize_text_field($_POST['lookbook_title']);
        $lookbook_content = sanitize_textarea_field($_POST['lookbook_content']);
        $lookbook_products = isset($_POST['lookbook_products']) ? array_map('intval', $_POST['lookbook_products']) : [];

        // Skapa en ny lookbook (custom post type)
        $lookbook_id = wp_insert_post(array(
            'post_title'   => $lookbook_title,
            'post_content' => $lookbook_content,
            'post_status'  => 'publish',
            'post_type'    => 'lookbook',
        ));

        // Om lookbooken skapades, spara de valda produkterna som post_meta
        if ($lookbook_id && !is_wp_error($lookbook_id)) {
            update_post_meta($lookbook_id, 'lookbook_products', $lookbook_products);

         // Omdirigera till arkivsidan för alla Lookbooks (All Looks)
         $lookbook_archive_url = get_post_type_archive_link('lookbook'); // Hämta URL för arkivsidan
         wp_redirect($lookbook_archive_url);  // Omdirigera användaren till arkivsidan för Lookbooks
         exit;
        }
    }
}
add_action('template_redirect', 'handle_lookbook_submission');



function add_lookbook_metabox() {
    add_meta_box(
        'lookbook_products',
        'Plagg i denna Outfit',
        'display_lookbook_products_metabox',
        'lookbook',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_lookbook_metabox');

function display_lookbook_products_metabox($post) {
    $products = wc_get_products(array('limit' => -1));
    $selected_products = get_post_meta($post->ID, 'lookbook_products', true);

    echo '<label for="lookbook_products">Välj plagg för denna outfit:</label>';
    echo '<select multiple id="lookbook_products" name="lookbook_products[]">';

    foreach ($products as $product) {
        $selected = (is_array($selected_products) && in_array($product->get_id(), $selected_products)) ? 'selected' : '';
        echo '<option value="' . $product->get_id() . '" ' . $selected . '>' . $product->get_name() . '</option>';
    }

    echo '</select>';
}

function save_lookbook_products($post_id) {
    if (array_key_exists('lookbook_products', $_POST)) {
        update_post_meta($post_id, 'lookbook_products', $_POST['lookbook_products']);
    }
}
add_action('save_post', 'save_lookbook_products');


function add_lookbook_to_cart() {
    if ( isset($_GET['add_lookbook_to_cart']) ) {
        $lookbook_id = intval($_GET['add_lookbook_to_cart']);
        $product_ids = get_post_meta($lookbook_id, 'lookbook_products', true);

        if (!empty($product_ids)) {
            foreach ($product_ids as $product_id) {
                WC()->cart->add_to_cart($product_id);
            }
            wp_safe_redirect(wc_get_cart_url());
            exit;
        }
    }
}
add_action('wp', 'add_lookbook_to_cart');

/////FORM!!!
// visa formuläret på frontend

function lookbook_frontend_form() {
    if (!is_user_logged_in()) {
        return '<p>Du måste vara inloggad för att skapa en lookbook.</p>';
    }

    ob_start(); 

    ?>
    <form action="" method="post">
        <label for="lookbook_title">Titel för Lookbook</label> <br>
        <input type="text" name="lookbook_title" id="lookbook_title" required>

        <br>

        <label for="lookbook_content">Beskrivning</label>
        <textarea name="lookbook_content" id="lookbook_content" required></textarea>

        <label for="lookbook_products">Välj produkter</label>
        <div id="lookbook_products">
            <?php
            
            $products = wc_get_products(array('limit' => -1));
            foreach ($products as $product) {
                
                $product_image_url = wp_get_attachment_image_src($product->get_image_id(), 'thumbnail')[0];
                $product_name = $product->get_name();

               
                echo '<div class="product-checkbox">';
                echo '<input type="checkbox" name="lookbook_products[]" value="' . $product->get_id() . '">';
                echo '<label>';
                echo '<img src="' . esc_url($product_image_url) . '" alt="' . esc_attr($product_name) . '" style="width: 50px; height: 50px; vertical-align: middle;"> ';
                echo esc_html($product_name);
                echo '</label>';
                echo '</div>';
            }
            ?>
        </div>

        <input type="submit" name="submit_lookbook" value="Skapa Lookbook">
    </form>

    <?php

    return ob_get_clean(); 
}
add_shortcode('create_lookbook_form', 'lookbook_frontend_form');


// Registrera en taxonomi för Lookbooks
function create_lookbook_taxonomy() {
    register_taxonomy(
        'lookbook_category', // Slug för taxonomin
        'lookbook', // Custom post type som taxonomin är kopplad till
        array(
            'label' => __( 'Lookbook Categories' ),
            'rewrite' => array( 'slug' => 'lookbook-category' ),
            'hierarchical' => true, // Om kategorierna ska vara hierarkiska som vanliga kategorier
            'show_in_rest' => true, // För att använda taxonomin med Gutenberg
        )
    );
}
add_action( 'init', 'create_lookbook_taxonomy' );
