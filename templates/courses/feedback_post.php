<?php 
        //$feedback = get_posts(array('post_type'=>'feedback','author'=>$user->ID));
        $limited = get_transient('sttv_cfbrp:'.$user->ID)?'disabled':'';
        $ph = $limited ? "You are only allowed one feedback per day" : "Enter your feedback here.";
?>
<div id="course-feedback">
    <h1>Feedback</h1>
    <div class="col s12"><span>Drop us a line if you catch any mistakes, have suggestions for new content, or would just like to let us know how we're helping you get a better score! If you'd like to rate us so future students can see how great (or not) the course is, exit this window and click on "Rate This Course". P.S. this is just between us... no one else will see your feedback but the fine folks here at SupertutorTV.</span></div>
    <div class="col s12" id="feedback-post-form">
        <div class="overlay"></div>
        <textarea <?php echo $limited; ?> placeholder="<?php echo $ph; ?>" id="feedback-content"></textarea>
        <div class="feedback-submit-container center-align"><a class="feedback-submit-button btn <?php echo $limited; ?>" href="#!"><strong>Post Feedback</strong></a></div>
    </div>
    <div id="feedback-container"></div>
</div>