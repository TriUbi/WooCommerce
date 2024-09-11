
<?php

if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name'); ?> | <?php wp_title(); ?></title>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

    <div class="container">
        <header>
            <h1><?php bloginfo('name'); ?></h1>
            <p><?php bloginfo('description'); ?></p>
        </header>

        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                ?>
                <article>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div><?php the_content(); ?></div>
                </article>
                <?php
            endwhile;
        else :
            echo '<p>Inga inlägg hittades.</p>';
        endif;
        ?>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Alla rättigheter förbehållna.</p>
        </footer>
    </div>

    <?php wp_footer(); ?>
</body>
</html>
