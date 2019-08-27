# WJCT Newscast Automator

The WJCT Newscast Automator is a plugin to automate a simple task: update the station's Alexa Flash Briefing every time the station's NPR One Newscast is updated.

## Background:

Public radio stations have to upload an MP3 to a server for the NPR One app to ingest the latest newscast.
And, because of how NPR One is set up, the file must have the same filename each time.

Amazon's Alexa requires an RSS feed of individual episodes to update the Alexa Flash Briefing. The mp3 attachment does not have to have an original filename, but the post must have a new date or Alexa will not update the Flash Briefing.

The WJCT Newscast Automator will create a new post on Wordpress every time the NPR One Newscast MP3 file is uploaded to the server.

With one action, two different platforms will receive the same content, with no additional labor from the news team.

### What's New

* version 0.2 - Added delete_old_newscasts(). Now the previous newscasts will go to the trash automatically before the new one is posted. It's a dirty fix, but at least there's only one newscast published at a time. 
* version 0.15 - Added last-modified.php and fixed code to get lastmodified Timestamp correctly.
* version 0.141 - Enabled MP3 URL settings option.
* version 0.14 - Updated license, added copy of license to text, added settings option menu (currently not functional)   
* version 0.13 - Beautified the code.
* version 0.12 - Changed date format in post title. Changed logic to include update database either way.
* version 0.11 - Changed name to WJCT Newscast Automator because reasons.
* version 0.1 -  Set up cron to check every 5 minutes for new mp3. If mp3 is new, the post will be created. (This version is ready for pre-alpha testing.)
* version 0.05 - Added cron job to automatically check for update every one minute (for testing).
* version 0.04 - Added programmatically_create_post() and logic in WJCT_Latest_Newscast_widget_function() to check if newscast is new. programmatically_create_post() fires if newscast is new.
* version 0.03 - Added get_option and update_option to store latest newscast update DateTime in WP Options table.
* version 0.02 - Updated dashboard widget to show latest newscast update DateTime in local timezone.
* version 0.01 - Created dashboard widget to show latest newscast update DateTime in UTC format.

## Roadmap

* AP Style - I want to format the title of the post to AP Style data and time. The former news editor in me cannot abide by this sloppy style.  
* Settings Page - I want to add a form that stations can fill out with their mp3 file URL, their preferences for cron interval, title prefix, category and tags.
* Instructions - I plan on putting together a guide on how to create an Alexa Flash Briefing, especially designed for public radio stations.
* How to Listen Guide - I plan on writing up a customizable guide for stations to post on how listeners can subscribe to their station's flash briefing.
