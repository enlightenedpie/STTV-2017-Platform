<?php 
$lostpw = wp_lostpassword_url();
stFWInset('stBordered',<<<HTML
<div id="stLoginWrapper" class="stFormWrapper row">
    <div class="stOverlay"></div>
    <div id="stLoginHeader" class="stFormHeader col s12">
        <h2>Sign into your account</h2>
        <span>You can access all of your test prep courses, as well as all of your account information, by logging in below. <br></span>
    </div>
    <div id="stLoginCredentials" class="col s12">
        <div class="input-field col s12">
            <input class="browser-default validate email" type="email" name="st-username" placeholder="Email Address" onblur="_st.login.prepare(this)"/>
        </div>
        <div class="input-field col s12">
            <input class="browser-default validate" type="password" name="st-password" placeholder="Password" onblur="_st.login.prepare(this)"/>
        </div>
    </div>
    <div class="stForgotBlock col s12">
        <span><a href="{$lostpw}">Forgot your password?</a></span>
    </div>
    <div class="stFormButtons col s12">
        <button class="stFormButton pmt-button btn waves-effect waves-light" onclick="_st.login.submit()">Login</button>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function(event) { 
        _st.login = new _st.login()
    });
</script>
HTML
);