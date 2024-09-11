<?php get_header(); ?>

<h1><?php single_cat_title(); ?></h1>
<?php if (have_posts()) : ?>
    <ul>
    <?php while (have_posts()) : the_post(); ?>
        <li>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            
            <p>Skapad: <?php echo get_the_date(); ?></p>
        

            <?php
            $product_ids = get_post_meta(get_the_ID(), 'lookbook_products', true);
            if (!empty($product_ids)) {
                echo '<h3>Plagg i denna outfit:</h3><ul>';
                foreach ($product_ids as $product_id) {
                    $product = wc_get_product($product_id);
                    if ($product) {
                        echo '<li>' . $product->get_image() . ' ' . $product->get_name() . ' - ' . wc_price($product->get_price()) . '</li>';
                    }
                }
                echo '</ul>';
            }
            ?>
        </li>
    <?php endwhile; ?>
    </ul>
<?php else : ?>
    <p>Inga lookbooks hittades.</p>
<?php endif; ?>
<?php get_footer(); ?>
