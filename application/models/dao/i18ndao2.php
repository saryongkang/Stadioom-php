<?php

class I18nDao2 extends CI_Model {

    private $ll_cc = array(
        'en' => 'English',
        'es' => 'Spanish',
        'ko' => 'Korean'
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
    public function translate($id, $lang = null, $clientType = null) {
        if ($lang == null || !$this->isSupported($lang)) {
            $lang = "en";
        }

        $category = strtok($id, "_");

        $this->lang->load($category, $lang);
        $originalText = $this->lang->line($id);
        return $this->replace($originalText, $clientType);
    }

    private function replace(&$translated, &$clientType) {
        if ($clientType == 'ios') {
            $pattern = "/%(\d*)s/";
            $replacement = '%\1@';
            $translated = preg_replace($pattern, $replacement, $translated);
        }
        return $translated;
    }

    public function getDelta($category, $lang, $after) {
        if ($lang == null || !$this->isSupported($lang)) {
            $lang = "en";
        }

        $this->lang->load($category, $lang);
        $lastUpdated = intval($this->lang->line("__" . $category . "_last_modified"));
        if ($lastUpdated > $after) {
            return $this->lang->all();
        }
        return array();
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

}

?>
