<?php

class MatchDao extends CI_Model {

    private $em;

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');

        $this->em = $this->doctrine->em;
    }

    public function register($args) {
        $match = $args['match'];
        $teamA = $args['teamA'];
        $teamB = $args['teamB'];
        $teamAFbIds = $args['teamAFbIds'];
        $teamAStIds = $args['teamAStIds'];
        $teamBFbIds = $args['teamBFbIds'];
        $teamBStIds = $args['teamBStIds'];
        $matchType = $match->getMatchType();

        if ($matchType == 1) { // single match
            if (($teamAFbIds == null && $teamAStIds == null)
                    || ($teamBFbIds == null && $teamBStIds == null)) {
                throw new Exception("Insufficient data (check the team member IDs.)", 400);
            }

            $this->em->persist($match);
            $this->em->flush();
            $this->em->refresh($match);

            $this->em->beginTransaction();
            try {
                foreach ($teamAStIds as $memberId) {
                    $member = new Entities\MatchRecordPlayer();
                    $member->setMatchId($match->getId());
                    // TODO: verify whether the user exists or not.
                    $member->setStadioomId($memberId);
                    $member->setInvolvedTeam(1);
                    $this->em->persist($member);
                }
                foreach ($teamAFbIds as $memberId) {
                    $member = new Entities\MatchRecordPlayer();
                    $member->setMatchId($match->getId());
                    $member->setFbId($memberId);
                    $member->setInvolvedTeam(1);
                    $this->em->persist($member);
                }
                foreach ($teamBStIds as $memberId) {
                    $member = new Entities\MatchRecordPlayer();
                    $member->setMatchId($match->getId());
                    // TODO: verify whether the user exists or not.
                    $member->setStadioomId($memberId);
                    $member->setInvolvedTeam(2);
                    $this->em->persist($member);
                }
                foreach ($teamBFbIds as $memberId) {
                    $member = new Entities\MatchRecordPlayer();
                    $member->setMatchId($match->getId());
                    $member->setFbId($memberId);
                    $member->setInvolvedTeam(2);
                    $this->em->persist($member);
                }
                $this->em->commit();
            } catch (Exception $e) {
                $this->em->rollback();
                throw $e;
            }
        } else {    // team match
            if ($teamA == null || $teamB == null) {
                throw new Exception("Insufficient data ('teamA' and 'teamB' are required.)", 400);
            }

            // TODO implement...
            throw new Exception("Not Implemented.", 501);
        }
    }

    public function find($since, $limit, $page, $sportId) {
        if ($limit == null || $limit < 20) {
            $limit = 20;    // minimum limit is 20.
        } else if ($limit > 200) {
            $limit = 200;   // maximum limit is 200.
        }

        if ($since == null) {
            $since = new DateTime();
            $since->sub(new DateInterval('P1M'));   // a month ago.
        }

        if ($page == null || $page < 1) {
            $page = 1;  // default page is 1.
        }

        $dql = 'SELECT m, p FROM Entities\MatchRecord m JOIN m.players p WHERE m.lastUpdated >= ' . $since;
        if ($sportId != null) {
            $dql = $dql . ' m.sportId = ' . $sportId;
        }
        $dql = $dql . ' ORDER BY m.lattUpdated DESC';
        $q = $this->em->createQuery($dql);
        $result = $q->getResult();
        return $result;
    }

    public function delete($matchId, $userId) {
        $match = $this->em->find('Entities\MatchRecord', $matchId);
        if ($match == null) {
            throw new Exception("Not Found.", 404);
        }
        if ($match->getOwnerId() != $userId) {
            throw new Exception("Forbidden. You have no permission to delete this match.", 403);
        }

        $this->em->remove($match);
        $this->em->flush();
    }

}

?>
