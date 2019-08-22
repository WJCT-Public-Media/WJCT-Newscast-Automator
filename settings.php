<?php

// WJCT Newscast Automator Plugin Settings Page

// Settings START HERE

// Register the settings
function wjct_newscast_register_settings() {
  add_option( 'wjct_newscast_mp3url', '');
  register_setting( 'wjct_newscast_options_group', 'wjct_newscast_mp3url', 'wjct_newscast_callback' );
  add_option( 'wjct_newscast_interval', '60');
  register_setting( 'wjct_newscast_options_group', 'wjct_newscast_interval', 'wjct_newscast_callback' );
  add_option( 'wjct_newscast_authorID', '');
  register_setting( 'wjct_newscast_options_group', 'wjct_newscast_authorID', 'wjct_newscast_callback' );
  add_option( 'wjct_newscast_categoryID', '');
  register_setting( 'wjct_newscast_options_group', 'wjct_newscast_categoryID', 'wjct_newscast_callback' );
  add_option( 'wjct_newscast_titleprefix', '');
  register_setting( 'wjct_newscast_options_group', 'wjct_newscast_titleprefix', 'wjct_newscast_callback' );
  add_option( 'wjct_newscast_deleteprevious', '');
  register_setting( 'wjct_newscast_options_group', 'wjct_newscast_deleteprevious', 'wjct_newscast_callback' );

}
add_action( 'admin_init', 'wjct_newscast_register_settings' );

// Create the options page
function wjct_newscast_register_options_page() {
  add_options_page('WJCT Newscast Automator', 'WJCT Newscast Automator Settings', 'manage_options', 'wjct_newscast', 'wjct_newscast_options_page');
}
add_action('admin_menu', 'wjct_newscast_register_options_page');

// And now, the options page content
function wjct_newscast_options_page()
{
?>
  <div>
  <?php screen_icon(); ?>
  <h2>WJCT Newscast Automator Settings</h2>
  <p>This form doesn't do anything yet, except save the option settings.</p>
  <form method="post" action="options.php">
  <?php settings_fields( 'wjct_newscast_options_group' ); ?>
  <table class="form-table">
  <tr valign="top">
  <th scope="row"><label for="wjct_newscast_mp3url">Newcast MP3 URL</label></th>
  <td><input style="width: 500px;" type="text" id="wjct_newscast_mp3url" name="wjct_newscast_mp3url" value="<?php echo get_option('wjct_newscast_mp3url'); ?>" />
    <p><em>What is your station's Newscast URL for NPR One? It is located in <a href="https://stationconnect.org/" target="_blank">StationConnect</a> under your station's call letters.</em></p>
  </td>
  </tr>
  <tr valign="top">
  <th scope="row"><label for="wjct_newscast_interval">Interval to run <br/>Newscast Automator</label></th>
  <td><input style="width: 50px;" type="text" id="wjct_newscast_interval" name="wjct_newscast_interval" value="<?php echo get_option('wjct_newscast_interval'); ?>" />
    <p><em>How often, in minutes, should the Newscast Automator run? The default is 60.</em></p>
  </td>
  </tr>
  <tr valign="top">
  <th scope="row"><label for="wjct_newscast_authorID">Newscast Author</label></th>
  <td>
    <?php
      $args = array(
      // 'exclude'                 => '1',
      'selected'                => get_option('wjct_newscast_authorID'),
      'name'                    => 'wjct_newscast_authorID',
      'who'                     => 'authors'
     );
     wp_dropdown_users($args);
    ?>
    <p><em>Whose byline should be on the Newscasts? Since this cannot be changed easily, it is recommended to be assigned to a generic user like Newsteam or Station</em></p>
  </td>
  </tr>

  <tr valign="top">
  <th scope="row"><label for="wjct_newscast_categoryID">Category</label></th>
  <td>
    <?php
      $args = array(
      // 'exclude'                 => '1',
      'selected'                => get_option('wjct_newscast_categoryID'),
      'name'                    => 'wjct_newscast_categoryID',
      'who'                     => 'category'
     );
     wp_dropdown_categories($args);
    ?>
    <p><em>Which Category do you want Newscasts in? This should be a unique category that is only used for the Alexa News Flash RSS Feed. <a href="/wp-admin/edit-tags.php?taxonomy=category">Create a new category</a> if you haven't already set one up for your News Flash.</em></p>
  </td>
  </tr>
  <tr valign="top">
  <th scope="row"><label for="wjct_newscast_titleprefix">Title Prefix</label></th>
  <td><input style="width: 500px;" type="text" id="wjct_newscast_titleprefix" name="wjct_newscast_titleprefix" value="<?= get_option('wjct_newscast_titleprefix'); ?>" />
    <p><em>What do you want the title of each newscast post to begin with? e.g. "Flash Briefing", "Newscast", etc. The post title will always end with the date and time of the mp3. e.g. "Flash Briefing 8/20/2019 6:30 a.m."</em></p>
  </td>
  </tr>

  <? $options = get_option( 'wjct_newscast_deleteprevious' );
  if( !isset( $options['wjct_newscast_deleteprevious'] ) ) $options['wjct_newscast_deleteprevious'] = 0;
  ?>

  <tr valign="top">
  <th scope="row"><label for="wjct_newscast_deleteprevious">Delete Previous Newscast Posts?</label></th>
  <td>
    <?php
    if ( get_option('wjct_newscast_deleteprevious') == true ) { $display = 'checked'; }
    else { $display = ''; }
    update_option( 'wjct_newscast_deleteprevious', $display );
    ?>

    <input type="checkbox" id="wjct_newscast_deleteprevious" name="wjct_newscast_deleteprevious" value="1" <?php echo get_option('wjct_newscast_deleteprevious'); ?> />
    <p><em>Do you want the Newscast Automator to delete previous Newscast posts when it publishes a new post? This prevents Alexa from receiving multiple copies of the latest newscast.</em></p>
  </td>
  </tr>

  </table>
  <?php  submit_button(); ?>
  </form>
  </div>

<?php
}

// Settings END HERE
