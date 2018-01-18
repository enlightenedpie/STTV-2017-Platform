<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
*
* STTV Course object class
*
* @package sttv/courses
* @since 1.4
*
**/

class STTV_Course {
	
	public $id;

	public $name;

	public $slug;

	public $link;

	public $sales_page;

	public $test;

	public $intro;

	public $description;

	public $tl_content;

	public $dashboard;

	public $sections = [];

	private $cap;

	private $allcaps = [];

	private $created;

	private $modified;
	
	public function __construct( $course ) {
		$this->id = $course;
		return $this;
	}

	public function set_name( $name ) {
		$this->name = $name;
		return $this;
	}

	public function set_slug( $slug ) {
		$this->slug = $slug;
		return $this;
	}

	public function set_link( $link ) {
		$this->link = $link;
		return $this;
	}
	
	public function set_sales_page( $page ) {
		$this->sales_page = $page;
		return $this;
	}

	public function set_test( $test ) {
		$this->test = $test;
		return $this;
	}

	public function set_intro( $intro ) {
		$this->intro = $intro;
		return $this;
	}

	public function set_description( $desc ) {
		$this->description = $desc;
		return $this;
	}

	public function set_tl_content( $tlc ) {
		$this->tl_content = $tlc;
		return $this;
	}

	public function set_dashboard( $dash ) {
		$this->dashboard = $dash;
		return $this;
	}

	public function set_cap( $cap ) {
		$this->cap = $cap;
		return $this;
	}

	public function set_all_caps( $allcaps ) {
		$this->allcaps = $allcaps;
		return $this;
	}

	public function set_created( $cre ) {
		$this->created = $cre;
		return $this;
	}

	public function set_modified( $mod ) {
		$this->modified = $mod;
		return $this;
	}
} // end class.

function sttvcourse( $id = 0 ) {
	static $instance;
	if ( null === $instance ) {
		$instance = new STTV_Course( $id );
	}
	return $instance;
}