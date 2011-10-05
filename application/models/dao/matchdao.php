<?php

class MatchDao extends CI_Model {

    private $em;

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');

        $this->em = $this->doctrine->em;
    }

    public function register($match, $memberFbIdsA, $memberFbIdsB) {
        $this->complete($match, $memberFbIdsA, $memberFbIdsB);

        $matchType = $match->getMatchType();

//        if ($matchType == 1) { // single match
            $this->em->persist($match);
            $this->em->flush();
            return $match->getId();
//        } else {    // team match
//            // TODO implement...
//            throw new Exception("Not Implemented.", 501);
//        }
    }

    public function complete(&$match, &$memberFbIdsA, &$memberFbIdsB) {
        if (is_array($memberFbIdsA)) {
            foreach ($memberFbIdsA as $member) {
                $fbId = $member['fbId'];
                $name = $member['name'];
                $user = $this->em->getRepository('Entities\User')->findOneByFbId($fbId);
                if ($user == null) {
                    $user = new Entities\User();
                    $user->setFbId($fbId);
                    $user->setName($name);
                    $user->setFbLinked(false);
                    $user->setFbAuthorized(false);
                    $user->setVerified(false);
                    
                    $this->em->persist($user);
                    $this->em->flush();
                }
                $newMember = new Entities\MatchRecordMemberA();
                $newMember->setUserId($user->getId());
                $newMember->setMatch($match);
                $match->addMemberIdsA($newMember);
            }
        }
        if (is_array($memberFbIdsB)) {
            foreach ($memberFbIdsB as $member) {
                $fbId = $member['fbId'];
                $name = $member['name'];
                $user = $this->em->getRepository('Entities\User')->findOneByFbId($fbId);
                if ($user == null) {
                    $user = new Entities\User();
                    $user->setFbId($fbId);
                    $user->setName($name);
                    $user->setFbLinked(false);
                    $user->setFbAuthorized(false);
                    $user->setVerified(false);
                    $this->em->persist($user);
                    $this->em->flush();
                }
                $newMember = new Entities\MatchRecordMemberB();
                $newMember->setUserId($user->getId());
                $newMember->setMatch($match);
                $match->addMemberIdsB($newMember);
            }
        }
    }

    public function find($matchId) {
        $match = $this->em->find('Entities\MatchRecord', $matchId);
        return $match->toArray();
    }

    public function findAll($since, $firstOffset, $limit, $sportId) {
        if ($limit == null || $limit < 10) {
            $limit = 10;    // minimum limit is 20.
        } else if ($limit > 200) {
            $limit = 200;   // maximum limit is 200.
        }

        if ($since == null) {
            $since = new DateTime();
            $since = $since->sub(new DateInterval('P1M'));   // a month ago.
            $since = $since->getTimestamp();
        }

        if ($firstOffset == null || $firstOffset < 0) {
            $firstOffset = 0;  // default page is 0.
        }

        // TODO: apply 'page' and 'limit'.
        $dql = 'SELECT m FROM Entities\MatchRecord m WHERE m.lastUpdated >= ' . $since;
        if ($sportId != null) {
            $dql = $dql . ' AND m.sportId = ' . $sportId;
        }
        $dql = $dql . ' ORDER BY m.lastUpdated DESC';
        $q = $this->em->createQuery($dql);
        $q->setMaxResults($limit);
        $q->setFirstResult($firstOffset);
        $result = $q->getResult();
        $allMatches = array();
        if (is_array($result)) {
            foreach ($result as $match) {
                array_push($allMatches, $match->toArray());
            }
        }
        return $allMatches;
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
