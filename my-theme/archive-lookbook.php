<?php get_header(); ?>

<h1>
    <?php 
    if (is_author()) {
        echo 'Lookbooks skapade av ' . get_the_author();
    } elseif (is_date()) {
        echo 'Lookbooks skapade på ' . get_the_date();
    } elseif (is_category()) {
        echo 'Lookbooks i kategorin: ' . single_cat_title('', false);
    } else {
        echo 'All Looks';
    }
    ?>
</h1>

<?php if (have_posts()) : ?>
    <ul>
    <?php while (have_posts()) : the_post(); ?>
        <li>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

           
            <p>Skapad: <?php echo get_the_date(); ?></p>

            <?php
            $product_ids = get_post_meta(get_the_ID(), 'lookbook_products', true);

            if (!empty($product_ids)) {
                echo '<h3>Plagg i denna outfit:</h3>';
                echo '<ul>';
                foreach ($product_ids as $product_id) {
                    $product = wc_get_product($product_id);
                    if ($product) {
                        $product_image = $product->get_image();  
                        $product_name = $product->get_name();    
                        $product_price = $product->get_price();  

                        echo '<li>';
                        echo $product_image; 
                        echo '<h4>' . esc_html($product_name) . '</h4>';
                        echo '<p>Pris: ' . wc_price($product_price) . '</p>';
                        echo '</li>';
                    }
                }
                echo '</ul>';

                $add_to_cart_url = add_query_arg('add_lookbook_to_cart', get_the_ID());
                echo '<a href="' . esc_url($add_to_cart_url) . '" class="button">Shop the Look</a>';
            } else {
                echo '<p>Inga plagg kopplade till denna lookbook.</p>';
            }
            ?>
        </li>
    <?php endwhile; ?>
    </ul>
<?php else : ?>
    <p>Inga looks hittades.</p>
<?php endif; ?>

<?php get_footer(); ?>

