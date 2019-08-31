<?php
  /*
  Plugin Name: WJCT Newscast Automator
  Plugin URI: https://github.com/RayHollister/WJCT-Newscast-Automator
  description: A plugin that automatically updates the WJCT Alexa Flash Briefing when the NPR One newscast has been uploaded.
  Version: 0.21
  Author: Ray Hollister
  Author URI: https://rayhollister.com
  License: GPLv2
  License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html

  Copyright 2019 WJCT

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

  */

  // define the plugin director as a constant
  define( 'WJCT_Newscast_Automator_Plugin_Dir', plugin_dir_path(__FILE__) );

  // include the settings file
  require_once( WJCT_Newscast_Automator_Plugin_Dir . 'settings.php' );

  // include the last-modified function
  require_once( WJCT_Newscast_Automator_Plugin_Dir . 'last-modified.php' );

  // The URL of the MP3 file uploaded to NPR One.
  // $mp3url = 'https://media.publicbroadcasting.net/wjct/newscast/newscast.mp3';
  $mp3url = get_option( 'wjct_newscast_mp3url');

  // this function unpublishes the existing newscasts
  function delete_old_newscasts() {
     for( $i = 0; $i < 1; $i++ ) {     // Runs this loop 1 time
         $args = array(
            'post_status' => 'published',
            'cat'         => 1605,        // Use Category ID here.
            'posts_per_page' => 100,    // Lets just do 100 at a time so we don't blow up your server
            'no_found_rows'  => true,   // We don't need to paginate.
            'update_post_meta_cache' => false,  // We don't need post meta either.
     );
      $posts = get_posts( $args );
      if ( !$posts ) return;             // Breaks out of the function if there are no posts.
          foreach ( $posts as $post ) {
          print_r( old_newscasts_change_status( $post->ID ) );

          }
      }
  }

   function old_newscasts_change_status( $post_id ) {
      $updated_post = array();
      $updated_post['ID'] = (int) $post_id;
      $updated_post['post_status'] = 'trash';

      wp_update_post( $updated_post );
        //  return $post_id .' has been deleted <br/>';
      }

  // End of delete old newscasts funtion

  // A function used to programmatically create a post in WordPress.
  // The slug, author ID, and title are defined within the context of the function.
  //
  // @returns -1 if the post was never created, -2 if a post with the same title exists, or the ID
  // of the post if successful.
  //
  // Credit: Tom McFarlin https://tommcfarlin.com/programmatically-create-a-post-in-wordpress/#code

  function programmatically_create_post()
  {
      global $mp3url;

      $recenttimestamp = remote_file_last_modified( $mp3url );
      // Convert numeric to string
      $recenttimestamp = strval($recenttimestamp);

      // Initialize the page ID to -1. This indicates no action has been taken.
      $post_id = -1;

      // Setup the author, slug, category and title for the post
      // User ID 636 is the generic 'WJCT' User
      $author_id = 636;

      // set the slug to the Unix Timestamp
      $slug = $recenttimestamp;

      // Set the title to the most recent updated date time
      // $title = 'My Example Post';
      $title = 'Flash Briefing ' . date("m/d/Y h:i:s A", $recenttimestamp);

      // 1605 is WJCT's 'News Flash' category id
      $category_ids = array(1605);

      // This sets the content of the post to the embed link for the mp3.
      $post_content = '[embed]' . $mp3url . '[/embed]';

      // If the page doesn't already exist, then create it
      if (null == get_page_by_title($title)) {

          // Set the post ID so that we know the post was created successfully
          $post_id = wp_insert_post(
              array(
                'comment_status'	=>	'closed',
                'ping_status'	=>	'closed',
                'post_author'	=>	$author_id,
                'post_content'	=>	$post_content,
                'post_name'	=>	$slug,
                'post_title'	=>	$title,
                'post_category'	=>	$category_ids,
                'post_status'	=>	'publish',
                'post_type' =>	'post'
                )
              );
      // Otherwise, we'll stop
      } else {

  // Arbitrarily use -2 to indicate that the page with the title already exists
          $post_id = -2;
      } // end if
  } // end programmatically_create_post


  // CRON JOB STARTS HERE
  // Credit Jay Versluis
  // https://bit.ly/2HgDfUW

  // unschedule event upon plugin deactivation
  function cronstarter_deactivate()
  {
    // find out when the last event was scheduled
    $timestamp = wp_next_scheduled('WJCT_flash_briefing_automator_cron');
    // unschedule previous event if any
    wp_unschedule_event($timestamp, 'WJCT_flash_briefing_automator_cron');
  }
  register_deactivation_hook(__FILE__, 'cronstarter_deactivate');
  // create a scheduled event (if it does not exist already)
  function cronstarter_activation()
  {
      if (!wp_next_scheduled('WJCT_flash_briefing_automator_cron')) {
          // wp_schedule_event(time(), 'every5minutes', 'WJCT_flash_briefing_automator_cron');
          wp_schedule_event(time(), 'everyminute', 'WJCT_flash_briefing_automator_cron');
      }
  }
  // and make sure it's called whenever WordPress loads
  add_action('wp', 'cronstarter_activation');

  // here's the function we'd like to call with our cron job
  function newscast_checker()
  {
      global $mp3url;
      // get the datetime when the newscast was last updated
      $lastupdate = get_option('lastupdated');

      $recenttimestamp = remote_file_last_modified( $mp3url );
      // Convert numeric to string
      $recenttimestamp = strval($recenttimestamp);

      if ($recenttimestamp != $lastupdate) {
          $recentupdate = date("m/d/Y h:i:s A T", $recenttimestamp);
          // put the old newscasts in the trash
          delete_old_newscasts();
          // create a new flash briefing post
          programmatically_create_post();
          // store the most recent datetime the newscast was updated in the website database
          update_option('lastupdated', $recenttimestamp);
      } else {
          // store the most recent datetime the newscast was updated in the website database
          // update_option('lastupdated', $recenttimestamp);
      }
  }

  // hook that function onto our scheduled event:
  add_action('WJCT_flash_briefing_automator_cron', 'newscast_checker');
  // CUSTOM INTERVALS
  // by default we only have hourly, twicedaily and daily as intervals
  // to add your own, use something like this - the example adds 'weekly'
  // http://codex.wordpress.org/Function_Reference/wp_get_schedules

  // add another interval
  function cron_add_minute($schedules)
  {
      // Adds once every minute to the existing schedules.
      $schedules['everyminute'] = array(
  'interval' => 60,
  'display' => __('Once Every Minute')
  );
      return $schedules;
  }
  add_filter('cron_schedules', 'cron_add_minute');
  // add another interval
  function cron_add_fiveminutes($schedules)
  {
  // Adds once every 5 minutes to the existing schedules.
  $schedules['every5minutes'] = array(
  'interval' => 300,
  'display' => __('Once Every 5 Minutes')
  );
      return $schedules;
  }
  add_filter('cron_schedules', 'cron_add_fiveminutes');

  // add another interval
  function cron_add_fifteenminutes($schedules)
  {
  // Adds once every 15 minutes to the existing schedules.
  $schedules['every15minutes'] = array(
  'interval' => 900,
  'display' => __('Once Every 15 Minute')
  );
  return $schedules;
  }
  add_filter('cron_schedules', 'cron_add_fifteenminutes');

  // more info here:
  // https://bit.ly/31XBQuz
  // https://github.com/versluis
  // THE REST OF THE CODE IS NOT USED FOR THE CRON FUNCTION

  // Add a widget to the dashboard.
  function WJCT_Latest_Newscast_add_dashboard_widget()
  {
      wp_add_dashboard_widget(
          'WJCT_Latest_Newscast_widget',         // Widget slug.
          'Latest Newscast',         // Title.
          'WJCT_Latest_Newscast_widget_function' // Display function.
          );
  }
  add_action('wp_dashboard_setup', 'WJCT_Latest_Newscast_add_dashboard_widget');


  function WJCT_Latest_Newscast_widget_function()
  {
      // global $mp3url;

      newscast_checker();

      echo '<div>';

      // // get the datetime when the newscast was last updated
      // $lastupdate = get_option('lastupdated');
      //
      // $recenttimestamp = remote_file_last_modified( $mp3url );
      // // Convert numeric to string
      // $recenttimestamp = strval($recenttimestamp);
      //
      // // echo '<p>The last update was ' . $lastupdate . '</p>';
      // // echo '<p>The most recent update was ' . $recentupdate . '</p>';
      //
      // // format timestamp and convert to local timezone
      // $recentupdate = date("m/d/Y h:i:s A T", $recenttimestamp);
      //
      // if ($recenttimestamp != $lastupdate) {
      //
      //     echo "<p>The newcast was updated " . $recentupdate . ".<br/>A new flash briefing is being published now.</p>";
      //     echo "<p>The timestamps are " . $recenttimestamp . " (new) and " . $lastupdate . " (old)</p>";
      //     // store the most recent datetime the newscast was updated in the website database
      //     update_option('lastupdated', $recenttimestamp);
      //     // delete_old_newscasts();
      //     // programmatically_create_post();
      // } else {
      //     // store the most recent datetime the newscast was updated in the website database
      //     update_option('lastupdated', $recenttimestamp);
      //     // echo "<p>The newscast was last updated " . $recentupdate . ".</p>";
      //     echo "<p>The newscast was last updated <a href='/" . $recenttimestamp . "' target='_blank'>" . $recentupdate  . "</a>.</p>";
      //     // echo "<p>The timestamps are " . $recenttimestamp . " (new) and " . $lastupdate . " (old)</p>";
      // }
      echo '</div>';
  }
