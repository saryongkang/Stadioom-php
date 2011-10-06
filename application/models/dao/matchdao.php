<?php

class MatchDao extends CI_Model {

    private $em;

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');

        $this->em = $this->doctrine->em;
    }

    public function register($match, $memberFbIdsA, $memberFbIdsB, $fbAccessToken) {
        $this->complete($match, $memberFbIdsA, $memberFbIdsB, $fbAccessToken);

        $this->em->persist($match);
        $this->em->flush();
        return $match->getId();
    }

    public function shared($sharedInfo) {
        // TODO check input.

        $this->em->persist($sharedInfo);
        $this->em->flush();
        return $sharedInfo->getId();
    }

    public function complete(&$match, &$memberFbIdsA, &$memberFbIdsB, $fbAccessToken) {
        if (is_array($memberFbIdsA)) {
            $me = $this->em->find('Entities\User', $match->getOwnerId());
//            $myFbInfo = $this->em->getRepository('Entities\UserFb')->findOneByFbId($me->getFbId());
//            $fbAccessToken = $myFbInfo->getFbAccessToken();

            $this->load->library('fb_connect');
            $this->fb_connect->setAccessToken($fbAccessToken);

            foreach ($memberFbIdsA as $fbId) {
                $user = $this->em->getRepository('Entities\User')->findOneByFbId($fbId);
                if ($user == null) {
                    $user = new Entities\User();

                    try {
                        $myFbInfo = $this->em->getRepository('Entities\UserFb')->findOneByFbId($fbId);
                        $fbFriend = $this->fb_connect->api('/' . $fbId);
                    } catch (FacebookApiException $e) {
                        throw new Exception("Failed to get authorized by Facebook.", 401, $e);
                    }

                    // fill user table
                    $user->setFbId($fbId);
                    $user->setName($fbFriend['name']);
                    $user->setGender($fbFriend['gender']);
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
                    $userFb->setName($fbFriend['name']);
                    $userFb->setGender($fbFriend['gender']);
                    $userFb->setLocale($fbFriend['locale']);

                    $this->em->persist($userFb);
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

                    try {
                        $fbFriend = $this->fb_connect->api('/' . $fbId);
                    } catch (FacebookApiException $e) {
                        throw new Exception("Failed to get authorized by Facebook.", 401, $e);
                    }

                    // fill user table
                    $user->setFbId($fbId);
                    $user->setName($fbFriend['name']);
                    $user->setGender($fbFriend['gender']);
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
                    $userFb->setName($fbFriend['name']);
                    $userFb->setGender($fbFriend['gender']);
                    $userFb->setLocale($fbFriend['locale']);

                    $this->em->persist($userFb);
                }

                $newMember = new Entities\MatchRecordMemberB();
                $newMember->setUserId($user->getId());
                $newMember->setMatch($match);
                $match->addMemberIdsB($newMember);
            }
        }

        $this->em->flush();
    }

    public function find($matchId) {
        $match = $this->em->find('Entities\MatchRecord', $matchId);
        return $match->toArray();
    }

//    public function findAll($since, $firstOffset, $limit, $sportId) {
//        if ($limit == null || $limit < 10) {
//            $limit = 10;    // minimum limit is 20.
//        } else if ($limit > 200) {
//            $limit = 200;   // maximum limit is 200.
//        }
//
//        if ($since == null) {
//            $since = new DateTime();
//            $since = $since->sub(new DateInterval('P1M'));   // a month ago.
//            $since = $since->getTimestamp();
//        }
//
//        if ($firstOffset == null || $firstOffset < 0) {
//            $firstOffset = 0;  // default page is 0.
//        }
//
//        // TODO: apply 'page' and 'limit'.
//        $dql = 'SELECT m FROM Entities\MatchRecord m WHERE m.lastUpdated >= ' . $since;
//        if ($sportId != null) {
//            $dql = $dql . ' AND m.sportId = ' . $sportId;
//        }
//        $dql = $dql . ' ORDER BY m.lastUpdated DESC';
//        $q = $this->em->createQuery($dql);
//        $q->setMaxResults($limit);
//        $q->setFirstResult($firstOffset);
//        $result = $q->getResult();
//        $allMatches = array();
//        if (is_array($result)) {
//            foreach ($result as $match) {
//                array_push($allMatches, $match->toArray());
//            }
//        }
//        return $allMatches;
//    }

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

    public function findAll($options) {
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
        return $allMatches;
    }

    public function deleteMatch($matchId, $userId) {
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
