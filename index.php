<?php
#!/usr/bin/php -q
$botId = '[BotToken]';

$botData = 'https://api.telegram.org/'.$botId.'/getupdates';

$postsBot = apiCall($botData);
echo 'GET MESSAGES ::: '."\n";

$postsBot = json_decode($postsBot);
$resultPosts = $postsBot->result;

foreach($resultPosts as $messages){

    foreach($messages->message->photo as $photo){
       $apiFile = 'https://api.telegram.org/'.$botId.'/getFile?file_id='.$photo->file_id;

       $calledFile =json_decode(apiCall($apiFile));
 		echo 'File :::  '.$photo->file_id.' Called '. "\n";

       $files[$photo->file_id] = [
           'file_path' => $calledFile->result->file_path,
           'file_size' => $calledFile->result->file_size,
       ];
    }

}

foreach($files as $file_id=>$file){
    $file_address = 'https://api.telegram.org/file/'.$botId.'/'.$file['file_path'];



    if(!file_exists(__DIR__.$file_id.'_'.'/'.$file['file_path'])){
		echo ' Create File :::  '.$file['file_path'].' Created' . "\n";


        file_put_contents(__DIR__.'/'.$file['file_path'],file_get_contents($file_address));

    }
    // echo '<img src="'.$file_address.'" alt="'.$file['file_size'].'><br>';
}



echo 'DONE';


exit;
function apiCall($url)
{

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
    ));

    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,0);
    curl_setopt($curl, CURLOPT_TIMEOUT, 400); //timeout in seconds

    $resp = curl_exec($curl);

    $curl_errno = curl_errno($curl);
    $curl_error = curl_error($curl);
    curl_close($curl);

    if ($curl_errno > 0) {
        echo "cURL Error ($curl_errno): $curl_error\n";
    } else {
        return  $resp;
    }

}



