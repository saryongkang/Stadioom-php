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

//            $me = $this->em->find('Entities\User', $match->getOwnerId());
        if (is_array($memberFbIdsA)) {
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

                $match->addMembersA($user);
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

                $match->addMembersB($user);
            }
        }

        log_message('debug', "completeMembers: exit.");
        $this->em->flush();
    }
//    public function completeMembers(&$match, &$memberFbIdsA, &$memberFbIdsB) {
//        log_message('debug', "completeMembers: enter.");
//
//        if (is_array($memberFbIdsA)) {
////            $me = $this->em->find('Entities\User', $match->getOwnerId());
//            foreach ($memberFbIdsA as $fbId) {
//                $user = $this->em->getRepository('Entities\User')->findOneByFbId($fbId);
//                if ($user == null) {
//                    $user = new Entities\User();
//
//                    $fbFriend = file_get_contents("http://graph.facebook.com/" . $fbId);
//                    $fbFriend = json_decode($fbFriend);
//
//                    // fill user table
//                    $user->setFbId($fbId);
//                    $user->setName($fbFriend->name);
//                    $user->setGender($fbFriend->gender);
//                    $user->setFbLinked(false);
//                    $user->setFbAuthorized(false);
//                    $user->setVerified(false);
//
//                    $this->em->persist($user);
//
//                    // fill user fb table.
//                    $userFb = $this->em->getRepository('Entities\UserFb')->findOneByFbId($fbId);
//                    if ($userFb == null) {
//                        $userFb = new Entities\UserFb();
//                        $userFb->setFbId($fbId);
//                    }
//                    $userFb->setName($fbFriend->name);
//                    $userFb->setGender($fbFriend->gender);
//                    $userFb->setLocale($fbFriend->locale);
//
//                    $this->em->persist($userFb);
//                    $this->em->flush();
//                }
//
//                $newMember = new Entities\MatchRecordMemberA();
//                $newMember->setUserId($user->getId());
//                $newMember->setMatch($match);
//                $match->addMemberIdsA($newMember);
//            }
//        }
//        if (is_array($memberFbIdsB)) {
//            foreach ($memberFbIdsB as $fbId) {
//                $user = $this->em->getRepository('Entities\User')->findOneByFbId($fbId);
//                if ($user == null) {
//                    $user = new Entities\User();
//
//                    $fbFriend = file_get_contents("http://graph.facebook.com/" . $fbId);
//                    $fbFriend = json_decode($fbFriend);
//
//                    // fill user table
//                    $user->setFbId($fbId);
//                    $user->setName($fbFriend->name);
//                    $user->setGender($fbFriend->gender);
//                    $user->setFbLinked(false);
//                    $user->setFbAuthorized(false);
//                    $user->setVerified(false);
//
//                    $this->em->persist($user);
//
//                    // fill user fb table.
//                    $userFb = $this->em->getRepository('Entities\UserFb')->findOneByFbId($fbId);
//                    if ($userFb == null) {
//                        $userFb = new Entities\UserFb();
//                        $userFb->setFbId($fbId);
//                    }
//                    $userFb->setName($fbFriend->name);
//                    $userFb->setGender($fbFriend->gender);
//                    $userFb->setLocale($fbFriend->locale);
//
//                    $this->em->persist($userFb);
//                    $this->em->flush();
//                }
//
//                $newMember = new Entities\MatchRecordMemberB();
//                $newMember->setUserId($user->getId());
//                $newMember->setMatch($match);
//                $match->addMemberIdsB($newMember);
//            }
//        }
//
//        log_message('debug', "completeMembers: exit.");
//        $this->em->flush();
//    }
//
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
            $dql .= ', a, b';
        }
        $dql .= ' FROM Entities\MatchRecord m';
        if ($memberId != null) {
            $dql .= ' JOIN m.membersA a JOIN m.membersB b';
        }
        if ($since != null || $sportId != null || $ownerId != null || $memberId != null) {
            $dql .= ' WHERE';

            $first = true;
            if ($since != null) {
                if (!$first) {
                    $dql .= ' AND';
                }
                $dql .= ' m.lastUpdated >= ' . $since;
                $first = false;
            }
            if ($sportId != null) {
                if (!$first) {
                    $dql .= ' AND';
                }
                $dql .= ' m.sportId = ' . $sportId;
                $first = false;
            }
            if ($ownerId != null) {
                if (!$first) {
                    $dql .= ' AND';
                }
                $dql .= ' m.ownerId >= ' . $ownerId;
                $first = false;
            }
            if ($memberId != null) {
                if (!$first) {
                    $dql .= ' AND';
                }
                $dql .= ' (a.id = ' . $memberId . ' OR b.id = ' . $memberId . ')';
                $first = false;
            }
        }
        $dql .= ' ORDER BY m.lastUpdated DESC';
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
