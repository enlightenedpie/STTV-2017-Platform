<?php

    $qty = range(3,100);
    $qhtml = '';
    foreach ( $qty as $q ){
        $qhtml .= '<option value="'.$q.'">'.$q.'</option>';
    }

?>
<div id="mu_form_wrapper" class="col s12">
    <div class="row">
        <div id="mu_form_inner" class="col s12 m6 l4 offset-m3 offset-l4">
            <div class="input-field col s12">
                <input id="mukey" class="validate" data-type="mukey" name="mukey" type="text" required/>
                <label data-error="Invalid invitation code" for="mukey">Invitation Code</label>
            </div>
            <div class="input-field col s12">
                <input id="email" class="validate" data-type="email" name="email" type="email" required/>
                <label data-error="Invalid email address" for="email">Email Address</label>
            </div>
            <div class="input-field col s12 m9">
                <select class="browser-default validate" name="sttv_course_id" required>
                    <option value disabled selected>Course...</option>
                    <?php
                        $courses = new WP_Query([
                            'post_type' => 'courses',
                            'posts_per_page' => -1
                        ]);
                        if ( $courses->have_posts() ) :
                            while ( $courses->have_posts() ) : $courses->the_post(); ?>
                                <option value="<?php the_id(); ?>"><?php the_title(); ?></option>
                            <?php endwhile;
                        endif;
                        wp_reset_postdata();
                    ?>
                </select>
            </div>
            <div class="input-field col s12 m3">
                <select class="browser-default validate" name="qty" required>
                    <option value disabled selected>Qty...</option>
                    <?php print $qhtml; ?>
                </select>
            </div>
            <div class="col s12">
                <button type="submit" class="signup-submit button-wide z-depth-1 waves-effect waves-light" disabled>Buy Bulk Licenses</button>
            </div>
        </div>
    </div>
</div>