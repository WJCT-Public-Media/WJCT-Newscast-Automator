<?php
   /*
   Plugin Name: WJCT Alexa Flash Briefing Automator
   Plugin URI: https://www.wjct.org
   description: A plugin that automatically updates the WJCT Alexa Flash Briefing when the NPR One newscast has been uploaded.
   Version: 0.01
   Author: Ray Hollister
   Author URI: https://rayhollister.com
   License:
   License URI:
   Text Domain: wjct-alexa-flash-briefing-automator


    Add a widget to the dashboard.
   */

   function WJCT_Latest_Newscast_add_dashboard_widget()
   {
       wp_add_dashboard_widget(
           'WJCT_Latest_Newscast_widget',         // Widget slug.
                    'Latest Newscast',         // Title.
                    'WJCT_Latest_Newscast_widget_function' // Display function.
           );
   }
   add_action('wp_dashboard_setup', 'WJCT_Latest_Newscast_add_dashboard_widget');

   /*
   ** Output dashboard widget contents
   */
   function WJCT_Latest_Newscast_widget_function()
   {
    echo '<div>';
    echo '<p>The newscast was last uploaded ';

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

    // Show just the header that we need, the last modified date/time

    $lastupdated = substr($headers[7], 15, 29);
    $date = new DateTime($lastupdated);
    $date = $date->format('m/d/Y H:i:s A T');
    echo $date;
    // echo $date->format('m/d/Y H:i:s A T');
    echo '</p>';
    echo '<p>';
    echo gmdate('m/d/Y H:i:s A T');
    // Current UTC
    echo '</p>';
    echo '</div>';
   }
