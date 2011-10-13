<?php

class TeamDao extends CI_Model {

    private $em;

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');

        $this->em = $this->doctrine->em;
    }

    /**
     * Add a new sport.
     *
     * @param Entities\Team $team
     */
    public function add(&$team) {
        log_message('debug', "add: enter.");

        // TODO (low) need optimization.
        $prev = $this->em->getRepository('Entities\Team')->findOneByName($team->getName());
        if ($prev == null) {
            $this->em->persist($team);
            $this->em->flush();
        }
        $added = $this->em->getRepository('Entities\Team')->findOneByName($team->getName());

        log_message('debug', "add: exit.");
        return $added->getId();
    }

    public function find(&$id) {
        log_message('debug', "find: enter.");

        if (!(is_numeric($id) && $id > 0)) {
            log_message('error', "Invalid ID: " . $id);
            throw new Exception("Invalid ID: " . $id, 400);
        }
        $team = $this->em->find('Entities\Team', $id);
        if ($team == null) {
            log_message('error', "INot Found: " . $id);
            throw new Exception("Not Found: " . $id, 404);
        }

        log_message('debug', "find: exit.");
        return $team;
    }

    public function getAll() {
        log_message('debug', "getAll: enter.");

        $q = $this->em->createQuery('SELECT b FROM Entities\Team b');

        log_message('debug', "getAll: exit.");
        return $q->getResult();
    }

}

?>
