<?php 

/* Template Name: MU Student Signup */

?>
<?php get_header(); ?>
<?php get_template_part('templates/title'); ?>

<div id="content-wrapper-signup" class="col s12">
    <?php the_content(); ?>
</div>
<div id="mu_form_wrapper" class="col s12">
    <div class="row">
        <div id="mu_form_inner" class="col s12 m6 l4 offset-m3 offset-l4">
        <div id="account_info" class="col s12">
            <div class="row">
                <div class="input-field left-col col s6">
                    <input type="text" class="validate" name="sttv_firstname" value="" required/>
                    <label for="sttv_firstname">First Name</label>
                </div>
                <div class="input-field left-col col s6">
                    <input type="text" class="validate" name="sttv_lastname" value="" required/>
                    <label for="sttv_lastname">Last Name</label>
                </div>
                <div class="input-field col s12">
                    <input id="sttv_email" class="validate" name="sttv_email" type="email" value="" required/>
                    <label data-error="Invalid email address" for="sttv_email">Email Address</label>
                </div>
                <div class="input-field col s12">
                    <input id="sttv_password" name="sttv_password" type="password" value="" required/>
                    <label for="sttv_password">Choose Password</label>
                </div>
                <div class="input-field col s12">
                    <input id="mukey" class="validate" data-type="mukey" name="mukey" type="text" required/>
                    <label data-error="Invalid license key" for="mukey">License Key</label>
                </div>
                <div class="input-field col s12">
                    <button type="submit" class="mu-signup signup-submit button-wide z-depth-1 waves-effect waves-light" disabled>Sign up now!</button>
                </div>
                <div class="input-field col s12">
                    <p class="message"></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>