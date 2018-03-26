<div id="mu_form_wrapper" class="col s12">
    <div class="row">
        <div id="mu_form_inner" class="col s12 m6 offset-m3">
            <div class="input-field col s12">
                <input id="mukey" class="validate" data-type="mukey" name="mukey" type="text" required/>
                <label data-error="Invalid invitation code" for="mukey">Invitation Code</label>
            </div>
            <div class="input-field col s12">
                <input id="email" class="validate" data-type="email" name="email" type="email" required/>
                <label data-error="Invalid email address" for="email">Email Address</label>
            </div>
            <div class="input-field col s12">
                <div id="sttv_mu_card_element">
            </div>
            <div class="col s12">
                <button type="submit" class="signup-submit button-wide z-depth-1 waves-effect waves-light" disabled><span></span></button>
            </div>
        </div>
    </div>
</div>
<script>
    var stripe = Stripe(stajax.stripe.public_key);
	var elements = stripe.elements();
	var card = elements.create('card',{
		hidePostalCode: true
	});
	card.mount('#sttv_mu_card_element');
</script>