<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * SupertutorTV job board.
 *
 * All operations, including custom routes, template redirection, and REST endpoints for the SupertutorTV job board.
 *
 * @class 		STTV_Jobs
 * @version		1.3.0
 * @package		STTV
 * @category	Class
 * @author		Supertutor Media, inc.
 */

class STTV_Jobs {
    private $jobs_table = STTV_PREFIX.'_jobs_data';

    private $st_jobs_table = STTV_PREFIX.'_jobs_data';

     private $submissions_table = STTV_PREFIX.'_jobs_submissions';
    
    public function __construct() {
        
    }
    public function init() {
        add_filter( 'query_vars', array($this, 'sttv_jobs_qvar') );
        add_filter( 'template_include', array($this,'jobs_page_template'), 99 );
        add_action( 'init', array($this, 'sttv_jobs_endpoint') );
        //add_action( 'parse_query', array($this, 'init_job_global') );
        $this->sttv_jobs_tables();
    }

    public function sttv_jobs_qvar($vars) {
        $vars[] = 'job-post';
        $vars[] = 'job-action';
        return $vars;
    }

    public function sttv_jobs_endpoint() {
        add_rewrite_rule('^jobs/(.*)/(.*)?$','index.php?pagename=jobs&job-post=$matches[1]&job-action=$matches[2]','top' );
        add_rewrite_rule('^jobs/(.*)?$','index.php?pagename=jobs&job-post=$matches[1]','top' );
    }

    public function jobs_page_template($template) {
        global $wp_query;
        $base = STTV_TEMPLATE_DIR;

        if (isset($wp_query->query['job-action'])) {
            if (($wp_query->query['job-action'] === 'edit' || $wp_query->query['job-action'] === 'submissions' ) && !current_user_can('manage_options')){
                return wp_redirect(site_url().'/'.$wp_query->query['pagename'].'/'.$wp_query->query['job-post']);
            } elseif ($wp_query->query['job-action'] === 'edit') {
                return $base.'jobs/edit-single.php';
            } elseif ($wp_query->query['job-action'] === 'apply') {
                return $base.'jobs/apply.php';
            } elseif ($wp_query->query['job-action'] === 'submissions') {
                return $base.'jobs/submissions.php';
            }
        }

        if (isset($wp_query->query['job-post'])) {
            if ($wp_query->query['job-post'] === 'edit') {
                return $base.'jobs/edit-all.php';
            } else {
                return $base.'jobs/single.php';
            }
        }
    
        return $template;
    }

    public function get_job($name = '') {
        global $wpdb;
        $job = $wpdb->get_results( 
            $wpdb->prepare("SELECT * FROM $this->jobs_table WHERE name=%s", $name) 
        );

        if (!$job) {
            return null;
        }
        return $this->set_job_object_types($job[0]);
    }

    public function get_jobs() {
        global $wpdb;
        return $wpdb->get_results( 
            $wpdb->prepare("SELECT * FROM $this->jobs_table WHERE status=%s", 'active') 
        );
    }

    public function create_job($job = array()){
        global $wpdb;
        $defaults = array(
            'title' => '',
            'name' => '',
            'description' => '',
            'url' => '/',
            'location' => 'Los Angeles, CA',
            'is_remote' => false,
            'status' => 'active',
            'sub_count' => 0
        );
        $vals = array_merge($defaults,$job);

        $init = $wpdb->insert($this->jobs_table,$vals,array('%s','%s','%s','%s','%s','%s','%d','%d'));

        if (!$init) {
            return $init;
        }

        $newjob = $wpdb->get_results( 
            $wpdb->prepare("SELECT * FROM $this->jobs_table WHERE status=%s", 'active') 
        );

        //return $wpdb->update($this->jobs_table,);
    }

    public function set_job_object_types($job){
        $job->id = (int) $job->id;
        $job->description = esc_html($job->description);
        $job->is_remote = (bool) $job->is_remote;
        $job->sub_count = (int) $job->sub_count;
        $job->date_posted = strtotime($job->date_posted);
        $job->expires = (int) $job->expires;
        return (object) $job;
    }

    public function sttv_jobs_tables() {
        global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		if($wpdb->get_var("SHOW TABLES LIKE '$this->jobs_table'") != $this->jobs_table) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $this->jobs_table (
                    id int(10) NOT NULL AUTO_INCREMENT,
                    name tinytext,
                    title tinytext,
                    description longtext,
                    url varchar(255),
                    location tinytext,
                    category tinytext,
                    is_remote boolean NOT NULL DEFAULT 0,
                    status tinytext,
                    sub_count smallint DEFAULT 0,
                    date_posted TIMESTAMP,
                    expires int(10),
                    UNIQUE KEY id (id)
            ) AUTO_INCREMENT=20508 $charset_collate;";
            dbDelta( $sql );
        }
        
        if($wpdb->get_var("SHOW TABLES LIKE '$this->submissions_table'") != $this->submissions_table) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $this->submissions_table (
                    id int(10) NOT NULL AUTO_INCREMENT,
                    job_id int(10),
                    email varchar(255),
                    user_data longtext,
                    date_applied TIMESTAMP,
                    UNIQUE KEY id (id)
            ) $charset_collate;";
            dbDelta( $sql );
		}
    }

 }
(new STTV_Jobs)->init();

class STTV_Jobs_REST extends WP_REST_Controller {
    private $jobs;

    public function __construct() {
        $this->jobs = new STTV_Jobs();
        add_action( 'rest_api_init', array($this, 'jobs_rest_init') );
    }

    public function jobs_rest_init() {
        
    }
 }
 new STTV_Jobs_REST;