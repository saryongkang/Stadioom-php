<?php

class I18nDao extends CI_Model {

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
        log_message('debug', "getAllSupportedLanguages: enter.");
        log_message('debug', "getAllSupportedLanguages: exit.");
        
        return $this->ll_cc;
    }

    /**
     * Returns the language name of the given language code. (eg. 'en' -> 'English')
     * 
     * @param type $ll language code to ask.
     * @return string Language name og given langauge code. 
     */
    public function getLanguageName($ll) {
        log_message('debug', "getLanguageName: enter.");
        
        $result = null;
        if ($this->isSupported($ll)) {
            $result =  $this->ll_cc[$ll];
        }
        
        log_message('debug', "getLanguageName: exit.");
        return $result;
    }

    /**
     * Returns the translated message of the given msg (specified by 'msgId').
     * 
     * @param type $msgId The ID of message to translate ('integer' or 'string')
     * @param type $lang (optional) The language code to translate.
     * @param type $clientType  (optional) The client type ('ios', 'js')
     */
    public function translate($id, $lang = null, $clientType = null) {
        log_message('debug', "translate: enter.");
        
        if ($lang == null || !$this->isSupported($lang)) {
            $lang = "en";
        }

        $category = strtok($id, "_");

        $this->lang->load($category, $lang);
        $originalText = $this->lang->line($id);
        
        log_message('debug', "translate: exit.");
        return $this->replace($originalText, $clientType);
    }

    private function replace(&$translated, &$clientType) {
        log_message('debug', "replace: enter.");
        
        if ($clientType == 'ios') {
            $pattern = "/%(\d*)s/";
            $replacement = '%\1$@';
            $translated = preg_replace($pattern, $replacement, $translated);
        }
        
        log_message('debug', "replace: exit.");
        return $translated;
    }

    /**
     * Returns only the updated language message after the specific time.
     * 
     * @param string $category message category.
     * @param string $lang language code.
     * @param integer $after timestamp.
     * @return array array of updated message including '__last_modified'.
     */
    public function getDelta($category, $after, $lang = null, $clientType = null) {
        log_message('debug', "getDelta: enter.");
        
        if ($lang == null || !$this->isSupported($lang)) {
            $lang = "en";
        }
        if ($category == null || !is_numeric($after)) {
            throw new Exception("'category' and 'after' are required.", 400);
        }

        $found = $this->lang->load($category, $lang);
        if ($found == FALSE) {
            throw new Exception("Category Not Found", 404);
        }
        $lastUpdated = intval($this->lang->line("__last_modified"));
        if ($lastUpdated > $after) {
            $all = $this->lang->all();
            $keys = array_keys($all);
            foreach ($keys as $key) {
                $all[$key] = $this->replace($all[$key], $clientType);
            }
            
            log_message('debug', "getDelta: exit.");
            return array('data' => $all);
        }
        
        log_message('debug', "getDelta: exit.");
        return array('data' => array("__last_modified" => $this->lang->line("__last_modified")));
    }

    /**
     * Checks whether the given language is supported.
     * 
     * @param string $lang The language code to check.
     * @return boolean 'true' if the given language is supported. 'false' otherwise.
     */
    private function isSupported(&$lang) {
        log_message('debug', "isSupported: enter.");
         log_message('debug', "isSupported: exit.");
       return array_key_exists($lang, $this->ll_cc);
    }

}

?>
