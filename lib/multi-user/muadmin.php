<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class MultiUserAdmin {

    public function __construct() {
        add_action( 'personal_options', [ $this, 'user_admin_keygen' ] );
        add_action( 'admin_init', [ $this, 'init' ] );
    }

    public function init() {
        $this->create_mu_role();

        /* $price_table = [
            10789 => range(0,10)
        ];
        $ranges = [
            21900 => range(3,5),
            19900 => range(6,30),
            14900 => range(31,99),
            11900 => range(100,500)
        ];
        foreach ($ranges as $k => $v) {
            foreach ($v as $i) {
                $price_table[10789][$i] = $k;
            }
        }
        update_option( 'sttv_mu_pricing_table', $price_table ); */
    }

    private function create_mu_role() {
        $role = add_role( 'multi_user', 'Multi User', [ 'multi_user' => true ] );
        if ( null !== $role ){
            $admin = get_role( 'administrator' );
            return $admin->add_cap( 'multi_user', true );
        }
    }

    public function user_admin_keygen( $user ){
        if ( !user_can( $user->ID, 'multi_user' ) ) {
            return false;
        }
        $mu = new MultiUserPermissions( $user->ID );
        $key = $mu->get_key_by_id();
        ?>
        <style type="text/css">
            #mukey_cont {
                font-family: monospace;
            }
            #multi_user_key {
                display: block;
                margin-bottom: 1em;
            }
        </style>
        <tr class="user-comment-shortcuts-wrap">
            <th scope="row"><?php _e( 'Multi User Permission Key' ); ?></th>
            <td>
                <div id="mukey_cont">
                    <span id="multi_user_key" <?php echo $key ? 'data-key"'.$key.'"' : ''; ?>><?php echo $key ?: 'No multi-user permission keys found for this user'; ?></span>
                    <button id="mukey_submit" class="button button-primary">Generate new key</button>
                </div>
            </td>
        </tr>
        <script>
            $('#mukey_submit').on('click',function(e){
                e.preventDefault()
                var t = $('#multi_user_key')
                if ( confirm("Are you sure you want to generate a new key?") ) {
                    var mukey = t.attr('data-key') || stajax.rest.ID

                    _st.request({
                        route : stajax.rest.url+'/multi-user?key='+mukey,
                        headers : {
                            'X-WP-Nonce' : stajax.rest.nonce
                        },
                        success : function(data) {
                            t.fadeOut(250,function(){
                                t.text(data)
                            })
                            .fadeIn(250)
                        },
                        error : function(x){
                            console.log(x)
                        }
                    })
                }
            })
        </script>
    <?php }

}
new MultiUserAdmin;