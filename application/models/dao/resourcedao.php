<?php

class ResourceDao extends CI_Model {

    /**
     *
     * @var \Doctrine\EntityManager 
     */
    private $em;

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');

        $this->em = $this->doctrine->em;
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
    
    public function getByNumId(&$id, &$clientType, &$lang) {
        $columnName = $this->getColumnName($clientType);
        $result = $this->em->createQuery("SELECT m." . $columnName . " FROM Entities\Resource m WHERE m.numId = " . $id . " AND m.lang = '" . $lang . "'")->getResult();
        if (count($result) == 0) {
            throw new Exception("Resource Not Found.", 404);
        }
        return $result[0][$columnName];
    }
    
    public function getByStrId(&$id, &$clientType, &$lang) {
        $columnName = $this->getColumnName($clientType);
        $result = $this->em->createQuery("SELECT m." . $columnName . " FROM Entities\Resource m WHERE m.strId = '" . $id . "' AND m.lang = '" . $lang . "'")->getResult();
        if (count($result) == 0) {
            throw new Exception("Resource Not Found.", 404);
        }
        return $result[0]['$columnName'];
    }
    
    public function insert($numId, $strId, $msgGeneral, $msgiOS, $msgJS, $lang) {
        $msg = new Entities\Resource();
        $msg->setNumId($numId);
        $msg->setStrId($strId);
        $msg->setMsg($msgGeneral);
        $msg->setMsgiOS($msgiOS);
        $msg->setMsgJS($msgJS);
        $msg->setLang($lang);
        $this->em->persist($msg);
        $this->em->flush();
    }
}

?>
