<?php
require_once PLUGIN_DIR."api/Athena_Social_Api.php";
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
                if($result->errors) {
                    foreach($result->errors as $error) {
                        $this->_result->msg .= $error->message.' ';
                    }
                } else {
                    // parse tweets
                    foreach($result->statuses as $tweet) {
                        echo "<pre>";
                        print_r($tweet); die();
                        $tweetObj = $this->_parseTweet($tweet);
                        $this->_items->add($tweetObj);
                        $this->_result->code = 200;
                        $this->_result->success = true;
                    }
                }
            }
        } else {
            //$this->_result->msg = 'Renseignez au moins un tag dans le paramètre "tags" de la config pour cette méthode';
        }
    }
}