<div id="stMailinglistWrapper" class="col s12 l6 offset-l3">
    <div id="stMailinglistOverlay" class="stOverlay"></div>
    <div id="stMailinglistWrapperInner" class="row">
        <div id="stMailinglistForm" class="col s12">
            <div class="input-field required col s12 m6 st-input-half-left">
                <input class="browser-default validate" autocomplete="off" type="text" name="st-firstname" placeholder="First Name" onblur="_st.subscribe.prepare(this)" required />
            </div>
            <div class="input-field required col s12 m6 st-input-half-right">
                <input class="browser-default validate" autocomplete="off" type="text" name="st-lastname" placeholder="Last Name" onblur="_st.subscribe.prepare(this)" required/>
            </div>
            <div class="input-field required col s12">
                <input class="browser-default validate email" autocomplete="off" type="email" name="st-email" placeholder="Email Address" onblur="_st.subscribe.prepare(this)" required/>
            </div>
        </div>
        <div id="stMailinglistButtons" class="col s12">
            <button class="stMailinglistSubmit stMailinglistBtn pmt-button btn waves-effect waves-dark" onclick="_st.subscribe.submit()">Subscribe</button>
        </div>
        <div id="stFormErrors" class="col s12"><p class="error"></p></div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function(event) { 
        _st.subscribe = new _st.subscribe()
    });
</script>