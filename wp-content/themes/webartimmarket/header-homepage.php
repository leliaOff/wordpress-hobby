<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div class="wrapper homepage">
        <header class="screen">

            <div id="logo"></div>
            
            <div id="header-main">
                <h1>рукоделие и творчество</h1>
                    <hr />
                <h2>мишки Тедди и куклы ручной работы</h2>
                <?php get_search_form(); ?>
            </div>

            <nav id="top-menu">
                <div class="phone"><i class="fa fa-phone"></i>+7 (921) 944 73 98</div>
                <div class="email"><i class="fa fa-envelope"></i>info@hobbyshtuchki.ru</div>
                
                <?php if(is_user_logged_in()): ?>
                    <?php wp_nav_menu(['theme_location' => 'auth_header_top', 'container_class' => 'menu-container', 'link_before' => '<span>', 'link_after' => '</span>']) ?>
                <?php else: ?>
                    <?php wp_nav_menu(['theme_location' => 'header_top', 'container_class' => 'menu-container', 'link_before' => '<span>', 'link_after' => '</span>']) ?>
                <?php endif; ?>

            </nav>

            <nav id="header-menu">
                <?php wp_nav_menu(['theme_location' => 'header', 'container_class' => 'menu-container']) ?>
            </nav>

            <button id="header-arrow" onclick="scrollToElement('#homepage-content')"></button>
            <div id="sky-1"></div><div id="sky-2"></div><div id="sky-3"></div>

        </header>