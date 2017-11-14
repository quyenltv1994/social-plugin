<?php
require_once PLUGIN_DIR."api/Athena_Social_Api.php";
require_once PLUGIN_DIR."api/Athena_Social_Object.php";

class Athena_Youtube_Api extends Athena_Social_Api{

    public function getResults($playlistId, $apiKey){
        $apiCount = 50;
        if(!empty($playlistId)) {
            $result = $this->_request('https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=' . $playlistId . '&maxResults=' . $apiCount . '&key=' . $apiKey);
            $result = json_decode($result);
            if (!empty($result->items)) {
                //reset Data
                $delete = $this->_resetData('youtube');
                // parse medias
                foreach ($result->items as $video) {
                    $createTime = date('Y-m-d H:i:s', strtotime($video->publishedAt));
                    $social_object = new Athena_Social_Object();
                    $social_object->setTitle($video->snippet->title);
                    $social_object->setContent($video->snippet->description);
                    $social_object->setCreateTime($createTime);
                    $social_object->setPostType('youtube');
                    $social_object->setPhoto( $video->snippet->thumbnails->maxres->url);
                    $social_object->setLink('https://www.youtube.com/watch?v='.$video->snippet->resourceId->videoId);
                    $post_id = $this->_saveData($social_object);
                    if($post_id){
                        echo "<p>".$video->snippet->title."-".$video->snippet->description."....DONE!</p>";
                    }
                }
            } else {
                echo $result->meta->error_message;
            }
        } else {
            echo 'Renseignez un id dans le paramÃ¨tre "playlistId" de la config pour cette mÃ©thode';
        }
    }
}