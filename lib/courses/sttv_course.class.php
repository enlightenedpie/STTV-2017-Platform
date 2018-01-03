<?php

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
	
	public function __construct($course = 0) {
		$this->id = $course;
		return $this;
	}
	
}