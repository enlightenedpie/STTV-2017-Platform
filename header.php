<?php get_template_part('templates/head'); ?>
<?php sttv_nav_menu('sttv-nav','st-nav-list-mobile','sidenav'); ?>
<header class="row z-depth-3" id="stHeader">
    <nav class="main-nav" id="main-nav">
        <div id="stHeaderUpper" class="stHeaderInner main-nav row">
            <div id="stHeaderUpperInner" class="nav-wrapper">
                <div class="stHeaderBranding col s12 m6">
                    <a href="#" data-target="st-nav-list-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                    <a href="<?php echo site_url(); ?>">
                        <img src="<?php header_image(); ?>" alt="<?php echo get_bloginfo('name');?>" />
                    </a>
                    <span class="stHeaderSlogan"><?php echo get_bloginfo('description');?></span>
                </div>
                <div class="col s12 m6"></div>
            </div>
        </div>
        <div id="stHeaderLower" class="stHeaderInner row">
            <div class="nav-wrapper">
                <?php sttv_nav_menu('sttv-nav','st-nav-list','left hide-on-med-and-down'); ?>
            </div>
        </div>
    </nav>
</header>
<div id="not-header-wrapper" class="row">