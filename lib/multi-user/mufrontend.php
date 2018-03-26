<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class MultiUserFrontend {

    public function __construct() {
        add_action( 'personal_options', [ $this, 'user_admin_keygen' ] );
        add_action( 'init', [ $this, 'create_mu_role' ] );
        add_action( 'rest_api_init', [ $this, 'mukey_endpoint' ] );
        add_action( 'mu_signup_after_content', [ $this, 'mu_purchase_form' ] );
    }

    public function mukey_endpoint() {
        register_rest_route( STTV_REST_NAMESPACE , '/multi-user', [
            [
                'methods' => 'GET',
                'callback' => function( WP_REST_Request $request ){
                    $key = $request->get_param( 'key' );

                    return (new MultiUserPermissions( $key ))->keygen()->get_current_key();

                },
                'permission_callback' => function(){
                    return current_user_can( 'multi_user' );
                },
                'args' => [
                    'key' => [
                        'required' => true,
                        'type' => 'string',
                        'description' => 'Can be a mukey or user id'
                    ]
                ]
            ]
        ]);
    }

    public function create_mu_role() {
        $role = add_role( 'multi_user', 'Multi User', [ 'multi_user'=>true ] );
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

    public function mu_purchase_form(){
        get_template_part('templates/multi-user/mu_purchase_form');
    }

}
new MultiUserFrontend;