<?php

class SportDao extends CI_Model {

    private $em;

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');

        $this->em = $this->doctrine->em;
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

        $prev = $this->em->getRepository('Entities\Sport')->findOneByName($sport->getName());
        if ($prev == null) {
            $this->em->persist($sport);
            $this->em->flush();
        }
        $added = $this->em->getRepository('Entities\Sport')->findOneByName($sport->getName());
        return $added->getId();
    }

    /**
     * Delete the given sport.
     *
     * @param integer $id
     */
    public function remove(&$id) {
        $sport = $this->em->find('Entities\Sport', $id);
        if ($sport != null) {
            $this->em->remove($sport);
            $this->em->flush();
        }
    }

    public function find(&$id) {
        if (!(is_numeric($id) && $id > 0)) {
            throw new Exception("Invalid ID: " . $id, 400);
        }
        $sport = $this->em->find('Entities\Sport', $id);
        if ($sport == null) {
            throw new Exception("Not Found.", 404);
        }
        return $sport;
    }

    public function getAll() {
        return $this->em->createQuery('SELECT s FROM Entities\Sport s')->getResult();
    }
    
    public function getAllOrderedByPriority() {
        return $this->em->createQuery('SELECT s FROM Entities\Sport s ORDER BY s.priority DESC')->getResult();
    }

    public function findAfter($after) {
        if ($after == null || $after < 0) {
            $after = 0;
        }

        $q = $this->em->createQuery('SELECT s FROM Entities\Sport s WHERE s.latestRevision > ' . $after);
        return $q->getResult();
    }

    private function isInRange(&$str, $min, $max) {
        $length = strlen($str);
        return $length == 0 || ($min <= $length && $length <= $max);
    }

}

?>
