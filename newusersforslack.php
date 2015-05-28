<?php
// Script written by levels.io
// Config lines: Please edit Lines 9,11,13,14,19,21,23 (15 optional)
// Edit timezone to your local city or leave as is
        date_default_timezone_set('America/New_York');
        mb_internal_encoding("UTF-8");
 
        // Find your Typeform ApiKey by going to https://admin.typeform.com/account
        $typeformApiKey='APIKEY';
        //Get your form ID by vieweing your form
        $typeformFormId='FORMID';
       //Get text fields by https://api.typeform.com/v0/form/FORMID?key=APIKEY&completed=true';
        $typeformEmailField='email_Number';
        $typeformNameField='textfield_Number';
        //JSON Email list - leave as is unless you change directories and/or file name
        $previouslyInvitedEmailsFile=__DIR__.'/slackmaillist.json';
 
        // your slack team/host name 
        $slackHostName='subdomain';
        // Find slack channels by going to https://api.slack.com/methods/channels.list/test
        $slackAutoJoinChannels='Channel1,Channel2';
        // generate token at https://api.slack.com/
        $slackAuthToken='SLACK-API-TOKEN';
 
// !!! DO NOT EDIT BELOW THIS LINE !!!
 
// Grab Typeform Emails
        if(@!file_get_contents($previouslyInvitedEmailsFile)) {
                $previouslyInvitedEmails=array();
        }
        else {
                $previouslyInvitedEmails=json_decode(file_get_contents($previouslyInvitedEmailsFile),true);
        }
        $offset=count($previouslyInvitedEmails);
 
        $typeformApiUrl='https://api.typeform.com/v0/form/'.$typeformFormId.'?key='.$typeformApiKey.'&completed=true&offset='.$offset;
 
        if(!$typeformApiResponse=file_get_contents($typeformApiUrl)) {
                echo "Sorry, can't access API";
                exit;
        }
 
        $typeformData=json_decode($typeformApiResponse,true);
 
        $usersToInvite=array();
        foreach($typeformData['responses'] as $response) {
                $user['email']=$response['answers'][$typeformEmailField];
                $user['name']=$response['answers'][$typeformNameField];
                if(!in_array($user['email'],$previouslyInvitedEmails)) {
                        array_push($usersToInvite,$user);
                }
        }
 
// Slack Invite Code
        $slackInviteUrl='https://'.$slackHostName.'.slack.com/api/users.admin.invite?t='.time();
 
        $i=1;
        foreach($usersToInvite as $user) {
                echo date('c').' - '.$i.' - '."\"".$user['name']."\" <".$user['email']."> - Inviting to ".$slackHostName." Slack\n";
 
                        $fields = array(
                                'email' => urlencode($user['email']),
                                'channels' => urlencode($slackAutoJoinChannels),
                                'first_name' => urlencode($user['name']),
                                'token' => $slackAuthToken,
                                'set_active' => urlencode('true'),
                                '_attempts' => '1'
                        );
 
                                $fields_string='';
                                foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
                                rtrim($fields_string, '&');
 
                                $ch = curl_init();
 
                                curl_setopt($ch,CURLOPT_URL, $slackInviteUrl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch,CURLOPT_POST, count($fields));
                                curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
 
                                $replyRaw = curl_exec($ch);
                                $reply=json_decode($replyRaw,true);
                                if($reply['ok']==false) {
                                        echo date('c').' - '.$i.' - '."\"".$user['name']."\" <".$user['email']."> - ".'Error: '.$reply['error']."\n";
                                }
                                else {
                                        echo date('c').' - '.$i.' - '."\"".$user['name']."\" <".$user['email']."> - ".'Invited successfully'."\n";
                                }
 
                                curl_close($ch);
 
                                array_push($previouslyInvitedEmails,$user['email']);
                $i++;
        }
// Write emails to json
file_put_contents($previouslyInvitedEmailsFile,json_encode($previouslyInvitedEmails));
 
?>
