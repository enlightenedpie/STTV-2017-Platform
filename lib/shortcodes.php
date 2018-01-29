<?php 

######################
##### SHORTCODES #####
######################

/**
 *
 *
 * STTV Bio Shortcode
 * Description: Renders side-photo material cards for employee headshots and bios
 *
 * @param $atts and $content
 * @return HTML content to display
 *
 *
**/

add_shortcode('sttv-bio','sttv_shortcode_bio');
function sttv_shortcode_bio($atts,$content) {
	
	$sttv_atts = shortcode_atts( array(
			'name' => 'name',
			'title' => 'title',
			'img' => 10656,
			'columns' => '2'
    		), $atts, 'sttv-bio' );
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

/**
 *
 *
 * STTV Subscribe Form
 * Description: Renders a form for subscribing people to STTV's mailing list via Mailchimp
 *
 * @param $atts and $content
 * @return HTML content to display
 *
 *
**/

add_shortcode('subscribe-form','sttv_subscribe_form');
function sttv_subscribe_form($atts,$content = '') {
	global $current_user;
	wp_get_current_user();
	
	$atts = shortcode_atts( array(
			'context' => 'content',
			'title' => 'Subscribe Now!'
    		), $atts, 'sttv-bio' );
	$context = ($atts['context'] == 'sidebar') ? 'l12' : 'l6' ;
	
	
	return '<div id="subscribeform">
				<script>
					var response = function() {
						grecaptcha.getResponse();
					};
				</script>
				<h4>'.$atts['title'].'</h4>
				<div id="subscribe-c2a">'.$content.'</div>
				<div id="sub_redir_button">
					<a class="z-depth-2 button" href="'.site_url().'/subscribe"><h3>Subscribe now!</h3></a>
				</div>
			</div>';
	
}

add_shortcode('stripe-plan','sttv_stripe_plan');
function sttv_stripe_plan($atts,$content='') {
	
	$contents = explode(',',$content);
	
	$highlight = false;
	if (in_array('highlight',$atts)) {
		$highlight = 'highlight';
	}
	
	$atts = shortcode_atts( array(
			'plan' => 'oops',
			'columns' => '2',
			'title' => 'The Best',
			'price' => '$5000',
			'length' => ''
    ), $atts, 'course-plan' );
	
	$price = str_replace('.','',str_replace('$','',$atts['price']));
	
	$databind = json_encode([
		'ID' => $atts['plan'],
		'title' => sanitize_text_field($atts['title']),
		'price' => (int) $price
	]);
	
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
            	<caption class="<?php echo ($highlight) ?: ''; ?>"><a href="javascript:void(0)" class="payment-launcher" data-bind='<?php echo $databind; ?>'><?php _e(str_replace('.00','',$atts['price'])); ?></a></caption>
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
                	<a href="<?php echo get_permalink(); ?>/checkout" class="payment-launcher pmt-button btn waves-effect waves-light" data-bind='<?php echo $databind; ?>'>Sign up now!</a>
                </td>
            </tr>
            </table>
            <?php //print_r($atts); ?>
		</div>
	</div>
	<?php return ob_get_clean();
}

add_shortcode('sttv-faq','sttv_faq_display');
function sttv_faq_display($atts,$content='') {
	ob_start(); ?>
	<div id="sttv_faq_<?php echo $atts['id']; ?>" class="sttv-faq-column col s12 m6">
    	<div class="sttv-faq-inner"><?php echo $content; ?></div>
    </div>
<?php 
	return ob_get_clean();
}


add_shortcode('sttv-school-card','sttv_school_card_output');
function sttv_school_card_output($atts,$content='') {
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