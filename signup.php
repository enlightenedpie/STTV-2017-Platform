<?php 

/* Template Name: Signup Template */

get_header('stripped'); ?>
<section id="stFWStrippedInner" class="row">
    <div id="stFWInsetRight" class="col s12 l8 offset-l2 stBordered">
        <div id="stFWInsetRightInner" class="z-depth-4">
            <header class="stBordered row">
                <div id="stFWInsetDarkHeader" class="col s12">
                    <img src="http://localhost:8888/sttvroot/wp-content/themes/sttvsite/i/sttv_logo_contrast.png" />
                </div>
            </header>
            <header class="stBoxed row">
                <div id="stFWInsetLightHeader" class="col s12">
                    <img src="http://localhost:8888/sttvroot/wp-content/themes/sttvsite/i/sttv_logo_contrast.png" />
                </div>
            </header>
            <div id="stSignupWrapper" class="stFormWrapper row">
                <div class="stOverlay"></div>
                <div id="stSignupForm"></div>
            </div>
            <div id="stFormErrors" class="row"></div>
            <footer class="row">
                <img src="http://localhost:8888/sttvroot/wp-content/themes/sttvsite/i/supertutortv-students.png" />
            </footer>
        </div>
    </div>
</section>
<script>
    document.addEventListener("DOMContentLoaded", function(event) { 
        _st.signup = new _st.signup()
    });
</script>
<?php get_footer('stripped');