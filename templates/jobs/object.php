<?php
global $wp_query;
    if (is_page('jobs')){
        global $stjob;
        $stjob = (new STTV_Jobs)->get_job(get_query_var('job-post'));

        if (null === $stjob){
            $stjob = 'Job not found';
            $wp_query->set_404();
            status_header(404);
        } else {
            function job_title($title,$post_id) {
                global $wp_query, $stjob;

                if ($post_id !==  $wp_query->queried_object_id) {
                    return $title;
                } 
                
                if (get_query_var('job-action') == 'edit'){
                    return 'Edit Job';
                }

                $afterpipe = strstr($title,'|');
                return ($afterpipe) ? $stjob->title.' '.$afterpipe : $stjob->title;
            }
            add_filter('the_title','job_title',11,2);
            add_filter('wpseo_title',function($title){
                global $stjob;
                $afterpipe = strstr($title,'|');
                return ($afterpipe) ? $stjob->title.' '.$afterpipe : $stjob->title;
            });
        }
    }