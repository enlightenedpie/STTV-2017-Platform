$(document).ready(function() {
	"use strict";

	var loader = '<div id="checkout_modal" class="modal"><div id="checkout_modal_overlay"><img src="'+stajax.contentURL+'/i/sttv-spinner.gif"/><span></span></div><header id="checkoutheader"><div id="logo-box"><img src="https://supertutortv.com/wp-content/uploads/2017/01/sttv_site_logo.png" alt="logo" /></div></header><div class="modal-content"></div></div>';

	$('body').prepend(loader);
	window.cModal = $('#checkout_modal');

	// init Modal
			$('.modal').modal({
			  dismissible : true,
			  opacity : .8,
			  inDuration: 500,
      		  outDuration: 250,
			  startingTop: '1%',
      		  endingTop: '10%'
			});
	// end init modal


	$('.payment-launcher').click(function(e) {
		e.preventDefault();
		var planData = JSON.parse($(this).attr('data-bind'));

		cModal.animate({
			scrollTop: cModal.offset().top
		}, 1);
		cModal.css('overflow','hidden');
		cModal.modal('open');
		$('#checkout_modal_overlay').fadeIn(500,function() {

			fsub.checkout(planData);

		}).delay(1000).queue(function(next){
			price_updater();
			next();
		}).fadeOut(500,function() {
			cModal.css('overflow','auto');
		});

		$('[name=sttv_email],[name=sttv_billing_pcode],input[name=sttv_coupon]').blur();

	});

}); // end document ready

var fsub = {
			valid : false,
			shipWasChecked : false,
			setOutcome : function(result) {
				var successElement = document.querySelector('.success');
				var errorElement = document.querySelector('.error');
				successElement.classList.remove('visible');
				errorElement.classList.remove('visible');

				fsub.valid = result.complete;

				var validSub = ($('#t_and_c').is(':checked') && result.complete);
					$('.signup-submit').prop('disabled',!validSub);

				if (typeof result.error !== 'undefined') {
					$('.error').text(result.error.message);
				} else {
					$('.error').text('');
				}
			},// end setOutcome
			setToken : function(result) {

				if (result.error) {
				  $('#checkout_modal_overlay').fadeOut(1,function() {
					  $('.error',cModal).text(result.error.message);
				  });

				  console.log(result.error);
				  return false;
			  	}

				cModal.animate({
					scrollTop: 0
				}, 1);
				cModal.css('overflow','hidden');
				$('#checkout_modal_overlay').fadeIn(1,function() {
					$(this).prepend('<h2 style="margin-top:4em">PROCESSING...</h2>');
					$('span',this).text('This could take a minute if you have a slow connection.');
				});
			  console.log(result,"pre POST");
				  data.token = result.token.id;

				  $.post(
					stajax.ajaxURL,
					data,
					function(response) {
						var action = {};

						if (response.success) {
							action.ST = function() {
								window.location.replace(response.data);
							};
							action.action = 'Success';
							action.color = 'olive';
							action.msg = 'You will be redirected shortly';
							action.icon = 'done';

						} else {
							action.ST = function() {
								cModal.modal('close');
							};
							action.action = 'Error';
							action.color = 'red-text text-darken-3';
							action.msg = response.data.message;
							action.icon = 'report_problem';
						}
						var appended = '<div>';
						appended += '<h2 class="'+action.color+'">';
						appended += '<i class="material-icons">'+action.icon+'</i> ';
						appended += action.action+'</div>';
						appended += '<small>'+action.msg+'</small><br/>';
						appended += (!response.success)?'<small>err: '+response.data.error+'</small>':'';

						console.log(response);
						$('.modal-content',cModal).empty();
						$('.modal-content',cModal).append(appended);
						setTimeout(function(){action.ST()},3000);
				});
			},//end setToken
			checkout : function(pDt) {

				if (!$('table#totals_table').length) {
					$('.modal-content',cModal).empty();

					$.get({
						url: stajax.contentURL+'/templates/html/_checkout.html',
						cache: false
					}).then(function(d){
						//console.log(d);
						$('.modal-content',cModal).append(d);
					},"html")
					.fail(function(){
						alert('Something went wrong. Please reload the page.');
					}); // end GET

				} //endif

				if (window.plan == undefined || window.plan.ID !== pDt['ID']) {
					window.plan = {
						ID : pDt['ID'],
						name : pDt['title'],
						price : parseInt(pDt['price']),
						tax : 0,
						shipping : 0,
						disc : 0,
						discp : 0
					};
				} //endif

			}, //end checkout()
			globalTester : function() {
				console.log("Success!");
			}
		}; //end fsub

function price_updater() {
		plan.shipping = ($('#digital_book').is(':checked')) ? 1285 : 0;
		var price = parseFloat(plan.price);
		var taxed = (2500*(plan.tax/100));
		var totals = (((price-(price*(plan.discp/100))-plan.disc)+taxed)+plan.shipping);

		if (0 < totals) {
			var btnmsg = 'Pay $'+(totals/100).toFixed(2)+' now!';
		} else {
			var btnmsg = 'Sign up for FREE now!';
		}
		$('button.signup-submit span').fadeOut(100,function() {
			$(this).text(btnmsg);
		}).fadeIn(100);

		var table = $('table#totals_table');

		$('tbody',table).fadeOut(100,function() {
			$(this).empty().append('<tr><td colspan="2">'+plan.name+'</td><td style="text-align:right">'+(price/100).toFixed(2)+'</td></tr>');

			if (0 < plan.disc) {
				var discprice = plan.disc;
			} else if (0 < plan.discp) {
				var discprice = (price*(plan.discp/100));
			}
			if (0 < plan.disc || 0 < plan.discp) {
				$(this).append('<tr><td colspan="2"><small>Discount ('+plan.coupon+')</small></td><td style="text-align:right"><small>-'+(discprice/100).toFixed(2)+'</small></td></tr>');
			}
			if (0 < plan.tax && 0 < price) {
				$(this).append('<tr><td colspan="2"><small>CA Tax ('+plan.tax+'%)</small></td><td style="text-align:right"><small>+'+(taxed/100).toFixed(2)+'</small></td></tr>');
			}
			if (plan.shipping) {
				$(this).append('<tr><td colspan="2"><small>Priority Shipping</small></td><td style="text-align:right"><small>+'+(plan.shipping/100).toFixed(2)+'</small></td></tr>');
			}

		}).fadeIn(100);

		$('#signup_total_price span').fadeOut(100,function() {
			$(this).text('$'+(totals/100).toFixed(2));
		}).fadeIn(100);

	}

	_st.checkout = (function(element) {
		var type = _st.checkout,
			cart = _st.cart.get(),
			total = tax = taxRate = shipping = disc = discp = 0,
			html = ''

			for ( var key in cart ) {
				var item = cart[key],
					price = item.price*item.qty
	
				if ( item.taxable !== false ) {
					tax += (price*taxRate)/100
				}
				total += price+tax

				html += '<div class="col s12">'+item.qty+'x '+item.name+' | $'+price/100+'</div>'
			}

		element.html(html)

		return {
			type : type,
			items : cart,
			totals : {
				total : total,
				tax : tax,
				taxRate : taxRate,
				shipping : shipping,
				disc : disc,
				discp : disc
			},
			update : ''
		}
	})($('#cart-column'))