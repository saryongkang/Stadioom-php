<?php

class I18nDao extends CI_Model {

    private $ll_cc = array(
        'en' => 'English',
        'es' => 'Spanish'
    );
    private $em;

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');

        $this->em = $this->doctrine->em;
    }

    /**
     * Returns all language codes currently supported by this service.
     * 
     * @return array all languages currently supported.
     */
    public function getAllSupportedLanguages() {
        return $this->ll_cc;
    }

    public function getLanguageName($ll) {
        if ($this->isSupported($ll)) {
            return $this->ll_cc[$ll];
        }
        return null;
    }

    /**
     * Returns the translated message of the given msg (specified by 'msgId').
     * 
     * @param type $msgId The ID of message to translate ('integer' or 'string')
     * @param type $lang (optional) The language code to translate.
     * @param type $clientType  (optional) The client type ('ios', 'js')
     */
    public function translate($msgId, $lang = null, $clientType = null) {
        if ($lang == null || !$this->isSupported($lang)) {
            $lang = "en";
        }

        if (is_numeric($msgId)) {
            return $this->getByNumId($msgId, $lang, $clientType);
        } else {
            return $this->getByStrId($msgId, $lang, $clientType);
        }
    }

    public function getByNumId(&$id, &$lang, &$clientType) {
        $columnName = $this->getColumnName($clientType);
        $result = $this->em->createQuery("SELECT m." . $columnName . " FROM Entities\Resource m WHERE m.numId = " . $id . " AND m.lang = '" . $lang . "'")->getResult();
        if (count($result) == 0) {
            throw new Exception("Resource Not Found.", 404);
        }
        return $result[0][$columnName];
    }

    public function getByStrId(&$id, &$lang, &$clientType) {
        $columnName = $this->getColumnName($clientType);
        $result = $this->em->createQuery("SELECT m." . $columnName . " FROM Entities\Resource m WHERE m.strId = '" . $id . "' AND m.lang = '" . $lang . "'")->getResult();
        if (count($result) == 0) {
            throw new Exception("Resource Not Found.", 404);
        }
        return $result[0][$columnName];
    }

    /**
     * Checks whether the given language is supported.
     * 
     * @param string $lang The language code to check.
     * @return boolean 'true' if the given language is supported. 'false' otherwise.
     */
    public function isSupported(&$lang) {
        return array_key_exists($lang, $this->ll_cc);
    }

    private function getColumnName(&$clientType) {
        switch ($clientType) {
            case "ios":
                return "msgiOS";
            case "js":
                return "msgJS";
            default:
                return "msg";
        }
    }
//
//    public function insert($numId, $strId, $msgGeneral, $msgiOS, $msgJS, $lang) {
//        $msg = new Entities\Resource();
//        $msg->setNumId($numId);
//        $msg->setStrId($strId);
//        $msg->setMsg($msgGeneral);
//        $msg->setMsgiOS($msgiOS);
//        $msg->setMsgJS($msgJS);
//        $msg->setLang($lang);
//        $this->em->persist($msg);
//        $this->em->flush();
//    }

}

?>