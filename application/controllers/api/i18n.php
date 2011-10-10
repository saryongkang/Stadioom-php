<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class I18n extends Stadioom_REST_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('dao/I18nDao2');

        force_ssl();
    }

    public function index_get() {
        try {
            $this->responseOk($this->I18nDao2->translate($this->get('id'), $this->get("lang"), $this->get("clientType")));
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function delta_get($after) {
        // TODO later...
    }

    public function lang_get($id = null) {
        if ($id == null) {
            $this->responseOk(array('data' => $this->I18nDao2->getAllSupportedLanguages()));
        } else {
            $name = $this->I18nDao2->getLanguageName($id);
            if ($name === null) {
                $name = 'Not supported';
            }
            $this->responseOk($name);
        }
    }

    // only for testing purpose.
    public function insert_get() {
        $this->I18nDao->insert(1000, 'img_shareicon', "http://stadioom.com/assets/images/sponsors/shareicons/{1}_{2}_shareicon.gif", "http://stadioom.com/assets/images/sponsors/shareicons/%1@_%2@_shareicon.gif", "http://stadioom.com/assets/images/sponsors/shareicons/%1s_%2s_shareicon.gif", "en");
        $this->I18nDao->insert(2000, 'link_match', "http://stadioom.com/match/view/{1}", "http://stadioom.com/match/view/%@", "http://stadioom.com/match/view/%s", "en");
        $this->I18nDao->insert(10001, 'msg_match_won', "{1} won!", "%@ won!", "%1s won!", "en");
        $this->I18nDao->insert(10001, 'msg_match_won', "{1} 승!", "%@ 승", "%1s 승!", "ko");
        $this->I18nDao->insert(10002, 'msg_match_lost', "{1} lost!", "%@ lost!", "%1s lost!", "en");
        $this->I18nDao->insert(10002, 'msg_match_lost', "{1} 패!", "%@ 패!", "%1s 패!", "ko");
        $this->I18nDao->insert(10003, 'msg_match_drew', "{1} drew!", "%@ drew!", "%1s drew!", "en");
        $this->I18nDao->insert(10003, 'msg_match_drew', "{1} 무승부!", "%@ 무승부!", "%1s 무승부!", "ko");
        $this->I18nDao->insert(10004, 'msg_match_caption', "{1} defeated {2} in a fierce {3} match.", "%1@ defeated %2@ in a fierce %3@ match.", "%1s defeated %2s in a fierce %3s match.", "en");
        $this->I18nDao->insert(10004, 'msg_match_caption', "{1}가 {3} 경기에서 {2}에 대항하여 승리했습니다.", "%1@가 %3@ 경기에서 %2@에 대항하여 승리했습니다.", "%1s가 %3s 경기에서 %2s에 대항하여 승리했습니다.", "ko");
        $this->I18nDao->insert(10005, 'msg_match_title', "{1} {2} Match", "%1@ %2@ Match", "%1s %2s Match", "en");
        $this->I18nDao->insert(10005, 'msg_match_title', "{1}배 {2}경기", "%1@배 %2@경기", "%1s배 %2s경기", "ko");
        $this->I18nDao->insert(10006, 'msg_match_score', "Final score: {1} {2} - {3} {4}", "Final score: %1@ %2@ - %3@ %4@", "Final score: %1s %2s - %3s %4s", "en");
        $this->I18nDao->insert(10006, 'msg_match_score', "최종 점수: {1} {2} - {3} {4}", "최종 점수: %1@ %2@ - %3@ %4@", "최종 점수: %1s %2s - %3s %4s", "ko");
    }

}

?>
