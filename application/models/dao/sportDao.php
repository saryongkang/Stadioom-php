<?php

class SportDao extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');
    }

    /**
     * Add a new sport.
     *
     * @param Entities\Sport $sport
     */
    public function add(&$sport) {
        if (!$this->isInRange($sport->getName(), 5, 32)) {
            throw new Exception("Invalid name (5 <= name <= 32).", 400);
        }

        $prev = $this->doctrine->em->getRepository('Entities\Sport')->findOneBy(array('name' => $sport->getName()));
        if ($prev == null) {
            $this->doctrine->em->persist($sport);
            $this->doctrine->em->flush();
        }
        $added = $this->doctrine->em->getRepository('Entities\Sport')->findOneBy(array('name' => $sport->getName()));
        return $added->getId();
    }

    /**
     * Delete the given sport.
     *
     * @param integer $id
     */
    public function remove(&$id) {
        $sport = $this->doctrine->em->getRepository('Entities\Sport')->findOneBy(array('id' => $id));
        if ($sport != null) {
            $this->doctrine->em->remove($sport);
            $this->doctrine->em->flush();
        }
    }

    public function find(&$id) {
        if (!(is_numeric($id) && $id > 0)) {
            throw new Exception("Invalid ID: " . $id, 400);
        }
        $sport = $this->doctrine->em->getRepository('Entities\Sport')->findOneBy(array('id' => $id));
        if ($sport == null) {
            throw new Exception("Not Found.", 404);
        }
        return $sport;
    }
    
    public function getAll() {
        $q = $this->doctrine->em->createQuery('SELECT s FROM Entities\Sport s WHERE s.id != 0 ORDER BY s.weight DESC');
        return $q->getResult();
    }

    /**
     * Check if the length of string is in the given range (inclusive).
     *
     * @return boolean
     */
    private function isInRange(&$str, $min, $max) {
        $length = strlen($str);
        return $length == 0 || (3 <= $min && $length <= $max);
    }

}

?>
