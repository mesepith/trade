<?php

require 'WhichBrowser/vendor/autoload.php';
require 'guzzle/vendor/autoload.php';

class Custom_Controller {
    /*
     * @author: ZAHIR
     * DESC: set cookie
     *
     */

    function setcookie_browser() {

        $check_prev_cookie = $this->input->cookie('uni_co', true);

        if (empty($check_prev_cookie)) {

            $unique_id = uniqid();

            $cookie = array(
                'name' => 'uni_co',
                'value' => $unique_id,
                'expire' => strtotime("+1 year"),
            );

            $this->input->set_cookie($cookie);
        }
        $check_prev_language_cookie = $this->input->cookie('googtrans', true);

        if (empty($check_prev_language_cookie) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {

            $browser_language_setting = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

            if (!empty($browser_language_setting)) {

                $language_cookie = array(
                    'name' => 'googtrans',
                    'value' => '/en/'.$browser_language_setting,
                    'expire' => strtotime("+1 year"),
                );

		   $this->input->set_cookie($language_cookie);
            }
        }
    }

    /*
     * @author: ZAHIR
     * DESC: Store admin login/logout logs
     */

    function storeLoginLog($auth_user_id, $credential_data, $login_or_logout) {

        $userip = $_SERVER['REMOTE_ADDR'];

        $browser_data = $this->getBrowserData();

        if (empty($browser_data)) {

            $browser_data = null;
        }

        $client = new GuzzleHttp\Client();

        $connection_info = json_decode(($client->request('GET', 'https://ipinfo.io/' . $userip)->getBody()->getContents()), true);

        //echo "<pre>";
        //print_r($connection_info); 

        $article = new AdminLoginLogoutLog;
        $article->auth_user_id = $auth_user_id;
        $article->credential_data = json_encode($credential_data);
        $article->browser_data = ($login_or_logout === "login") ? json_encode($browser_data) : null;
        $article->connection_info = json_encode($connection_info);
        $article->login_or_logout = $login_or_logout;
        $article->cookie_id = (!empty($this->input->cookie('uni_co', true))) ? $this->input->cookie('uni_co', true) : "";
        $article->ci_session = (!empty($this->input->cookie('ci_session', true))) ? $this->input->cookie('ci_session', true) : "";
        $article->created_at = date("Y-m-d H:i:s");
        $article->save();
        $last_insert_id = $article->id;
    }

    /*
     * @author: ZAHIR
     * DESC: Get browser data
     * */

    function getBrowserData() {

        if (empty($_SERVER['HTTP_USER_AGENT'])) {

            return false;
        }

        $browser_details = new WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);

        if (empty($browser_details)) {

            return false;
        }
        $browser_details_decode = json_decode(json_encode($browser_details), true);

        return $browser_details_decode;
    }

}
