<?php
$timeZone = 'Europe/Berlin';
// Find your Typeform ApiKey by going to https://admin.typeform.com/account
$typeformApiKey = 'APIKEY';
//Get your form ID by vieweing your form
$typeformFormId = 'FORMID';
//Get text fields by https://api.typeform.com/v0/form/FORMID?key=APIKEY&completed=true';
$typeformEmailField = 'email_Number';
$typeformNameField = 'textfield_Number';
//JSON Email list - leave as is unless you change directories and/or file name
$previouslyInvitedEmailsFile = __DIR__ . '/tsi_emaillog.json';

// your slack team/host name
$slackHostName = 'subdomain';
// Find slack channels by going to https://api.slack.com/methods/channels.list/test
$slackAutoJoinChannels = 'Channel1,Channel2';
// generate token at https://api.slack.com/
$slackAuthToken = 'SLACK-API-TOKEN';
?>