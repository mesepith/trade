<?php

class Fetch_nse_cookies_model extends CI_Model {

    function fetchCookieUrls() {

        $qry = $this->db->where('status', 1)
                ->get('nse_cookies_url');


        if ($qry->num_rows() > 0) {
            return $qry->result_array();
        }

        return NULL;
    }

    public function addCookies($data, $is_api, $url, $parent_id, $main_url_id, $nse_cookies_url_id) {

        $data['is_api'] = $is_api;
        $data['url'] = $url;
        $data['parent_id'] = $parent_id;
        $data['main_url_id'] = $main_url_id;
        $data['nse_cookies_url_id'] = $nse_cookies_url_id;

        $this->db->insert('nse_cookies', $data);
        return $this->db->insert_id();
    }

    public function lastUpdatedCookie($url) {
        $qry = $this->db->select('created_at')
                ->where('url', $url)
                ->where('status', 1)
                ->order_by('id', 'DESC')
                ->limit(1)
                ->get('nse_cookies');

        if ($qry->num_rows() > 0) {
            return $qry->row()->created_at;
        }
        return NULL;
    }

    public function deleteCookies() {
        $this->db->set('status', '0')
                ->where('status', 1)
                ->update('nse_cookies');
    }

    public function getActiveCookie() {
        $qry = $this->db->where('status', 1)
                ->get('nse_cookies');


        if ($qry->num_rows() > 0) {
            return $qry->result_array();
        }

        return NULL;
    }
    
    public function cookieNotWorking( $main_url, $api_url, $nse_cookies_url_id ) {
        $this->db->set('cookie_working', 0)
                ->where('main_url', $main_url)
                ->where('api_url', $api_url)
                ->where('status', 1)
                ->update('nse_cookies_url');
        
         $this->db->set('status', 0)
                ->where('nse_cookies_url_id', $nse_cookies_url_id)
                ->where('status', 1)
                ->update('nse_cookies');
    }
    public function setCookieWorkingStatus( $nse_cookies_url_id, $cookie_working_status ) {
        $this->db->set('cookie_working', $cookie_working_status)
                ->where('id', $nse_cookies_url_id)
                ->where('status', 1)
                ->update('nse_cookies_url');
    }

    public function getActiveCookieByType($cookie_type) {
        $qry = $this->db->where('status', 1)
                ->where('name', $cookie_type)                
                ->order_by('id', 'DESC')
                ->select('value')
                ->get('nse_cookies');


        if ($qry->num_rows() > 0) {
            return $qry->result_array()[0]['value'];
        }

        return NULL;
    }

}
