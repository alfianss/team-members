<?php
/*
Plugin Name: Team Members
Plugin URI: http://team-members.com/
Description: Plugin Team Members
Version: 1.0
Author: Alfian SS
Author URI: http://team-members.com/
License: GPLv2
*/

class Team_Members {

    public function create_team_members() {
        register_post_type( 'team_members',
            array(
                'labels' => array(
                    'name' => 'Team Members',
                    'singular_name' => 'Team Members',
                    'add_new' => 'Add New',
                    'add_new_item' => 'Add New Team Members',
                    'edit' => 'Edit',
                    'edit_item' => 'Edit Team Members',
                    'new_item' => 'New Team Members',
                    'view' => 'View',
                    'view_item' => 'View Team Members',
                    'search_items' => 'Search Team Members',
                    'not_found' => 'No Team Members found',
                    'not_found_in_trash' => 'No Team Members found in Trash',
                    'parent' => 'Parent Team Members'
                ),
     
                'public' => true,
                'menu_position' => 15,
                'supports' => array( 'title', 'editor', 'thumbnail' ),
                'taxonomies' => array( '' ),
                'menu_icon' => 'dashicons-list-view',
                'has_archive' => true
            )
        );
    }

    public function team_members_admin() {
        add_meta_box( 'team_members_meta_box',
            'Other Information',
            array($this, 'display_team_members_meta_box'),
            'team_members', 'normal', 'high'
        );
    }
    
    public function add_team_members_fields( $team_members_id, $team_members ) {
        // Check post type for team members
        if ( $team_members->post_type == 'team_members' ) {
            // Store data in post meta table if present in post data
            if ( isset( $_POST['tm_position'] ) && $_POST['tm_position'] != '' ) {
                update_post_meta( $team_members_id, 'tm_position', $_POST['tm_position'] );
            }
            if ( isset( $_POST['tm_email'] ) && $_POST['tm_email'] != '' ) {
                update_post_meta( $team_members_id, 'tm_email', $_POST['tm_email'] );
            }
            if ( isset( $_POST['tm_phone'] ) && $_POST['tm_phone'] != '' ) {
                update_post_meta( $team_members_id, 'tm_phone', $_POST['tm_phone'] );
            }
            if ( isset( $_POST['tm_url'] ) && $_POST['tm_url'] != '' ) {
                update_post_meta( $team_members_id, 'tm_url', $_POST['tm_url'] );
            }
            if ( isset( $_POST['tm_images'] ) && $_POST['tm_images'] != '' ) {
                update_post_meta( $team_members_id, 'tm_images', $_POST['tm_images'] );

                if( ! isset( $_FILES ) || empty( $_FILES ) || ! isset( $_FILES['my_files'] ) )
                return;

                if ( ! function_exists( 'wp_handle_upload' ) ) {
                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                }

                $files = $_FILES['tm_images'];
                foreach ($files['name'] as $key => $value) {
                    if ($files['name'][$key]) {
                        $uploadedfile = array(
                            'name'     => $files['name'][$key],
                            'type'     => $files['type'][$key],
                            'tmp_name' => $files['tmp_name'][$key],
                            'error'    => $files['error'][$key],
                            'size'     => $files['size'][$key]
                        );
                        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

                        if ( $movefile && !isset( $movefile['error'] ) ) {
                            $ufiles = get_post_meta( $team_members_id, 'tm_images', true );
                            if( empty( $ufiles ) ) $ufiles = array();
                            $ufiles[] = $movefile;
                            // update_post_meta( $post_id, 'my_files', $ufiles );

                        }
                    }
                }
            }
        }
    }

    public function display_team_members_meta_box( $team_members ) {
        // Retrieve current name of the Director and Movie Rating based on review ID
        $tm_position = esc_html( get_post_meta( $team_members->ID, 'tm_position', true ) );
        $tm_email    = esc_html( get_post_meta( $team_members->ID, 'tm_email', true ) );
        $tm_phone    = esc_html( get_post_meta( $team_members->ID, 'tm_phone', true ) );
        $tm_url      = esc_html( get_post_meta( $team_members->ID, 'tm_url', true ) );
        $tm_image    = get_post_meta( $team_members->ID, 'tm_images', true );        
        
        echo "<table>
                <tr style='width:100%;'>
                    <td style='width:50%;'>Position</td>
                    <td style='width:50%;'><input type='text' name='tm_position' value='". $tm_position ."'></td>
                </tr>
                 <tr style='width:100%;'>
                    <td style='width:50%;'>Email</td>
                    <td style='width:50%;'><input type='text' name='tm_email' value='". $tm_email ."'></td>
                </tr>
                <tr style='width:100%;'>
                    <td style='width:50%;'>Phone</td>
                    <td style='width:50%;'><input type='text' name='tm_phone' value='". $tm_phone ."'></td>
                </tr>
                <tr style='width:100%;'>
                    <td style='width:50%;'>Website</td>
                    <td style='width:50%;'><input type='text' name='tm_url' value='". $tm_url ."'></td>
                </tr>
                <tr style='width:100%;'>
                    <td style='width:50%;'>Image</td>
                    <td style='width:50%;'>";
                    if(isset($tm_image) && $tm_image != '') {
                        echo "<input type='text' name='tm_images' value='".$tm_image."'>";
                    } else {
                        echo "<input type='file' name='tm_images' >";
                    }
                    "</td>
                </tr>
                </table>";
    }

    public function tm_shortcode() {
        $mypost = array( 'post_type' => 'team_members', );
        $loop = new WP_Query( $mypost );

        $html = "<div class='row col-md-12' style='width: 100%; '>";
        while ( $loop->have_posts() ) : $loop->the_post();;
            $html .= "<div class='col-md-3' style='float:left; padding: 0 5px;'>";
            $html .= "<img src='".esc_html( get_post_meta( get_the_ID(), 'tm_images', true ) )."'>";
            $html .= "<span>".esc_html( get_post_meta( get_the_ID(), 'tm_position', true ) )."</span><br>";
            $html .= "<span>".esc_html( get_post_meta( get_the_ID(), 'tm_email', true ) )."</span><br>";
            $html .= "<span>".esc_html( get_post_meta( get_the_ID(), 'tm_phone', true ) )."</span><br>";
            $html .= "<span>".esc_html( get_post_meta( get_the_ID(), 'tm_url', true ) )."</span><br>";
            $html .= "</div>";
        endwhile;
        wp_reset_query();
        $html .= "</div>";

        echo $html;
    }

    public function custom_team_members( $atts = array() ) {

        // set up default parameters
        $arr_atts = shortcode_atts(array(
            'position'  => 1,
            'email'     => 1,
            'phone'     => 1,
            'website'   => 1,
            'images'    => 1
        ), $atts);

        $mypost = array( 'post_type' => 'team_members', );
        $loop = new WP_Query( $mypost );

        $html = "<div class='row col-md-12' style='width: 100%; '>";
        while ( $loop->have_posts() ) : $loop->the_post();;
            $html .= "<div class='col-md-3' style='float:left; padding: 0 5px;'>";
            if($arr_atts['images'] == 1) {
                $html .= "<img src='".esc_html( get_post_meta( get_the_ID(), 'tm_images', true ) )."'>";
            }
            if($arr_atts['position'] == 1) {
                $html .= "<span>".esc_html( get_post_meta( get_the_ID(), 'tm_position', true ) )."</span><br>";
            }
            if($arr_atts['email'] == 1) {
                $html .= "<span>".esc_html( get_post_meta( get_the_ID(), 'tm_email', true ) )."</span><br>";
            }
            if($arr_atts['phone']) {
                $html .= "<span>".esc_html( get_post_meta( get_the_ID(), 'tm_phone', true ) )."</span><br>";
            }
            if($arr_atts['website'] == 1) {
                $html .= "<span>".esc_html( get_post_meta( get_the_ID(), 'tm_url', true ) )."</span><br>";
            }
            $html .= "</div>";
        endwhile;
        wp_reset_query();
        $html .= "</div>";

        echo $html;
    }

    
}

$team_members = new Team_Members();

add_action( 'init', array($team_members, 'create_team_members') );
add_action( 'admin_init', array($team_members, 'team_members_admin') );
add_action( 'save_post', array($team_members, 'add_team_members_fields'), 10, 2 );

add_shortcode( 'team_members_sc', array($team_members,'tm_shortcode') );
add_shortcode( 'sc_tm_custom', array($team_members,'custom_team_members') );