<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Twitter test</title>
<link type="text/css" rel="stylesheet" href="http://www.kinderenvoorkika.nl/css/twitter.css">
</head>

<body>
<?php
ini_set('display_errors', 1);
require('libs/TwitterAPIExchange.php');


/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token' => "488409423-c4zDcWtUVb64GApaXS9whaPo1u3PaPn6DNADxl8g",
    'oauth_access_token_secret' => "vAHx0AgXN59NkUg6HZbwAb7dAFLS44ZVIfojCxCyVHHC6",
    'consumer_key' => "QDcrnuIhnxBGAegftkJ8AA",
    'consumer_secret' => "TqxQH6AJfqM5LSxMEka0kZaldt43YzwK2E0qnjyaK4"
);


$url = 'https://api.twitter.com/1.1/search/tweets.json';
$requestMethod = 'GET';

$getfield = '?q=#bergop+OR+#kinderenvoorkika+OR+kindvoorkika';

$twitter = new TwitterAPIExchange($settings);
$response =  $twitter->setGetfield($getfield)
                 ->buildOauth($url, $requestMethod)
                 ->performRequest();
$r = json_decode($response);

foreach ($r->statuses as $key=>$rec)
  { 
   	echo "<div class=\"status\">";
   	echo "<div class=\"foto\"><img src=\"".$rec->user->profile_image_url."\" width=\"30\" height=\"30\"></div>";
  	echo "<a href=\"https://www.twitter.com/".$rec->user->name."\">".$rec->user->name."</a><br>"; 
	echo "<a href=\"https://www.twitter.com/".$rec->user->name."\">@".$rec->user->screen_name."</a><br>";
	echo "<div style=\"clear:both\"></div>";
	echo $rec->metadata->created_at."<br>";;
  	$t=$rec->text;
	$i=0;
	$s=array();
	foreach ($rec->entities->hashtags as $val)
	  { 
	  	$s[$i]['start']=$val->indices[0];
	  	$s[$i]['end']=$val->indices[1];
		$s[$i]['replace']="<a href=\"https://www.twitter.com/search?q=%23".$val->text."&src=hash\" target=\"_blank\">#".$val->text."</a>";
		$i++;
	  }
	foreach ($rec->entities->user_mentions as $val)
	  { 
	  	$s[$i]['start']=$val->indices[0];
	  	$s[$i]['end']=$val->indices[1];
		$s[$i]['replace']="<a href=\"https://www.twitter.com/".$val->screen_name."\" target=\"_blank\">@".$val->screen_name."</a>";
		$i++;
	  }	
	arsort($s); 
	foreach ($s as $val)
	  { 
//	  	print_r($val); echo "<br>";
	  	$tail=substr($t,$val['end'],strlen($t)-$val['end']);
		$t=substr($t,0,$val['start']).$val['replace'].$tail;
	  } 
	echo $t."
	</div>"; 
//	print_r($s);
//	print_r($rec->entities->hashtags);
//	print_r($rec->entities->user_mentions);
  }

//echo "<br><br>";
//print_r($r->statuses);
?>
</body>
</html>