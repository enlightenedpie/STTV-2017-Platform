<?php get_template_part('templates/head'); ?>
<?php wp_nav_menu([
    'theme_location' => 'sttv-nav',
    'container' => false,
    'menu_id' => 'st-nav-list-mobile',
    'menu_class' => 'sidenav',
]); ?>
<header class="row" id="siteheader">
    <nav class="main-nav" id="main-nav">
        <div class="nav-wrapper col s12">
            <a href="<?php echo site_url(); ?>">
                <img src="<?php header_image(); ?>" alt="<?php echo bloginfo('name');?>" />
            </a>
            <a href="#" data-target="st-nav-list-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <?php wp_nav_menu([
                'theme_location' => 'sttv-nav',
                'container' => false,
                'menu_id' => 'st-nav-list',
                'menu_class' => 'right hide-on-med-and-down',
            ]); ?>
            <?php wp_nav_menu([
                'theme_location' => 'sttv-sub-nav-blog',
                'container' => false,
                'menu_id' => 'sttv-sub-nav-blog',
                'menu_class' => 'dropdown-content',
            ]); ?>
        </div>
    </nav>
</header>
<div id="not-header-wrapper" class="row">