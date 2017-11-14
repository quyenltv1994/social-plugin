<?php
require_once PLUGIN_DIR."api/Athena_Social_Api.php";
require_once PLUGIN_DIR."api/Athena_Social_Object.php";

class Athena_Facebook_Api extends Athena_Social_Api{
    public function getResults($pageId, $accessToken){
        if(!empty($pageId)) {
            $requestParams = array(
                'access_token' => $accessToken,
                'fields'=>'id,from,message,message_tags,picture,link,type,created_time,object_id',
                'limit' => 50
            );
            $result = $this->_request('https://graph.facebook.com/'.$pageId.'/posts/?'.$this->_serializeParams($requestParams));
            $result = json_decode($result);
            if($result->data) {
                //reset Data
                $delete = $this->_resetData('facebook');
                foreach($result->data as $post) {
                    // get photo if post has photo type
                    if($post->type == 'photo' && !empty($post->object_id)) {
                        $urlPicture = 'https://graph.facebook.com/'.$post->object_id.'/picture?'.$this->_serializeParams(
                                array(
                                    'type'=>'normal',
                                    'redirect'=>'false' ,
                                    'access_token' => $accessToken
                                )
                            );
                        $rawDataPhoto = $this->_request($urlPicture);
                        $dataPhoto = json_decode($rawDataPhoto);

                        $post->photo = array(
                            'src' => $dataPhoto->data->url,
                            'width' => 500,
                            'height' => 500
                        );
                    }
                    $social_object = new Athena_Social_Object();
                    $social_object->setTitle($post->from->name);
                    $social_object->setContent($post->message);
                    $social_object->setCreateTime($post->created_time);
                    $social_object->setPostType('facebook');
                    $social_object->setPhoto($post->photo['src']);
                    $social_object->setLink('https://www.facebook.com/profile.php?id='.$post->from->id);
                    $post_id = $this->_saveData($social_object);
                    if($post_id){
                        echo "<p>".$post->from->name."-".$post->message."....DONE!</p>";
                    }
                }
            } else {
                //$this->_result->msg .= $result->meta->error_message;
            }
        } else {
            //$this->_result->msg = 'Renseignez l\'id de la page facebook';
        }
    }
}