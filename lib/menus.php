<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sttv_add_menus');

function sttv_add_menus() {
	
	register_nav_menus( [
		'sttv-nav' => 'SupertutorTV Main Nav Menu',
		'sttv-mobile-nav' => 'SupertutorTV Mobile Nav Menu',
		'footer-menu' => 'SupertutorTV Footer Menu',
		'tutoring-info' => 'SupertutorTV Tutoring Info Links'
	 ] );

}

class Tutoring_Info_Walker extends Walker_Nav_Menu {

	function start_lvl( &$output, $depth = 0, $args = [] ) {
		$output .= '';
	}

	function end_lvl( &$output, $depth = 0, $args = [] ) {
		$output .= '';
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $post;
		$active = ($post->ID == $item->object_id) ? ' active' : '';
		$output .= "<a class='collection-item{$active}' href='{$item->url}'>{$item->title}</a>";
		//$output .= print_r($item);
	}
}

#####################################
##### CUSTOM NAV MENU GENERATOR #####
#####################################

class STTV_Nav_Menu {

	public $output = '';

	private $items = [];

	private $menu_id = '';

	private $menu_class = '';

	public function __toString() {
		return $this->output;
	}

	public function __construct($menu_name = '', $mid = '', $mclass = '') {
		if ( ( $locations = get_nav_menu_locations() ) && !isset( $locations[ $menu_name ] ) ) return '<span>Menu not found</span>';

		$this->menu_id = $mid;
		$this->menu_class = $mclass;
		$menu = wp_get_nav_menu_items( wp_get_nav_menu_object( $locations[ $menu_name ] )->term_id );
		foreach ($menu as $item) {
			$mn = [
				'title' => $item->title,
				'url' => $item->url ?? '',
				'class' => implode(' ',$item->classes)
			];
			if ($item->menu_item_parent != 0) 
				$this->items[$item->menu_item_parent]['children'][$item->ID] = $mn;
			else
				$this->items[$item->ID] = $mn;
		}
		$this->render();
	}

	private function render() {
		$ulclass = !$this->menu_class ? '' : " class='{$this->menu_class}'";
		$ulid = !$this->menu_id ? '' : " id='{$this->menu_id}'";

		$this->output .= "<ul$ulclass$ulid>";

		$this->draw_lvl($this->items);

		$this->output .= '</ul>';
	}

	private function draw_lvl($arr) {
		foreach ($arr as $id => $atts) {
			$theid = " id='menu-item-{$id}'";
			$classes = empty($atts['class']) ? '': " class='{$atts['class']}'";
			$theurl = empty($atts['url']) ? '' : " href='{$atts['url']}'";
	
			$this->output .= "<li$theid><a$classes$theurl";
	
			if (isset($atts['children'])) {
				$submenuid = 'sub-menu-' . strtolower($atts['title']);
				$this->output .= " data-target='{$submenuid}'>{$atts['title']}</a>";
				$this->output .= "<ul class='dropdown-content' id='{$submenuid}'>";
				$this->draw_lvl($atts['children']);
				$this->output .= '</ul>';
			} else {
				$this->output .= ">{$atts['title']}</a>";
			}
	
			$this->output .= '</li>';
		}
	}
}

function sttv_nav_menu($menu_name = '', $mid = '', $mclass = '') {
	echo new STTV_Nav_Menu($menu_name,$mid,$mclass);
}