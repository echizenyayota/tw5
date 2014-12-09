<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>京阪電車なう</title>
    <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.17.2/build/cssreset/cssreset-min.css">
    <link rel="stylesheet" href="mystyle.css">
    <script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
</head>
<body>
<div id="wrapper">
<div id="header">
      <a href=""><h1>京阪電車なう</h1></a>
</div>
<div id="main">
  列車種別を検索
  <!--
  <form action="" method="get" id="search-form">
  </br>
  <input type="text" name="idle" style="font-size:14px;padding: 4px 6px">
  <input type="submit"value="検索" style="font-size:14px;">
  </form>-->
  <div class="adsense">
    <p>Adsense</p>
  </div>
  <div class="popular-recent">
    ただいまの発車：　
  </div>
  <div class="date-header">
    2014/09/29 10時
  </div>
<?php
require_once("twitteroauth/twitteroauth.php");

$consumerKey = "";
$consumerSecret = "";
$accessToken = "";
$accessTokenSecret = "";

$twObj = new TwitterOAuth($consumerKey,$consumerSecret,$accessToken,$accessTokenSecret);

$keywords = "京阪電車 -rt -bot";

// $keywords = "京阪 -rt -bot";

$param = array(
    "q"=>$keywords,                  // keyword
    "lang"=>"ja",                   // language
    "count"=>100,                   // number of tweets
    "result_type"=>"recent",       // result type
    // "include_entities"=>true       // entities（"inclued_entities"はなくてもいける）
);

$json = $twObj->OAuthRequest(
    "https://api.twitter.com/1.1/search/tweets.json",
    "GET",
    $param);

$result = json_decode($json, true);

?>
<?php

if($result['statuses']){
    foreach($result['statuses'] as $tweet){
?>
        <?php
            // 正規表現
            // 名前へのリンク(0回以上すべての文字列)
            $tweet['user']['name'] = preg_replace("/(.*)/u", " <a href=\"https://twitter.com/\\1\" target=\"twitter\">\\1</a>", $tweet['user']['name']);
            // ユーザー名（スクリーンネーム）へのリンク
            $tweet['user']['screen_name'] = preg_replace("/([A-Za-z0-9_]{1,15})/", " <a href=\"https://twitter.com/\\1\" target=\"twitter\">@\\1</a>", $tweet['user']['screen_name']);
            // テキスト中の#（ハッシュタグ）へのリンク
            $tweet['text'] = preg_replace("/\s#(w*[一-龠_ぁ-ん_ァ-ヴーａ-ｚＡ-Ｚa-zA-Z0-9]+|[a-zA-Z0-9_]+|[a-zA-Z0-9_]w*)/u", " <a href=\"https://twitter.com/search/%23\\1\" target=\"twitter\">#\\1</a>", $tweet['text']);
            // テキスト中の@（スクリーンネーム）へのリンク
            $tweet['text'] = preg_replace("/(@[A-Za-z0-9_]{1,15})/", " <a href=\"https://twitter.com/\\1\" target=\"twitter\">\\1</a>", $tweet['text']);
            // テキスト中のURLへのリンク
            $tweet['text'] = preg_replace("/(http:\/\/t.co\/[a-zA-Z0-9]{10})/", "<a href=\"\\0\" target=\"_blank\">\\0</a>", $tweet['text']);
          ?>
<div class="tweet">
    <ul>
      <div class="tweet-content">
        <div class="tweet-timestamp">
         <li><?php echo date('H:i:s', strtotime($tweet['created_at'])); ?></li>
        </div>
        <div class="tweet-icon">
         <li><img src="<?php echo $tweet['user']['profile_image_url']; ?>" /></li>
        </div>
      </div>
         <li><?php echo $tweet['user']['name']; ?><?php echo $tweet['user']['screen_name']; ?></li>
         <li><?php echo $tweet['text']; ?></li>
    </ul>
    <ul class="tweet-intents">
      <li>
         <img src="https://si0.twimg.com/images/dev/cms/intents/icons/reply_hover.png"><p>
      </li>
      <li>
         <a href="https://twitter.com/intent/tweet?in_reply_to= <?php echo $tweet['id']; ?>" target="_blank">返信</a></p>
      </li>
      <li>
         <img src="https://si0.twimg.com/images/dev/cms/intents/icons/favorite_on.png">
      <li>
         <p><a href="https://twitter.com/intent/retweet?tweet_id= <?php echo $tweet['id']; ?>" target="_blank">リツィート</a></p>
      </li>
      <li>
         <img src="https://si0.twimg.com/images/dev/cms/intents/icons/retweet_on.png">
      </li>
      <li>
         <p><a href="https://twitter.com/intent/favorite?tweet_id= <?php echo $tweet['id']; ?>" target="_blank">お気に入り</a></p>
      </li>
    </ul>
<div class="tweet-img">
  <img src="<?php echo $tweet['entities']['media'][0]['media_url']; ?>">
</div>
  <?php } ?>
    <?php }else{ ?>
    <div class="twi_box">
        <p class="twi_tweet">関連したつぶやきがありません。</p>
    </div>
<?php } ?>
</div>
<div id="footer">
<p>京阪電車なう</p>
<div>Copyright (c) 2014<?php if (date("Y")!=2014) echo date("-Y"); ?> えちぜんやよーた All Rights Reserved.</div>
</div>
</body>
</html>