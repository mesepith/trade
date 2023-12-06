<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/Suggestion_Contr.php");

class Video_Play_Contr extends MX_Controller {

    public function playVideo($seo_url) {
//        echo $seo_url; exit;
        $this->load->model('Ytb_model');

        $ytbObjectList = $this->Ytb_model->playVideo($seo_url);

//        echo '<pre>'; print_r($ytbObjectList); exit;
        $suggestion_controller = new Suggestion_Contr();

        if (empty($ytbObjectList)) {

            $vid_id = 'vMZfyEy_jpI';
            $ytbObjectList = false;

        } else {
            $vid_id = $ytbObjectList[0]->vid_id;
            
            $data['nestedHead']['nestedSeo']['title'] = $ytbObjectList[0]->title;
            $data['nestedHead']['nestedSeo']['description'] = $ytbObjectList[0]->title;
        }
//echo $vid_id; exit;
        $suggestionList = $suggestion_controller->getSuggestionPlayVideoPage($vid_id);

//        if( empty($suggestionList)){
//            $suggestionList = false;
//        }
//        exit;
//        print_r($suggestionList); exit;

        $data['nestedHead']['component'] = array(
            "amp-youtube" => "https://cdn.ampproject.org/v0/amp-youtube-0.1.js"
        );
//        $data['nestedHead']['custom_template'] = array(
//            "amp-mustache" => "https://cdn.ampproject.org/v0/amp-mustache-0.1.js"
//            );

        $data['nestedHead']['css'] = array('assets/css/pages/video-play.css');


        $data['nestedData']['ytbObjectList'] = $ytbObjectList;
        $data['nestedData']['suggestionList'] = $suggestionList;

        $data['content'] = "video_play/video-play-parent";

//        $this->load->view('home/home-list-parent', $data);
        $this->load->view('index', $data);
    }

}
