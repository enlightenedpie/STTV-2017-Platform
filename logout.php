<?php

/* Template Name: logout page */
if (!defined('ABSPATH')) {die();}

wp_logout();
wp_redirect( site_url() );
exit;