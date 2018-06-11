<?php

$user = $sgt['user'];
$shipping = '<span>No shipping options available for mutli-user license orders.</span>';
$disabled = 'disabled';

if ( $sgt['type'] === 'checkout' ) {
$disabled = '';
HTML;
/*
<input class='filled-in' type='checkbox' name='sttv_no_trial' id='sttv_no_trial' checked/><label style='margin-top:1em' for='sttv_no_trial'>Skip the trial period and start right away</label><br/>
*/
}

?>

<section id='checkout-wrapper' class='row'>
	<div id='checkout-info' class='align-middle' style='display:block;'>
        <div id='wrapper_line-item' class='col s12'>
            <div id='customer_info' class='row'>
                <div id='account_info' class='col s12'>
                    <div class='row'>
                        <h4>Your Information</h4>
                        <div class='input-field left-col col s6'>
                            <input id='sttv_firstname' type='text' class='validate' name='sttv_firstname' value='<?php print $user->first_name; ?>' required/>
                            <label for='sttv_firstname'>First Name</label>
                        </div>
                        <div class='input-field left-col col s6'>
                            <input id='sttv_lastname' type='text' class='validate' name='sttv_lastname' value='<?php print $user->last_name; ?>' required/>
                            <label for='sttv_lastname'>Last Name</label>
                        </div>
                        <div class='input-field col s12'>
                            <input id='sttv_email' class='validate' name='sttv_email' type='email' value='<?php print $user->user_email; ?>' required/>
                            <label for='sttv_email'>Email Address</label>
                        </div>
                        <div class='input-field col s12'>
                            <input id='sttv_password' name='sttv_password' type='password' value='<?php print $user->user_pass; ?>' required/>
                            <label for='sttv_password'>Choose Password</label>
                        </div>
                        <div class='input-field col s12'>
                            <input id='sttv_phone' class='validate' name='sttv_phone' type='tel' value='' required/>
                            <label data-error='Invalid phone number' for='sttv_phone'>Phone Number</label>
                        </div>
                    </div>
                </div>
              </div>
                <input type='button' class='next_button' onClick='changePanel('checkout-info', 'checkout-billing')' value='next' style='float: right;'/>
            </div>
          </div>
      <div id='checkout-billing' class='align-middle' style='display:none;'>
            <div id='wrapper_line-item' class='col s12'>
              <div id='billing_info' class='col s12'>
                  <div id='billing_fields' class='row'>
                      <h4>Billing Address</h4>
                      <div class='input-field col s12'>
                          <input id='sttv_billing_address1' name='sttv_billing_address1' type='text' class='validate' required/>
                          <label class='active' for='sttv_billing_address1' data-error='Invalid format' >Address Line 1</label>
                      </div>
                      <div class='input-field col s12'>
                          <input id='sttv_billing_address2' name='sttv_billing_address2' type='text' />
                          <label class='active' for='sttv_billing_address2'>Address Line 2</label>
                      </div>
                      <div class='input-field col s12'>
                          <input id='sttv_billing_city' name='sttv_billing_city' class='validate' type='text' required/>
                          <label class='active' for='sttv_billing_city'>City</label>
                      </div>
                      <div class='input-field left-col col s6'>
                          <input id='sttv_billing_state' name='sttv_billing_state' class='validate' type='text' required/>
                          <label class='active' for='sttv_billing_state'>State</label>
                      </div>
                      <div class='input-field col s6'>
                          <input id='sttv_billing_pcode' name='sttv_billing_pcode' class='validate' type='tel' required/>
                          <label class='active' for='sttv_billing_pcode'>Postal Code</label>
                      </div>
                      <div class='input-field col s12'>
                          <select class='country-dd' name='sttv_billing_country' required>
                              <option value disabled selected>Country...</option>
							<?php print $sgt['countrydd']; ?>
                          </select>
                      </div>
                      <div class='input-field col s12'>
                          <input class='filled-in' type='checkbox' id='same_as_billing'/><label for='same_as_billing'>Use same shipping address</label>
                      </div>
                  </div>
                    <input type='button' class='prev_button' onClick='changePanel('checkout-billing', 'checkout-info')' value='previous' style='padding-top:1%'/>
                    <input type='button' class='next_button' onClick='changePanel('checkout-billing', 'checkout-shipping')' value='next' style='float: right;'/>
              </div>
            </div>
        </div>
    <div id='checkout-shipping' class='align-middle' style='display:none;'>
        <div id='shipping_info' class='col s12'>
            <div id='shipping_fields' class='row'>
                <h4>Shipping Address</h4>
                <div class='input-field col s12'>
                    <input id='sttv_shipping_address1' name='sttv_shipping_address1' type='text' class='validate' required/>
                    <label for='sttv_shipping_address1' data-error='Invalid format' >Address Line 1</label>
                </div>
                <div class='input-field col s12'>
                    <input id='sttv_shipping_address2' name='sttv_shipping_address2' type='text' />
                    <label for='sttv_shipping_address2'>Address Line 2</label>
                </div>
                <div class='input-field col s12'>
                    <input id='sttv_shipping_city' class='validate' name='sttv_shipping_city' type='text' required/>
                    <label for='sttv_shipping_city'>City</label>
                </div>
                <div class='input-field left-col col s6'>
                    <input id='sttv_shipping_state' class='validate' name='sttv_shipping_state' type='text' required/>
                    <label for='sttv_shipping_state'>State</label>
                </div>
                <div class='input-field col s6'>
                    <input id='sttv_shipping_pcode' class='validate' name='sttv_shipping_pcode' type='tel' required/>
                    <label for='sttv_shipping_pcode'>Postal Code</label>
                </div>
                <div class='input-field col s12'>
                    <select class='country-dd validate' name='sttv_shipping_country' required>
                        <option value disabled selected>Country...</option>
                        <?php print $sgt['countrydd']; ?>
                    </select>
                </div>
            </div>
          <input type='button' class='prev_button' onClick='changePanel('checkout-shipping', 'checkout-billing')' value='previous'/>
          <input type='button' class='next_button' onClick='changePanel('checkout-shipping', 'checkout-order')' value='next' style='float: right;'/>
        </div>
    </div>
    <div id='checkout-order' class='align-middle' style='display:none;'>
        <div class='row'>
            <h4>Your Order</h4>
            <div class='col s12' style='margin-top:10px'>
                <div class='row'>
                    <div id='shipping_options' class='col s12' style='margin-top:1%'>
                        <div class='shp-msg'>
                          <span>Shipping is for U.S. orders only.</span>
                          <div class='input-field col s12' style='margin-top:0px'>
                              <p>
                                  <input id='freeship' name='shipping_options' type='radio' value='0' checked />
                                  <label for='freeship'>Free Shipping (1-3 weeks)</label>
                              </p>
                              <p>
                                  <input id='priship' name='shipping_options' type='radio' value='1285' />
                                  <label for='priship'>Priority Shipping (3-4 days)</label>
                              </p>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id='cart-column' class='col s12'>
                <div class='row headings-row'>
                    <div class='col s2'>Qty</div>
                    <div class='col s8'>Item</div>
                    <div class='col s2 right-align'>Price</div>
                </div>
                <div class='items-row'></div>
                <div class='row totals-row'>
                    <div class='col s8'>
                        <div class='input-field coupon col s12'>
                            <input id='sttv_coupon' name='sttv_coupon' type='text' <?php print $disabled; ?>/>
                            <label for='sttv_coupon'>Coupon Code</label>
                        </div>
                    </div>
                    <div id='total' class='col s4 right-align'><span id='ttltxt'>Total: $<span>0</span></span></div>
                </div>
            </div>
        </div>
        <div class='card-content row'>
            <h4>Payment</h4>
            <div class='input-field col s12'>
                <input name='sttv_cardname' type='text' required/>
                <label class='active' for='sttv_cardname'>Name On Card</label>
            </div>
            <div class='input-field col s12'>
                <div id='sttv_card_element'></div>
            </div>
            <div class='col s12'>
                <div class='input-field col s12' style='margin-top:0px'>
                    <input class='filled-in' type='checkbox' name='sttv_mailinglist' id='sttv_mailinglist' checked/><label for='sttv_mailinglist'>Sign me up for promos, coupons, and giveaways from SupertutorTV</label><br/>
                </div>
                <div class='tandc col s12'>
                    <span>By submitting this payment, you agree to SupertutorTV's <a class='azure' href='<?php echo site_url('terms-and-conditions'); ?>' target='blank'>Terms and Conditions</a>.</span>
                </div>
            </div>
            <div class='col s12'>
                <button type='submit' class='signup-submit button-wide z-depth-1 waves-effect waves-light' disabled><span>Place Order</span></button>
            </div>
            <div class='col s12'>
                <p class='error token'>&nbsp;</p>
            </div>
            <input type='button' class='prev_button' onClick='changePanel('checkout-order', 'checkout-shipping')' value='previous'/>
        </div>
    </div>
</section>
<script type='text/javascript' src='<?php echo site_url(); ?>/wp-content/themes/sttv_2017/s/checkout.js?ver=<?php echo STTV_VERSION; ?>' id='sttv-checkout-js'></script>
