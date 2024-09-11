<?php get_header(); ?>

<h1>Lookbooks skapade av <?php the_author(); ?></h1>

<?php
// Hämta lookbooks skapade av denna författare
$args = array(
    'post_type' => 'lookbook',
    'author' => get_the_author_meta('ID'),
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
    echo '<p>Inga lookbooks hittades.</p>';
endif;

wp_reset_postdata();
?>

<?php get_footer(); ?>
