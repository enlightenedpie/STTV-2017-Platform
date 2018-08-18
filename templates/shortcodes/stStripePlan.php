<?php 
$contents = explode(',',$content);
$ids = explode( ',', $atts['id'] );

$highlight = false;
if (in_array('highlight',$atts)) $highlight = 'highlight';

$databind = [];
foreach( $ids as $id ) $databind[] = $id;

$cols = '';

switch($atts['columns']) :
    case '1':
        $cols = '12';
        break;
    case '3':
        $cols = '4';
        break;
    case '4':
        $cols = '3';
        break;
    case '6':
        $cols = '2';
        break;
    default :
        $cols = '6';
endswitch; ?>
<div class="row">
    <div class="col s12 m8 l6 xl4 offset-m2 offset-l3 offset-xl4 sttv-sales-table-wrapper <?php echo ($highlight) ? $highlight.' z-depth-4': ''; ?>">
        <table id="sttv-sales-table-<?php echo $atts['plan']; ?>" class="sttv-sales-table centered">
            <caption class="<?php echo ($highlight) ?: ''; ?>"><a data-course='<?php echo base64_encode(json_encode($databind)); ?>' onclick="_st.modal.init('checkout',this)" class="payment-launcher" >$<?php _e(str_replace('.00','',$atts['price'])); ?></a></caption>
                <?php /*?><tr>
                    <td>
                        <span class="sttv-course-price"><?php echo $atts['price']; ?></span>
                        <?php
                            echo (!empty($atts['length'])) ? ' / '.$atts['length'] : '';
                        ?>
                    </td>
                </tr><?php */?>
            <?php

            foreach ($contents as $con) :
                $con = str_replace('{comma}',',',$con);
                $high = false;
                if (stristr($con,'(highlight)')) {
                    $high = 'txt-highlight';
                    $con = str_replace('(highlight)','',$con);
                }

                ?><tr><td><?php echo ($high) ? '<span class="'.$high.'">'.$con.'</span>' : $con; ?></td></tr><?php

            endforeach;

        ?>
        <tr>
            <td>
                <a data-course='<?php echo base64_encode(json_encode($databind)); ?>' onclick="_st.modal.init('checkout',this)" class="payment-launcher pmt-button btn waves-effect waves-light"> Sign up now!</a>
            </td>
        </tr>
        </table>
    </div>
</div>
<?php 