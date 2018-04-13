<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class MultiUserAdmin {

    public function __construct() {
        add_action( 'personal_options', [ $this, 'user_admin_keygen' ] );
        add_action( 'admin_init', [ $this, 'init' ] );
        add_action( 'admin_menu', [ $this, 'mukey_admin_page' ] );
    }

    public function init() {
        $this->create_mu_roles();

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

    private function create_mu_roles() {
        $master = add_role( 'multi-user_master', 'Multi User Master', [ 'multi-user_master' => true, 'multi-user' => true ] );

        $student = add_role( 'multi-user_student', 'Multi User Student', [ 'multi-user_student' => true, 'multi-user' => true ] );
    }

    public function user_admin_keygen( $user ){
        if ( !user_can( $user->ID, 'multi-user_master' ) ) {
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

    public function mukey_admin_page() {
        add_menu_page(
            'Multi User Keys',     // page title
            'Multi User Keys',     // menu title
            'manage_options',   // capability
            'mu-keys',     // menu slug
            [ $this, 'mukey_render_admin_page' ], // callback function
            'dashicons-admin-network', // icon
            56 // position
        );
    }

    public function mukey_render_admin_page() {
        global $title;

        $keys = json_decode(MultiUser::getAllKeys(),true);
        $keyss = '';
        foreach ($keys as $key => $val) {
            $val['id'] = $key;
            $keyss .= '<span>'.json_encode($val).'</span><br>';
        }

        $courses = get_posts(['numberposts' => -1,'post_type' => 'courses']);
        $cselect = '<option value="" disabled selected>Select course</option>';
        foreach ( $courses as $course ) {
            $cselect .= "<option value='{$course->ID}'>{$course->post_title}</option>";
        }

        $users = get_users( [ 'role__in' => [ 'multi-user_master', 'administrator' ] ] );
        $uselect = '<option value="" disabled selected>Select master</option>';
        foreach ( $users as $user ) {
            $uselect .= "<option value='{$user->ID}'>{$user->user_email}</option>";
        }
        $html = <<<HTML
        <style type="text/css">
            header h1 {
                font-size: 42px;
            }
            .fullwidth {
                width: 100%;
                padding: 1em;
            }
            #keygen > * {
                margin-right: 1em
            }
        </style>
        <div class="wrap">
            <header class="mukey-header fullwidth"><h1>$title</h1></header>
            <div id="keygen" class="fullwidth">
                <input name="qty" type="text" placeholder="Qty" />
                <select name="master_user">$uselect</select>
                <select name="course_id">$cselect</select>
                <button class="button button-primary" type="submit">Generate keys</button>
            </div>
            <div id="generated-keys" class="fullwidth">
                $keyss
            </div>
        </div>
        <script>
            $('#keygen > .button').on('click', function(e){
                e.preventDefault();
                var data = {
                    qty : $('input[name=qty]','#keygen').val(),
                    user : $('select[name=master_user]','#keygen').val(),
                    course : $('select[name=course_id]','#keygen').val(),
                    email : $('select[name=master_user] option:selected','#keygen').text()
                }
                _st.request({
                    route : stajax.rest.url+'/multi-user',
                    method : 'PUT',
                    cdata : data,
                    headers : {
                        'X-WP-Nonce' : stajax.rest.nonce,
                    },
                    success : function(d) {
                        console.log(d)
                    },
                    error : function(x) {
                        console.log(x)
                    }
                })
            })
        </script>
HTML;
        print $html;
    }

}
new MultiUserAdmin;