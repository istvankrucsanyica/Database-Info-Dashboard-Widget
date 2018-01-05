<?php

  /**
   *
   * Plugin Name:       Database Info Dashboard Widget
   * Plugin URI:        https://github.com/istvankrucsanyica
   * Description:       Database Info Dashboard Widget
   * Version:           1.0.0
   * Author:            Istvan Krucsanyica at Kreatív Vonalak
   * Author URI:        https://github.com/istvankrucsanyica
   * Text Domain:       database-info-widget
   * Domain Path:       /languages
   * License:           GPLv2
   * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
   *
   * 2018, Istvan Krucsanyica at Kreatív Vonalak (email : istvan.krucsanyica@gmail.com)
   *
   * This program is free software; you can redistribute it and/or modify
   * it under the terms of the GNU General Public License version 2,
   * as published by the Free Software Foundation.
   *
   * ou may NOT assume that you can use any other version of the GPL.
   *
   * This program is distributed in the hope that it will be useful,
   * but WITHOUT ANY WARRANTY; without even the implied warranty of
   * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   * GNU General Public License for more details.
   *
   * The license for this software can likely be found here:
   * http://www.gnu.org/licenses/gpl-2.0.html
   */

  // If this file is called directly, abort.
  if ( ! defined( 'WPINC' ) ) {

    die;

  }

  if ( ! class_exists( 'Databaseinfowidget_class' ) ) {

    class Databaseinfowidget_class {

      public function __construct() {

        if ( is_admin() ) {

          add_action( 'wp_dashboard_setup', array( $this, 'add_to_dashboard_widgets' ) );
          add_action( 'admin_footer', array( $this, 'render_style' ), 10, 1 );

        }

      }

      public function add_to_dashboard_widgets() {

        global $wp_meta_boxes;

        wp_add_dashboard_widget( 'databaseinfo_widget', __('Adatbázis információk', 'database-info-widget'), array( $this, 'render_serverinfo_dashboard' ) );

      }

      public function render_serverinfo_dashboard() {


        echo '
        <table id="database-info-table">
          <tr>
            <td><strong>'. __('Változatok', 'database-info-widget') .':</strong><br/><small>'.$this->get_posts_revisions().'</small></td>
            <td><strong>'. __('Vázlatok', 'database-info-widget') .':</strong><br/><small>'.$this->get_posts_drafts().'</small></td>
          </tr>
          <tr>
            <td><strong>'. __('Lomtárban', 'database-info-widget') .':</strong><br/><small>'.$this->get_posts_trash().'</small></td>
            <td><strong>'. __('Auto vázlatok', 'database-info-widget') .':</strong><br/><small>'.$this->get_posts_autodrafts().'</small></td>
          </tr>
          <tr>
            <td colspan="2"><strong>'. __('Árva posztmeták', 'database-info-widget') .':</strong><br/><small>'.$this->get_orphan_postmeta().'</small></td>
          </tr>
        </table>';

      }

      public function render_style() {

        echo '<style>
          #databaseinfo_widget .inside { padding: 0; margin-top: 0;}
          #databaseinfo_widget .hndle { border-bottom: 1px solid #e5e5e5; }
          #database-info-table { width: 100%; border: 0; border-collapse: collapse; }
          #database-info-table td { padding: 5px 7px; font-size: 13px; width: 50%; vertical-align: top; }
          #database-info-table td strong { font-weight: 700; color: #2e4053; }
          #database-info-table td small { color: #d35400; }
          #database-info-table tr td { border-bottom: 1px solid #e5e5e5; }
          #database-info-table tr td:nth-child(1) { border-right: 1px solid #e5e5e5; }
          #database-info-table tr:last-child td { border-bottom: 0; }
        </style>';

      }

      private function get_posts_revisions() {

        global $wpdb;

        $query = "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type='revision'";

        return $wpdb->get_var( $query );

      }

      private function get_posts_drafts() {

        global $wpdb;

        $query = "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status='draft'";

        return $wpdb->get_var( $query );

      }

      private function get_posts_autodrafts() {

        global $wpdb;

        $query = "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status='auto-draft'";

        return $wpdb->get_var( $query );

      }

      private function get_posts_trash() {

        global $wpdb;

        $query = "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status='trash'";

        return $wpdb->get_var( $query );

      }

      private function get_orphan_postmeta() {

        global $wpdb;

        $query = "SELECT COUNT(*) FROM {$wpdb->postmeta} LEFT JOIN {$wpdb->posts} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id WHERE {$wpdb->posts}.ID IS NULL";

        return $wpdb->get_var( $query );

      }

    }

  }

  add_action('plugins_loaded', 'database_info_widget_init');

  function database_info_widget_init() {

    if ( current_user_can( 'administrator' ) ) {

      new Databaseinfowidget_class();

    }

  }
