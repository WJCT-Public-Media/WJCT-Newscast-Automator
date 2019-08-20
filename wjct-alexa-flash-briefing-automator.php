<?php
   /*
   Plugin Name: WJCT Alexa Flash Briefing Automator
   Plugin URI: https://www.wjct.org
   description: A plugin that automatically updates the WJCT Alexa Flash Briefing when the NPR One newscast has been uploaded.
   Version: 0.02
   Author: Ray Hollister
   Author URI: https://rayhollister.com
   License:
   License URI:
   Text Domain: wjct-alexa-flash-briefing-automator
   */

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
    echo '<div>';

    // get the datetime when the newscast was last updated
    $lastupdate = get_option( 'lastupdated' );
    echo '<p>The last update was ' . $lastupdate . '</p>';

    // The URL of the MP3 file uploaded to NPR One.
    // Find the URL in https://stationconnect.org/stations/447 (replace 447 with your NPR station ID)
    $url = 'https://media.publicbroadcasting.net/wjct/newscast/newscast.mp3';

    $headers = get_headers($url);

    // Show the entire PHP server header of the file
    // https://www.php.net/manual/en/function.get-headers.php
    /*
    print_r(get_headers($url));
    echo '</p>';
    */

    // Show just the header that we need, the modified date/time
    $recentupdate = substr($headers[7], 15, 29);
    // convert string to Unix Timestamp
    $recentupdate = strtotime($recentupdate);
    // format timestamp and convert to local timezone
    $recentupdate = date("m/d/Y H:i:s A T", $recentupdate);

    // store the most recent datetime the newscast was updated in the website database
    update_option( 'lastupdated', $recentupdate );

    // $date = $date->format('m/d/Y H:i:s A T');
    echo '<p>The most recent update was ';
    echo $recentupdate;
    echo '</p>';
    echo '</div>';
   }

?>
