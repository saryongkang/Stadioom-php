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
    public function register($match, $memberIdsA, $memberIdsB, $memberFbIdsA, $memberFbIdsB) {
        log_message('debug', "register: enter.");
        $this->fillMembers($match, $memberIdsA, $memberIdsB, $memberFbIdsA, $memberFbIdsB);

        $this->fillTitle($match);
        $this->validateMatch($match);

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
    private function validateMatch($match) {
        log_message('debug', "validateMatch: enter.");
        // check sport/brand and their sponsorship
        $result = $this->em->createQuery("SELECT count(b) FROM Entities\Brand b JOIN b.sports s WHERE b.id = " . $match->getBrandId() . " AND s.id = " . $match->getSportId())->getResult();
        if (intval($result[0][1]) == 0) {
            log_message('error', "Invalid brand ID or sport ID, or the brand does not support the sport.");
            throw new Exception("Invalid brand ID or sport ID, or the brand does not support the sport.", 400);
        }

        // title length (> 0)
        $title = $match->getTitle();
        if ($title == null || strlen($title) == 0) {
            log_message('error', "Match title is required.");
            throw new Exception("Match title is required.", 400);
        }
        // leagueTupe (1, 2, or 3)
        $leagueType = $match->getLeagueType();
        if ($leagueType == null) {
            $match->setLeagueType(1);
        } else if ($leagueType < 0 && $leagueType > 3) {
            log_message('error', "Unsupported league type: " . $leagueType);
            throw new Exception("Unsupported league type: " . $leagueType, 400);
        }
        // teamAId/teamBId (later)
        // score (null or >= 0)
        $scoreA = $match->getScoreA();
        $scoreB = $match->getScoreB();
        if (($scoreA != null && $scoreA < 0)
                || ($scoreB != null && $scoreB < 0)) {
            log_message('error', "Scores must be grater than or equal to 0 (or empty)");
            throw new Exceptoin("Scores must be grater than or equal to 0 (or empty)", 400);
        }

        // count(memberA) > 1
        // count(memberB) > 1
        $memberA = $match->getMembersA();
        $memberB = $match->getMembersB();
        if (count($memberA) == 0 || count($memberB) == 0) {
            log_message('error', "Team does not have members.");
            throw new Exception("Team does not have members.", 400);
        }

        // started/ended/canceled (null or >=0)
        $started = $match->getStarted();
        $ended = $match->getEnded();
        $canceled = $match->getCanceled();
        if (($started != null && $started < 0)
                || ($ended != null && $ended < 0)
                || ($canceled != null && $canceled < 0)) {
            log_message('error', "Started/ended/canceled time must be grater than or equal to 0 (or empty)");
            throw new Exceptoin("Started/ended/canceled time must be grater than or equal to 0 (or empty)", 400);
        }

        // location (what should I check?)
        // latitude/longitude (null or >=0)
        $latitude = $match->getLatitude();
        $longitude = $match->getLongitude();
        if (($latitude != null && $latitude < 0)
                || ($longitude != null && $longitude < 0)) {
            log_message('error', "Latitude and longitude must be grater than or equal to 0 (or empty)");
            throw new Exceptoin("Latitude and longitude must be grater than or equal to 0 (or empty)", 400);
        }

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

    private function fillMembers(&$match, &$memberIdsA, &$memberIdsB, &$memberFbIdsA, &$memberFbIdsB) {
        log_message('debug', "fillMembers: enter.");

        // TODO validate memberIdsA and memberIdsB
        // validates members in memberFbIdsA
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
                    if (isset($fbFriend->gender)) {
                        $user->setGender($fbFriend->gender);
                    }
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
                    $userFb->setGender($user->getGender());
                    $userFb->setLocale($fbFriend->locale);

                    $this->em->persist($userFb);
                    $this->em->flush();
                }

                $match->addMembersA($user);
            }
        }
        // validates members in memberFbIdsB
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
                    if (isset($fbFriend->gender)) {
                        $user->setGender($fbFriend->gender);
                    }
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
                    $userFb->setGender($user->getGender());
                    $userFb->setLocale($fbFriend->locale);

                    $this->em->persist($userFb);
                    $this->em->flush();
                }

                $match->addMembersB($user);
            }
        }

        log_message('debug', "fillMembers: exit.");
        $this->em->flush();
    }

    private function fillTitle($match) {
        log_message('debug', "fillTitle: enter.");

        $title = $match->getTitle();
        if ($title == null || $title == "" || $title == "null") {
            // get sport name
            $sportId = $match->getSportId();
            $sportName = $this->em->createQuery("SELECT s.name FROM Entities\Sport s WHERE s.id = " . $sportId)->getResult();
            ;
            if (count($sportName) == 0) {
                throw new Exception("Unknown Sport: " . $sportId, 404);
            }
            $title = $sportName[0]['name'];

            // get brand name (if exists)
            $brandId = $match->getBrandId();
            if ($brandId != null) {
                $brandName = $this->em->createQuery("SELECT b.name FROM Entities\Brand b WHERE b.id = " . $brandId)->getResult();
                ;
                if (count($brandName) == 0) {
                    throw new Exception("Unknown Brand: " . $sportId, 404);
                }
                $title .= ' ' . $brandName[0]['name'];
            }

            // append 'Match'
            $title .= ' Match';
            $match->setTitle($title);
        }

        log_message('debug', "fillTitle: exit.");
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

    public function getRecord($userId) {
        if ($userId == null || !is_numeric($userId)) {
            throw new Exception("Valid 'userId' is required.", 400);
        }

        $record = array();
        $total = $this->em->createQuery("SELECT count(DISTINCT m) FROM Entities\MatchRecord m JOIN m.membersA a JOIN m.membersB b WHERE m.ended > 0 AND (a.id = " . $userId . " OR b.id = " . $userId . ")")->getResult();
        $record['total'] = intval($total[0][1]);
        $win = $this->em->createQuery("SELECT count(DISTINCT m) FROM Entities\MatchRecord m JOIN m.membersA a JOIN m.membersB b WHERE m.ended > 0 AND ((a.id = " . $userId . " AND m.scoreA > m.scoreB) OR (b.id = " . $userId . " AND m.scoreA < m.scoreB))")->getResult();
        $record['win'] = intval($win[0][1]);
        $tie = $this->em->createQuery("SELECT count(DISTINCT m) FROM Entities\MatchRecord m JOIN m.membersA a JOIN m.membersB b WHERE m.ended > 0 AND m.scoreA = m.scoreB AND (a.id = " . $userId . " OR b.id = " . $userId . ")")->getResult();
        $record['tie'] = intval($tie[0][1]);
        $record['lose'] = $record['total'] - $record['win'] - $record['tie'];

        return $record;
    }

}

?>
