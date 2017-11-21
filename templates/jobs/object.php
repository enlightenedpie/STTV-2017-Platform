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
            function job_title($title) {
                global $stjob;
                if (get_query_var('job-action') == 'edit'){
                    return 'Edit Job';
                }
                $afterpipe = strstr($title,'|');
                return ($afterpipe) ? $stjob->title.' '.$afterpipe : $stjob->title;
            }
            add_filter('the_title','job_title');
            add_filter('wpseo_title','job_title');
        }
    }
?>