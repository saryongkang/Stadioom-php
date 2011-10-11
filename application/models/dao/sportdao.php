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
        log_message('debug', "add: enter.");

        if (!$this->isInRange($sport->getName(), 5, 32)) {
            log_message('error', "Invalid name (5 <= name <= 32).");
            throw new Exception("Invalid name (5 <= name <= 32).", 400);
        }

        // TODO (low) need optimization.
        $prev = $this->em->getRepository('Entities\Sport')->findOneByName($sport->getName());
        if ($prev == null) {
            $this->em->persist($sport);
            $this->em->flush();
        }
        $added = $this->em->getRepository('Entities\Sport')->findOneByName($sport->getName());

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

        $sport = $this->em->find('Entities\Sport', $id);
        if ($sport != null) {
            $this->em->remove($sport);
            $this->em->flush();
        }

        log_message('debug', "remove: exit.");
    }

    public function find(&$id) {
        log_message('debug', "find: enter.");

        if (!(is_numeric($id) && $id > 0)) {
            log_message('error', "Invalid ID: " . $id);
            throw new Exception("Invalid ID: " . $id, 400);
        }
        $sport = $this->em->find('Entities\Sport', $id);
        if ($sport == null) {
            throw new Exception("Not Found.", 404);
        }

        log_message('debug', "find: exit.");
        return $sport;
    }

    public function getAll() {
        log_message('debug', "getAll: enter.");
        log_message('debug', "getAll: exit.");
        return $this->em->createQuery('SELECT s FROM Entities\Sport s')->getResult();
    }

    public function getAllOrderedByPriority() {
        log_message('debug', "getAllOrderedByPriority: enter.");
        log_message('debug', "getAllOrderedByPriority: exit.");
        return $this->em->createQuery('SELECT s FROM Entities\Sport s ORDER BY s.priority DESC')->getResult();
    }

    public function findAfter($after) {
        log_message('debug', "findAfter: enter.");
        if ($after == null || $after < 0) {
            $after = 0;
        }

        $q = $this->em->createQuery('SELECT s FROM Entities\Sport s WHERE s.latestRevision > ' . $after);

        log_message('debug', "findAfter: exit.");
        return $q->getResult();
    }

    public function findAllSponsorsOf($sportId) {
        log_message('debug', "findAllSponsorsOf: enter.");

        if ($sportId == null) {
            log_message('error', "Sport ID is required.", 400);
            throw new Exception("sportId is required.", 400);
        }
        
        $sport = $this->em->find('Entities\Sport', $sportId);
        if ($sport == null) {
            throw new Exception("Sport Not Found: " . $sportId, 404);
        }

        $result = $this->doctrine->em->createQuery("SELECT b, s FROM Entities\Brand b JOIN b.sports s WHERE s.id = " . $sportId)->getResult();

        log_message('debug', "findAllSponsorsOf: exit.");
        return $result;
    }

    private function isInRange(&$str, $min, $max) {
        $length = strlen($str);
        return $length == 0 || ($min <= $length && $length <= $max);
    }

}

?>
