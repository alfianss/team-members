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
        add_meta_box( 'movie_review_meta_box',
            'Other Information',
            array($this,'display_team_members_meta_box'),
            'team_members', 'normal', 'high'
        );
    }

    public function display_team_members_meta_box( $team_members ) {
        // Retrieve current name of the Director and Movie Rating based on review ID
        $tm_position = esc_html( get_post_meta( $team_members->ID, 'tm_position', true ) );
        $tm_email    = esc_html( get_post_meta( $team_members->ID, 'tm_email', true ) );
        $tm_phone    = esc_html( get_post_meta( $team_members->ID, 'tm_phone', true ) );
        $tm_url      = esc_html( get_post_meta( $team_members->ID, 'tm_url', true ) );
        $tm_image    = get_post_meta( $team_members->ID, 'movie_rating', true );

        echo "<table>
                <tr style='width:100%;'>
                    <td style='width:50%;'>Position</td>
                    <td style='width:50%;'><input type='text' name='tm_position'></td>
                </tr>
                <tr style='width:100%;'>
                    <td style='width:50%;'>Email</td>
                    <td style='width:50%;'><input type='text' name='tm_email'></td>
                </tr>
                <tr style='width:100%;'>
                    <td style='width:50%;'>Phone</td>
                    <td style='width:50%;'><input type='text' name='tm_phone'></td>
                </tr>
                <tr style='width:100%;'>
                    <td style='width:50%;'>Website</td>
                    <td style='width:50%;'><input type='text' name='tm_url'></td>
                </tr>
                <tr style='width:100%;'>
                    <td style='width:50%;'>Image</td>
                    <td style='width:50%;'>
                        <img src=''>
                        <input type='File' name='tm_images'>
                    </td>
                </tr>
                </table>";
    }


    public function add_team_members_fields( $team_members_id, $team_members ) {
        // Check post type for movie reviews
        if ( $team_members->post_type == 'team_members' ) {
            // Store data in post meta table if present in post data
            if ( isset( $_POST['tm_position'] ) && $_POST['tm_position'] != '' ) {
                update_post_meta( $team_members_id, 'tm_position', $_POST['tm_position'] );
            }
            if ( isset( $_POST['tm_email'] ) && $_POST['tm_email'] != '' ) {
                update_post_meta( $team_members_id, 'tm_email', $_POST['tm_email'] );
            }
            if ( isset( $_POST['tm_position'] ) && $_POST['tm_position'] != '' ) {
                update_post_meta( $team_members_id, 'tm_position', $_POST['tm_position'] );
            }
            if ( isset( $_POST['tm_position'] ) && $_POST['tm_position'] != '' ) {
                update_post_meta( $team_members_id, 'tm_position', $_POST['tm_position'] );
            }
            if ( isset( $_POST['tm_position'] ) && $_POST['tm_position'] != '' ) {
                update_post_meta( $team_members_id, 'tm_position', $_POST['tm_position'] );
            }
        }
    }

}

$team_members = new Team_Members();

add_action( 'init', array($team_members, 'create_team_members') );
add_action( 'admin_init', array($team_members, 'team_members_admin') );
add_action( 'save_post', array($team_members, 'add_movie_review_fields', 10, 2) );