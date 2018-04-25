<?php

// Signup Form
add_action('stajax_nopriv_sttvajax_signup',array('SP_Sub','create'));
add_action('stajax_sttvajax_signup',array('SP_Sub','create'));

add_action('stajax_nopriv_sp_get_all_subs','sp_get_all_subs');
add_action('stajax_sp_get_all_subs','sp_get_all_subs');

add_action('stajax_nopriv_sttv_md5it','sttv_md5it');
add_action('stajax_sttv_md5it','sttv_md5it');

add_action('stajax_nopriv_mc_api_key','mc_api_key');
add_action('stajax_mc_api_key','mc_api_key');