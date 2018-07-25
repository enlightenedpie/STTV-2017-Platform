<?php

/* Template Name: logout page */
defined('ABSPATH') || exit;

wp_logout();
wp_redirect( site_url() );
exit;