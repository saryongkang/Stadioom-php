<?php

class BrandDao extends CI_Model {

    private $em;

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');

        $this->em = $this->doctrine->em;
    }

    /**
     * Add a new sport.
     *
     * @param Entities\Sport $brand
     */
    public function add(&$brand) {
        log_message('debug', "add: enter.");
        
        if (!$this->isInRange($brand->getName(), 5, 32)) {
            throw new Exception("Invalid name (5 <= name <= 32).", 400);
        }

        $prev = $this->em->getRepository('Entities\Brand')->findOneByName($brand->getName());
        if ($prev == null) {
            $this->em->persist($brand);
            $this->em->flush();
        }
        $added = $this->em->getRepository('Entities\Brand')->findOneByName($brand->getName());
        
        log_message('debug', "add: exit.");
        return $added->getId();
    }

    /**
     * Delete the given sport.
     *
     * @param integer $id
     */
    public function remove(&$id) {
        log_message('debug', "remove: enter.");
        
        $brand = $this->em->find('Entities\Brand', $id);
        if ($brand != null) {
            $this->em->remove($brand);
            $this->em->flush();
        }
        
        log_message('debug', "remove: exit.");
    }

    public function find(&$id) {
        log_message('debug', "find: enter.");
        
        if (!(is_numeric($id) && $id > 0)) {
            throw new Exception("Invalid ID: " . $id, 400);
        }
        $brand = $this->em->find('Entities\Brand', $id);
        if ($brand == null) {
            throw new Exception("Not Found.", 404);
        }
        
        log_message('debug', "find: exit.");
        return $brand;
    }

    public function getAll() {
        log_message('debug', "getAll: enter.");
        
        $q = $this->em->createQuery('SELECT b FROM Entities\Brand b');
        
        
        log_message('debug', "getAll: exit.");
        return $q->getResult();
    }

    public function getAllOrderedByPriority() {
        log_message('debug', "getAllOrderedByPriority: enter.");
        
        $q = $this->em->createQuery('SELECT b FROM Entities\Brand b ORDER BY b.priority DESC');
        
        log_message('debug', "getAllOrderedByPriority: exit.");
        return $q->getResult();
    }
    
    public function findAfter($after) {
        log_message('debug', "findAfter: enter.");
        
        if ($after == null || $after < 0) {
            $after = 0;
        }

        $q = $this->em->createQuery('SELECT b FROM Entities\Brand b WHERE b.latestRevision > ' . $after);

        log_message('debug', "findAfter: exit.");
        return $q->getResult();
    }

    private function isInRange(&$str, $min, $max) {
        $length = strlen($str);
        return $length == 0 || ($min <= $length && $length <= $max);
    }

}

?>
