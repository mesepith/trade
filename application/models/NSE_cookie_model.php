<?php

class NSE_cookie_model extends CI_Model {

    public function getWorkingIdByUrl($url) {
        $qry = $this->db->where('status', 1)->where('main_url', $url)->where('cookie_working', 1)
                ->select('id')
                ->order_by('id', 'desc')
                ->get('nse_cookies_url');


        if ($qry->num_rows() > 0 && !empty($qry->result_array()[0]['id'])) {
            return $qry->result_array()[0]['id'];
        }

        return NULL;
    }
    
    function getCookiesNameofApi( $nse_cookies_url_id ){
        
         $qry = $this->db->where('status', 1)->where('nse_cookies_url_id', $nse_cookies_url_id)
                ->where('is_api', 1)
                ->select('name')                
                ->get('nse_cookies');
         
//         echo $this->db->last_query();
         
        if ($qry->num_rows() > 0  ) {
            return $qry->result_array();
        }

        return NULL;
    }
          
    public function getUniqueCookiesInfo( $nse_cookies_url_id, $apis_cookies ) {
        
//        $where  = "('is_api' = 1 AND )";
        
        $qry = $this->db->where('status', 1)->where('nse_cookies_url_id', $nse_cookies_url_id)
                ->group_start()
                ->where('is_api', 1)
                ->or_where_not_in('name', $apis_cookies)
                ->group_end()
                ->select('id, name, value')
                ->get('nse_cookies');
        
//        echo $this->db->last_query();
        
        if ($qry->num_rows() > 0) {
            return $qry->result_array();
        }
        
        return NULL;
    }

}
