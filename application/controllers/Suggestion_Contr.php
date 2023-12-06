<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Suggestion_Contr extends MX_Controller {

    public function getSuggestionPlayVideoPage($vid_id) {

        $channel_id = $this->Ytb_model->getChannelIdByVidId($vid_id);

        $vids_list_by_channel = $this->Ytb_model->getVidsListInfoByChannelNotVidId($vid_id, $channel_id);

//        echo 'vids_list_by_channel arr : ';
//        echo '<pre>';
//        print_r($vids_list_by_channel);
        
//        $oVal = array() (object);
//        echo '<pre>';
//        print_r($oVal); exit;
////        
//        echo 'suggestion count :: ' . count($vids_list_by_channel); 
        
        $not_required_vid_ids = array();
        $vid_ids_arr_list_by_tags = array();

        $not_required_vid_ids = array($vid_id);
        
        if( !empty($vids_list_by_channel)){
        
            foreach ($vids_list_by_channel AS $value) {

                $not_required_vid_ids[] = $value->vid_id;
            }
            
            $vids_list_by_channel_count = count($vids_list_by_channel);
            
        }else{
            
            $suggested_vids_obj = array();
            
            $vids_list_by_channel_count = 0;
            
        }

//            echo '<pre>'; print_r($not_required_vid_ids);
        
        $first_half_suggesion_list = array();

        if (!empty($vids_list_by_channel) && $vids_list_by_channel_count >= HALF_SUGGESTION_LIST_LIMIT_PLAY_VID) {
            
            $first_half_suggesion_list = $vids_list_by_channel;
            $suggested_vids_obj = $vids_list_by_channel;

//            echo 'first_half_suggesion_list arr : ';
//            echo '<pre>'; print_r($first_half_suggesion_list);
//            
//            return $vids_list_by_channel;
        } else if (!empty($vids_list_by_channel) || $vids_list_by_channel_count < HALF_SUGGESTION_LIST_LIMIT_PLAY_VID) {

//             echo '<br/>$vid_id : ' . $vid_id . '<br/>'; exit;

           

            $this->load->model('Ytb_tags_model');

            $tags_list_object = $this->Ytb_tags_model->getTagsByVidId($vid_id);

//            echo '<pre>'; print_r($tags_list_object); exit;

            $tags_arr = array();

            foreach ($tags_list_object AS $value) {

                $tags_arr[] = $value->tag_id;
            }



            $total_vid_id_requred = HALF_SUGGESTION_LIST_LIMIT_PLAY_VID - $vids_list_by_channel_count;
            
//            echo '<br/>total_vid_id_requred <br/>';
//            
//            echo '<pre>'; print_r($total_vid_id_requred); exit;

            $vid_ids_obj_list_by_tags = $this->Ytb_tags_model->getVidIdsByTagsListNotVidIds($not_required_vid_ids, $tags_arr, $total_vid_id_requred);
            
//            echo '<br/>vid_ids_obj_list_by_tags <br/>';
//            
//            echo '<pre>'; print_r($vid_ids_obj_list_by_tags); exit;
            
            foreach ($vid_ids_obj_list_by_tags AS $value) {

                $vid_ids_arr_list_by_tags[] = $value->vid_id;
            }
            
//            echo '<br/>$vid_ids_arr_list_by_tags <br/>';
//            
//            echo '<pre>'; print_r($vid_ids_arr_list_by_tags); exit;

            $vids_info_list_by_vidis = $this->Ytb_model->getVidsInfoByVidIds($vid_ids_arr_list_by_tags);
            
//            echo '<br/>$vids_info_list_by_vidis <br/>';
//            
//            echo '<pre>'; print_r($vids_info_list_by_vidis); exit;
            
            if($vids_list_by_channel_count > 0 ){
                
                $suggested_vids_obj = (array) array_merge((array) $vids_list_by_channel, (array) $vids_info_list_by_vidis);
                
            }else{
                $suggested_vids_obj = $vids_info_list_by_vidis;
            }
            
            
            
//            echo count($suggested_vids_obj);
//            echo '<br/>$suggested_vids_obj <br/>';
//            
//            echo '<pre>'; print_r($suggested_vids_obj); exit;

            if (!empty($suggested_vids_obj) && count($suggested_vids_obj) >= HALF_SUGGESTION_LIST_LIMIT_PLAY_VID) {

                $first_half_suggesion_list = $suggested_vids_obj;

//                echo 'first_half_suggesion_list arr from tags: ';
//                echo '<pre>';
//                print_r($first_half_suggesion_list);

//                return $suggested_vids_obj;
            }
        }
//        echo 'count : ' . count($suggested_vids_obj);
//        echo '<br/> suggested_vids_obj: ';
//        echo '<pre>';
//        print_r($suggested_vids_obj);
//        
//        if(empty($suggested_vids_obj)){
//            echo 'emptyyyyyy';
//        }else{
//            echo 'not empty';
//        }
//        exit;
        $final_suggetion = $this->getTopViwedVdoForPlayVdoPage(  $suggested_vids_obj, $not_required_vid_ids, $vid_ids_arr_list_by_tags );
        
        return $final_suggetion;
        

//        

        exit;
    }

    public function getTopViwedVdoForPlayVdoPage( $suggested_vids_obj, $not_required_vid_ids, $vid_ids_arr_list_by_tags) {
        
        $not_requred_vid_in_top_viewed = array_merge($not_required_vid_ids, $vid_ids_arr_list_by_tags);

        $top_view_videos_for_suggestion = $this->Ytb_model->getTopViewedVidsNotVidIds($not_requred_vid_in_top_viewed);

        return $suggested_vids_with_top_viewed_obj = (array) array_merge((array) $suggested_vids_obj, (array) $top_view_videos_for_suggestion);
    }

}
