<?php

namespace STTV;

defined( 'ABSPATH' ) || exit;

class Shortcodes {

	private static $codes = [
		'stBlock',
		'stContainer',
		'stCallout',
		'stMailinglist',
		'stSocial',
		'stLogin',
		'stBio',
		'stFAQ',
		'stStripePlan'
	];

	public static function init() {
		foreach ( self::$codes as $code ) {
			add_shortcode( $code, function( $atts = [], $content = '', $name ) use ($code) {
				ob_start();
				require STTV_TEMPLATE_DIR . 'shortcodes/' . $code . '.php';
				return ob_get_clean();
			});
		}
	}

	public static function stbio($atts,$content) {

		$sttv_atts = shortcode_atts( [
			'name' => 'name',
			'title' => 'title',
			'img' => 10656,
			'columns' => '2'
		], $atts, 'stBio' );
		$cols = '';
	
		switch($sttv_atts['columns']) :
			case '1':
				$cols = '12';
				break;
			case '3':
				$cols = '4';
				break;
			case '4':
				$cols = '3';
				break;
			case '6':
				$cols = '2';
				break;
			default :
				$cols = '6';
		endswitch;
	
	
		return '<div class="col l'.$cols.' m12">
					<div class="card">
						<div class="card-image waves-effect waves-block waves-light">
							'.wp_get_attachment_image( $sttv_atts['img'], 'full', false, array('class' => 'responsive-img activator' )).'
							</div>
						<div class="card-content">
							<h5 class="activator">'.$sttv_atts['name'].'<i class="material-icons right">more_vert</i></h5><span>'.$sttv_atts['title'].'</span>
						</div>
						<div class="card-reveal">
							<span class="card-title">'.$sttv_atts['name'].'<i class="material-icons right">close</i></span>
							<p>'.$content.'</p>
						</div>
					</div>
				</div>';
	}

	public static function sttv_school_card($atts,$content='') {
		$i = get_intermediate_image_sizes();
		ob_start(); ?>
		<div class="sttv-school-card row">
			<div class="card horizontal">
			  <div class="card-image">
				<?php echo wp_get_attachment_image($atts['img'],'large',false); ?>
			  </div>
			  <div class="card-stacked">
				<div class="card-content">
					<h3><?php echo $atts['title']; ?></h3>
					<p><small><?php echo $content; ?></small></p>
				</div>
				<div class="card-action">
					<p><?php
						$atts = array_diff_key($atts,['title'=>'','img'=>'']);
						foreach ($atts as $k=>$v) {
							echo '<span class="seafoam" style="font-style:italic;font-size:.75em">'.ucfirst($k).': '.$v.'&nbsp;</span>';
						}
					?></p>
				</div>
			  </div>
			</div>
		</div>
		<?php return ob_get_clean();
	}
}
