<?php

class MatchDao extends CI_Model {

    private $em;

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');

        $this->em = $this->doctrine->em;
    }

    /**
     * Registers a new match.
     * 
     * @param Entities\MatchRecord $match
     * @param array $memberFbIdsA
     * @param array $memberFbIdsB 
     * @param string $fbAccessToken 
     */
    public function register($match, $memberFbIdsA, $memberFbIdsB) {
        log_message('debug', "register: enter.");
        $this->validateMatch($match, $memberFbIdsA, $memberFbIdsB);

        $this->completeMembers($match, $memberFbIdsA, $memberFbIdsB);
        $this->completeTitle($match);

        $this->em->persist($match);
        $this->em->flush();

        // make member names A
        // make member names B
        // make caption (score, who's win, or even or not played..)
        // get brand String ID
        // get sport String ID
        // get brand name
        // get sport name
//        $result = array(
//            'matchId' => $match->getId(),
//            'caption' => $memberNamesA . ' just defeated ' . $mamberNamesB . ' in a fierce ' . $matchTitle . ' match.',
//            'message' => $subject . " " . $matchResult,
//            'picture' => "http://stadioom.com/assets/images/sponsors/shareicons/" . $brandStringId . "_" . $sportStringId . "_shareicon.gif",
//            'title' => $sponsorName . " " . $sportName . " Match",
//            'link' => "http://stadioom.com/match/view/" . $match->getId(),
//            'description' => "Final score: " . $memberNamesA . " " . $match->getScoreA() . " - " . $memberNamesB . " " . $match->getScoreB()
//        );
        log_message('debug', "register: exit.");
        return $match->getId();
    }

    /**
     * Verify the inputs.
     * 
     * @param Entities\MatchRecord $match
     * @param array $memberFbIdsA
     * @param array $memberFbIdsB 
     */
    private function validateMatch($match, $memberFbIdsA, $memberFbIdsB) {
        log_message('debug', "validateMatch: enter.");
//        $matchType = $match->getMatchType();
//        if ($matchType == 1) { // single match
//            $numA = count($match->getMemberIdsA()) + count($memberFbIdsA);
//            $numB = count($match->getMemberIdsB()) + count($memberFbIdsB);
//
//            if ($numA != 1 || $numB != 1) {
//                throw new Exception("Number of both teams' members should be 1, but " . $numA . " and " . $numB, 400);
//            }
//        } else if ($matchType == 2) { // team match
//            $numA = count($match->getMemberIdsA()) + count($memberFbIdsA);
//            $numB = count($match->getMemberIdsB()) + count($memberFbIdsB);
//
//            if ($numA < 1 || $numB < 1) {
//                throw new Exception("Number of both teams' members should be greater than 0, but " . $numA . " and " . $numB, 400);
//            }
//        } else {
//            throw new Exception("Unsupported match type: " . $match->getMatchType(), 400);
//        }
//        // team A and team B should exclusive.
//        
//        // all member's are registered?
//        $members = count($match->getMemberIdsA());
//        // make IN (...) statement, than get count, then compare with total required numbers.
//        foreach ($members as $member) {
//            $q = $this->em->createQuery("SELECT u FROM Entities\User WHERE id = " . $member->getId());
//            $users = $q->getResult();
//            if (count($users) == )
//        }
        log_message('debug', "validateMatch: exit.");
    }

    public function shared($sharedInfo) {
        log_message('debug', "share: enter.");
        // TODO check input.

        $this->em->persist($sharedInfo);
        $this->em->flush();

        log_message('debug', "share: exit.");
        return $sharedInfo->getId();
    }

    public function completeMembers(&$match, &$memberFbIdsA, &$memberFbIdsB) {
        log_message('debug', "completeMembers: enter.");

        if (is_array($memberFbIdsA)) {
            $me = $this->em->find('Entities\User', $match->getOwnerId());
            foreach ($memberFbIdsA as $fbId) {
                $user = $this->em->getRepository('Entities\User')->findOneByFbId($fbId);
                if ($user == null) {
                    $user = new Entities\User();

                    $fbFriend = file_get_contents("http://graph.facebook.com/" . $fbId);
                    $fbFriend = json_decode($fbFriend);

                    // fill user table
                    $user->setFbId($fbId);
                    $user->setName($fbFriend->name);
                    $user->setGender($fbFriend->gender);
                    $user->setFbLinked(false);
                    $user->setFbAuthorized(false);
                    $user->setVerified(false);

                    $this->em->persist($user);

                    // fill user fb table.
                    $userFb = $this->em->getRepository('Entities\UserFb')->findOneByFbId($fbId);
                    if ($userFb == null) {
                        $userFb = new Entities\UserFb();
                        $userFb->setFbId($fbId);
                    }
                    $userFb->setName($fbFriend->name);
                    $userFb->setGender($fbFriend->gender);
                    $userFb->setLocale($fbFriend->locale);

                    $this->em->persist($userFb);
                    $this->em->flush();
                }

                $newMember = new Entities\MatchRecordMemberA();
                $newMember->setUserId($user->getId());
                $newMember->setMatch($match);
                $match->addMemberIdsA($newMember);
            }
        }
        if (is_array($memberFbIdsB)) {
            foreach ($memberFbIdsB as $fbId) {
                $user = $this->em->getRepository('Entities\User')->findOneByFbId($fbId);
                if ($user == null) {
                    $user = new Entities\User();

                    $fbFriend = file_get_contents("http://graph.facebook.com/" . $fbId);
                    $fbFriend = json_decode($fbFriend);

                    // fill user table
                    $user->setFbId($fbId);
                    $user->setName($fbFriend->name);
                    $user->setGender($fbFriend->gender);
                    $user->setFbLinked(false);
                    $user->setFbAuthorized(false);
                    $user->setVerified(false);

                    $this->em->persist($user);

                    // fill user fb table.
                    $userFb = $this->em->getRepository('Entities\UserFb')->findOneByFbId($fbId);
                    if ($userFb == null) {
                        $userFb = new Entities\UserFb();
                        $userFb->setFbId($fbId);
                    }
                    $userFb->setName($fbFriend->name);
                    $userFb->setGender($fbFriend->gender);
                    $userFb->setLocale($fbFriend->locale);

                    $this->em->persist($userFb);
                    $this->em->flush();
                }

                $newMember = new Entities\MatchRecordMemberB();
                $newMember->setUserId($user->getId());
                $newMember->setMatch($match);
                $match->addMemberIdsB($newMember);
            }
        }

        log_message('debug', "completeMembers: exit.");
        $this->em->flush();
    }

    private function completeTitle($match) {
        log_message('debug', "completeTitle: enter.");

        $title = $match->getTitle();
        if ($title == null) {
            // get sport name
            $sportId = $match->getSportId();
            $sportName = $this->em->createQuery("SELECT s.name FROM Entities\Sport s WHERE s.id = " . $sportId);
            if (count($sportName) == 0) {
                throw new Exception("Unknown Sport: " . $sportId, 404);
            }
            $title = $sportName[0]['name'];

            // get brand name (if exists)
            $brandId = $match->getBrandId();
            if ($brandId != null) {
                $brandName = $this->em->createQuery("SELECT b.name FROM Entities\Brand b WHERE b.id = " . $brandId);
                if (count($brandName) == 0) {
                    throw new Exception("Unknown Brand: " . $sportId, 404);
                }

                $title = $title . ' ' . $brandName[0]['name'];
            }

            // append 'Match'
            $title = $title . ' Match';
            $match->setTitle($title);
        }

        log_message('debug', "completeTitle: exit.");
    }

    public function find($matchId) {
        log_message('debug', "find: enter.");
        $match = $this->em->find('Entities\MatchRecord', $matchId);
        if ($match == null) {
            log_message('error', "Not Found: " . $matchId);
            throw new Exception("Not Found: " . $matchId, 404);
        }

        log_message('debug', "find: exit.");
        return $match->toArray();
    }

    public function delete($matchId, $userId) {
        log_message('debug', "delete: enter.");

        $match = $this->em->find('Entities\MatchRecord', $matchId);
        if ($match == null) {
            log_message('error', "Not Found: " . $matchId);
            throw new Exception("Not Found: " . $matchId, 404);
        }
        if ($match->getOwnerId() != $userId) {
            log_message('error', "Forbidden. You have no permission to delete this match.");
            throw new Exception("Forbidden. You have no permission to delete this match.", 403);
        }

        $this->em->remove($match);
        $this->em->flush();

        log_message('debug', "delete: exit.");
    }

    public function findAll($options) {
        log_message('debug', "findAll: enter.");

        $since = $options['since'];
        $firstOffset = $options['firstOffset'];
        $limit = $options['limit'];
        $sportId = $options['sportId'];
        $ownerId = $options['ownerId'];
        $memberId = $options['memberId'];

        if ($firstOffset == null || $firstOffset < 0) {
            $firstOffset = 0;  // default page is 0.
        }

        if ($limit == null || $limit < 10) {
            $limit = 10;    // minimum limit is 10.
        } else if ($limit > 200) {
            $limit = 200;   // maximum limit is 200.
        }

        if ($since == null) {
            $since = new DateTime();
            $since = $since->sub(new DateInterval('P1M'));   // a month ago.
            $since = $since->getTimestamp();
        }

        $dql = 'SELECT m';
        if ($memberId != null) {
            $dql = $dql . ', a, b';
        }
        $dql = $dql . ' FROM Entities\MatchRecord m';
        if ($memberId != null) {
            $dql = $dql . ' JOIN m.memberIdsA a JOIN m.memberIdsB b';
        }
        if ($since != null || $sportId != null || $ownerId != null || $memberId != null) {
            $dql = $dql . ' WHERE';

            $first = true;
            if ($since != null) {
                if (!$first) {
                    $dql = $dql . ' AND';
                }
                $dql = $dql . ' m.lastUpdated >= ' . $since;
                $first = false;
            }
            if ($sportId != null) {
                if (!$first) {
                    $dql = $dql . ' AND';
                }
                $dql = $dql . ' m.sportId = ' . $sportId;
                $first = false;
            }
            if ($ownerId != null) {
                if (!$first) {
                    $dql = $dql . ' AND';
                }
                $dql = $dql . ' m.ownerId >= ' . $ownerId;
                $first = false;
            }
            if ($memberId != null) {
                if (!$first) {
                    $dql = $dql . ' AND';
                }
                $dql = $dql . ' (a.userId = ' . $memberId . ' OR b.userId = ' . $memberId . ')';
                $first = false;
            }
        }
        $dql = $dql . ' ORDER BY m.lastUpdated DESC';
        error_log('=============' . $dql);
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

        log_message('debug', "findAll: exit.");
        return $allMatches;
    }

    public function deleteMatch($matchId, $userId) {
        log_message('debug', "deleteMatch: enter.");

        $match = $this->em->find('Entities\MatchRecord', $matchId);
        if ($match != null) {
            if ($match->getOwnerId() != $userId) {
                log_message('error', "Forbidden. You have no permission to delete this match.");
                throw new Exception("Forbidden. You have no permission to delete this match.", 403);
            }

            $this->em->remove($match);
            $this->em->flush();
        }

        log_message('debug', "deleteMatch: exit.");
    }

}

?>
