<?php

namespace STTV;

defined( 'ABSPATH' ) || exit;

class Shortcodes {

	public static function init() {
		$codes = [
			'stBlock',
			'stContainer',
			'stCallout',
			'stMailinglist',
			'stSocial',
			'stLogin',
			'sttv-bio',
			'sttv-school-card',
			'stripe-plan',
			'sttv-faq'
		];

		foreach ( $codes as $code ) {
			add_shortcode( $code, __CLASS__ . '::' . str_replace('-','_',$code) );
		}
	}

	public static function stBlock($dump,$content) {
		ob_start(); ?>
		<div <?php if (isset($dump['id']) && !empty($dump['id'])) echo "id=\"{$dump['id']}\""; ?> class="stBlock row"><?php echo do_shortcode($content); ?></div>
		<?php return ob_get_clean();
	}

	public static function stContainer($atts,$content) {
		extract(array_merge([
			'cols' => '12',
			'class' => 'default',
			'title' => ''
		],$atts));
		ob_start(); ?>
		<div class="stContainer col s12 m<?php echo $cols.' '.$class;?>">
		<?php if ($title) : ?><div class="stContainerTitle row"><h3><?php echo $title; ?></h3></div><?php endif; ?>
		<div class="stContainerText row"><?php echo $content; ?></div>
		</div>
		<?php return ob_get_clean();
	}

	public static function stCallout($atts,$content) {
		extract(array_merge([
			'cols' => '12',
			'class' => 'default',
			'color' => 'azure',
			'title' => 'Callout',
			'link' => '/',
			'linktext' => 'Go to link'
		],$atts));
		ob_start(); ?>
		<div class="stCallout col s12 m<?php echo $cols; ?>">
			<div class="stCalloutInner z-depth-4 col s12">
				<div class="stCalloutContent row">
					<div class="stCalloutIcon <?php echo $class; ?> z-depth-2"></div>
					<div class="stCalloutTitle"><h2><?php echo $title; ?></h2></div>
					<div class="stCalloutBlurb"><?php echo $content; ?></div>
				</div>
				<div class="stCalloutLink row">
					<a href="<?php echo $link; ?>"><?php echo $linktext; ?></a>
				</div>
			</div>
		</div>
		<?php return ob_get_clean();
	}

	public static function sttv_bio($atts,$content) {

		$sttv_atts = shortcode_atts( [
			'name' => 'name',
			'title' => 'title',
			'img' => 10656,
			'columns' => '2'
		], $atts, 'sttv-bio' );
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

	public static function stripe_plan($atts,$content='') {

		$contents = explode(',',$content);
		$ids = explode( ',', $atts['id'] );
	
		$highlight = false;
		if (in_array('highlight',$atts)) $highlight = 'highlight';
	
		$databind = [];
		foreach( $ids as $id ) $databind[] = $id;
	
		$cols = '';
	
		switch($atts['columns']) :
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
	
		ob_start(); ?>
		<div class="row">
			<div class="col s12 m8 l6 xl4 offset-m2 offset-l3 offset-xl4 sttv-sales-table-wrapper <?php echo ($highlight) ? $highlight.' z-depth-4': ''; ?>">
				<table id="sttv-sales-table-<?php echo $atts['plan']; ?>" class="sttv-sales-table centered">
					<caption class="<?php echo ($highlight) ?: ''; ?>"><a data-course='<?php echo base64_encode(json_encode($databind)); ?>' onclick="_st.modal.init('checkout',this)" class="payment-launcher" >$<?php _e(str_replace('.00','',$atts['price'])); ?></a></caption>
						<?php /*?><tr>
							<td>
								<span class="sttv-course-price"><?php echo $atts['price']; ?></span>
								<?php
									echo (!empty($atts['length'])) ? ' / '.$atts['length'] : '';
								?>
							</td>
						</tr><?php */?>
					<?php
	
					foreach ($contents as $con) :
						$con = str_replace('{comma}',',',$con);
						$high = false;
						if (stristr($con,'(highlight)')) {
							$high = 'txt-highlight';
							$con = str_replace('(highlight)','',$con);
						}
	
						?><tr><td><?php echo ($high) ? '<span class="'.$high.'">'.$con.'</span>' : $con; ?></td></tr><?php
	
					endforeach;
	
				?>
				<tr>
					<td>
						<a data-course='<?php echo base64_encode(json_encode($databind)); ?>' onclick="_st.modal.init('checkout',this)" class="payment-launcher pmt-button btn waves-effect waves-light"> Sign up now!</a>
					</td>
				</tr>
				</table>
			</div>
		</div>
		<?php return ob_get_clean();
	}

	public static function sttv_faq($atts,$content='') {
		ob_start(); ?>
		<div id="sttv_faq_<?php echo $atts['id']; ?>" class="sttv-faq-column col s12 m6">
			<div class="sttv-faq-inner"><?php echo $content; ?></div>
		</div>
	<?php return ob_get_clean();
	}

	public static function stMailinglist($att,$con,$tag) {
		return self::stTemplate($tag);
	}

	public static function stSocial($att,$con,$tag) {
		return self::stTemplate($tag);
	}

	public static function stLogin($att,$con,$tag) {
		return self::stTemplate($tag);
	}

	private static function stTemplate($name) {
		ob_start();
		sttv_get_template($name);
		return ob_get_clean();
	}
}
