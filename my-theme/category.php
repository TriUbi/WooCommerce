<?php get_header(); ?>

<h1>Lookbooks i kategorin: <?php single_term_title(); ?></h1>

<?php
// Hämta lookbooks för denna kategori
$args = array(
    'post_type' => 'lookbook',
    'tax_query' => array(
        array(
            'taxonomy' => 'lookbook_category', // Byt till den taxonomi du använder
            'field'    => 'slug',
            'terms'    => get_queried_object()->slug,
        ),
    ),
);

$lookbook_query = new WP_Query($args);

if ($lookbook_query->have_posts()) :
    echo '<ul>';
    while ($lookbook_query->have_posts()) : $lookbook_query->the_post(); ?>
        <li>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p>Skapad: <?php echo get_the_date(); ?></p>
        </li>
    <?php endwhile;
    echo '</ul>';
else :
    echo '<p>Inga lookbooks hittades i denna kategori.</p>';
endif;

wp_reset_postdata();
?>

<?php get_footer(); ?>
