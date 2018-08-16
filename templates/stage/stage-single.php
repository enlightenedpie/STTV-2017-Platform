<?php $ytlink = get_post_meta(get_the_ID(),'yt_link',true);

if (!empty($ytlink)) { ?>
    <div class="sttv-embed-video col s12 xl6 push-xl6">
        <iframe class="youtube-player" width="1920" height="1080" type="text/html" src="https://www.youtube.com/embed/<?php print $ytlink; ?>?version=3&rel=1&fs=1&autohide=2&showsearch=0&showinfo=1&iv_load_policy=1&wmode=transparent&playsinline=1" allowfullscreen="true" style="border:0;"></iframe>
    </div>
<?php } else { ?>
    <div id="cover-img" class="col s12 xl6 push-xl6" style="background-image: url(<?php print get_the_post_thumbnail_url( get_the_ID(), 'full' ); ?>); background-position: 49% 29% !important"></div>
<?php }

sttv_get_template('title');