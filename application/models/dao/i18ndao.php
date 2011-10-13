<?php

class I18nDao extends CI_Model {

    private $ll_cc = array(
        'en' => 'English',
        'es' => 'Spanish',
        'ja' => 'Japanese',
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
            $result = $this->ll_cc[$ll];
        }

        log_message('debug', "getLanguageName: exit.");
        return $result;
    }

    /**
     * Returns the translated message of the given message (specified by 'id').
     * 
     * @param type $msgId The ID of message to translate ('integer' or 'string')
     * @param type $lang (optional) The language code to translate.
     * @param type $clientType  (optional) The client type ('ios', 'js')
     */
    public function translate($id, $lang = null, $clientType = null) {
//        log_message('debug', "translate: enter.");

        if ($lang == null || !$this->isSupported($lang)) {
            $lang = "en";
        }

        $category = strtok($id, "_");
        // TODO: Current it ignores the language code. Fix it later.
        $lang = strtok($lang, "_");

        $this->lang->load($category, $lang);
        $originalText = $this->lang->line($id);

 //       log_message('debug', "translate: exit.");
        return $this->replace($originalText, $clientType);
    }

    private function replace(&$translated, &$clientType) {
//        log_message('debug', "replace: enter.");

        if ($clientType == 'ios') {
            $pattern = "/%(\d*)\\\$(s|d)/";
            $replacement = '%\1$@';
            $translated = preg_replace($pattern, $replacement, $translated);
        }

//        log_message('debug', "replace: exit.");
        return $translated;
    }

    /**
     * Returns only the updated language message after the specific time.
     * 
     * @param string $category message category.
     * @param string $lang language code.
     * @param integer $after timestamp.
     * @return array array of updated messages. Return format is like this {"lastUpdated":"1318234635", "data":[{"id":"msg_id","message":"msg_content"}, ..]}
     */
    public function getDelta($category, $after, $lang = null, $clientType = null) {
        log_message('debug', "getDelta: enter.");

        if ($lang == null || !$this->isSupported($lang)) {
            $lang = "en";
        }
        // TODO: Current it ignores the language code. Fix it later.
        $lang = strtok($lang, "_");

        if ($category == null || !is_numeric($after)) {
            log_message('error', "'category' and 'after' are required.");
            throw new Exception("'category' and 'after' are required.", 400);
        }

        $found = $this->lang->load($category, $lang);
        if ($found == FALSE) {
            log_message('error', "Category Not Found: " . $category);
            throw new Exception("Category Not Found: " . $category, 404);
        }
        $lastUpdated = intval($this->lang->line("__last_updated"));
        if ($lastUpdated > $after) {
            $origin = $this->lang->all();
            $keys = array_keys($origin);
            foreach ($keys as $key) {
                $origin[$key] = $this->replace($origin[$key], $clientType);
            }

            $_lastUpdated = $origin['__last_updated'];
            unset($origin['__last_updated']);

            $keys = array_keys($origin);
            $result = array();
            foreach ($keys as $key) {
                array_push($result, array('id' => $key, 'message' => $origin[$key]));
            }

            log_message('debug', "getDelta: exit.");
            return array('lastUpdated' => $_lastUpdated, 'data' => $result);
        }

        log_message('debug', "getDelta: exit.");
        return array("lastUpdated" => $this->lang->line("__last_updated"), 'data' => array());
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
        return array_key_exists($lang, $this->ll_cc) || array_key_exists(strtok($lang, "_"), $this->ll_cc);
    }

}

?>
