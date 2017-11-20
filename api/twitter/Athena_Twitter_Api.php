<?php
require_once PLUGIN_DIR."api/Athena_Social_Api.php";
require_once PLUGIN_DIR."api/Athena_Social_Object.php";
require_once PLUGIN_DIR."api/libs/twitteroauth/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

class Athena_Twitter_Api extends Athena_Social_Api{
	protected function _getTwitterAuth($consumerKey, $consumerSecret) {
        if(!empty($consumerKey) && !empty($consumerSecret)) {
            $auth = new TwitterOAuth($consumerKey, $consumerSecret);
            return $auth;
        } else {
            //$this->_result->msg = 'Renseignez le consumerKey et consumerSecret dans la config twitter';
        }
        return false;
    }

    /**
     * parse a tweet data to build a Socialwall object
     * @param $tweet
     * @return Socialwall
     */
    protected function _parseTweet($tweet) {
        $social_object = new Athena_Social_Object();

        // basic infos
        $createTime = date('Y-m-d H:i:s', strtotime($tweet->created_at));
        // text, with url, hashtag and user parsing
        $tweet->text = preg_replace('@(https?://([-\w\.]+)+(/([\w/_\.]*(\?\S+)?(#\S+)?)?)?)@', '<a target="_blank"  href="$1">$2$3</a>', $tweet->text);
        $tweet->text = preg_replace('/@(\w+)/', '<a target="_blank" href="http://twitter.com/$1">@$1</a>', $tweet->text);
        $tweet->text = preg_replace('/#([^\s[:punct:]]+)/', ' <a target="_blank" href="http://twitter.com/search?q=%23$1">#$1</a>', $tweet->text);
        $tweet->text = wp_encode_emoji($tweet->text);
        echo $tweet->text;
        $social_object->setContent($tweet->text);
        $social_object->setCreateTime($createTime);
        // hashtags
        $hashtags = array();
        if(!empty($tweet->entities->hashtags)) {
            foreach($tweet->entities->hashtags as $h) {
                $hashtags[] = $h->text;
            }
        }
        $hashtags = implode(',', $hashtags);
        // image
        if(!empty($tweet->entities->media)) {
            $media = reset($tweet->entities->media);
            if(!empty($media->sizes->small)) {
                $img = $media->media_url.':small';
            }
        }
        $social_object->setTitle($tweet->user->name);
        $social_object->setScreen_name($tweet->user->screen_name);
        $social_object->setAvatar($tweet->user->profile_image_url);
        if(!empty($img)){
            $social_object->setPhoto($img);
        }
        $social_object->setLink('http://twitter.com/'.$tweet->user->screen_name);
        $social_object->setPostType('twitter');

        return $social_object;
    }
    /**
     * get twitter posts by tag(s)
     */
    public function getResults( $consumerKey, $consumerSecret, $tags) {
        $tags = explode('|', $tags);
        if(!empty($tags)) {
            $auth = $this->_getTwitterAuth($consumerKey, $consumerSecret);
            if($auth) {
                // we got tags and twitter auth, get tweets from api
                $tagSearch = urlencode(implode(' OR ', $tags));
                $tagSearch .= ' -filter:retweets';
                $tagSearch .= ' filter:safe';
                $requestParams = array(
                    'q' => $tagSearch,
                    'count' => 50
                );
                $result = $auth->get('search/tweets', $requestParams);
                if(!empty($result->errors)) {
                    foreach($result->errors as $error) {
                        echo $error->message.' ';
                    }
                } else {
                    //reset Data
                    $delete = $this->_resetData('twitter');
                    // parse tweets
                    foreach($result->statuses as $tweet) {
                        echo $tweet->user->screen_name;
                        $tweetObj = $this->_parseTweet($tweet);
                        $post_id = $this->_saveData($tweetObj);
                        if($post_id){
                            echo "<p>".$tweetObj->getTitle()."-".$tweetObj->getContent()."....DONE!</p>";
                        }
                    }
                }
            }
        } else {
            //$this->_result->msg = 'Renseignez au moins un tag dans le paramètre "tags" de la config pour cette méthode';
        }
    }
}