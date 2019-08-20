# WJCT-Alexa-Flash-Briefing-Automator

The WJCT Alexa Flash Briefing Automator is a plugin to automate a simple task, update the station's Alexa Flash Briefing every time the station's NPR One Newscast is updated.

## Background:

Public radio stations have to upload an MP3 to a server for the NPR One app to ingest the latest newscast.
And, because of how NPR One is set up, the file must have the same filename each time.

Amazon's Alexa requires an RSS feed of individual episodes to update the Alexa News Briefing. The mp3 attachment does not have to have an original filename, but the post must have a new date or Alexa will not update the News Briefing.

The WJCT-Alexa-Flash-Briefing-Automator will create a new post on Wordpress every time the NPR One Newscast MP3 file is uploaded to the server.

With one action, two different platforms will receive the same content, with no additional labor from the news team.

version 0.01 - Created dashboard widget to show latest newscast update DateTime in UTC format. 
