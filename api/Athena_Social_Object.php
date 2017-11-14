<?php

class Athena_Social_Object{

    protected $title; //title

    protected $content; //content

    protected $photo; //photo

    protected $link; //link

    protected $created_time; //created time

    protected $posttype; //post type

    protected $avatar; //avatar

    protected $screen_name; //screen_name

    public function setTitle($title){
        $this->title = $title;
    }

    public function setContent($content){
        $this->content = $content;
    }

    public function setPhoto($photo){
        $this->photo = $photo;
    }

    public function setLink($link){
        $this->link = $link;
    }

    public function setCreateTime($created_time){
        $this->created_time = $created_time;
    }

    public function setPostType($posttype){
        $this->posttype = $posttype;
    }

    public function setAvatar($avatar){
        $this->avatar = $avatar;
    }

    public function setScreen_name($screen_name){
        $this->screen_name = $screen_name;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getContent(){
        return $this->content;
    }

    public function getPhoto(){
        return $this->photo;
    }

    public function getLink(){
        return $this->link;
    }

    public function getCreateTime(){
        return $this->created_time;
    }

    public function getPostType(){
        return $this->posttype;
    }

    public function getAvatar(){
        return $this->avatar;
    }

    public function getScreenName(){
        return $this->screen_name;
    }

}