<?php

class Athena_Social_Api{

    protected function _request($url, $headers = false) {
        $ch = curl_init();
        $timeout = 50; // set to zero for no timeout
        if($headers) {
            curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }

    protected function _serializeParams($params) {
        $p = array();
        foreach($params as $key => $value) {
            $p[] = $key.'='.urlencode($value);
        }
        return implode('&', $p);
    }

    protected function _resetData($posttype){
        global $wpdb;
        $posts_table = $wpdb->prefix . 'posts';
        $sql = "DELETE FROM {$posts_table} WHERE {$posts_table}.post_type = '{$posttype}'";
        $delete = $wpdb->query($sql);
        return $delete;
    }

    protected function _saveData($object){
        $createTime = date("d-F", strtotime($object->getCreateTime()));
        $post_id = wp_insert_post( array(
            'post_status' => 'publish',
            'post_type' => $object->getPostType(),
            'post_title' => $object->getTitle(),
            'post_content' => $object->getContent(),
            'post_date' => $object->getCreateTime()
        ) );
        update_post_meta( $post_id, 'social_photo', $object->getPhoto());
        update_post_meta( $post_id, 'social_link', $object->getLink() );
        if(!empty($object->getAvatar())){
            update_post_meta( $post_id, 'social_avatar', $object->getAvatar() );
        }
        return $post_id;
    }
}