<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Match extends Stadioom_REST_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('dao/MatchDao');

        if (function_exists('force_ssl'))
            remove_ssl();
    }

    /**
     * Registers a new match.
     */
    public function index_post() {
        $accessToken = $this->post('accessToken');

        try {
            $userId = $this->verifyToken($accessToken);

            // fill data
            $match = new Entities\MatchRecord();
            $match->setBrandId($this->post('brandId'));
            $match->setCanceled($this->post('canceled'));
            $match->setLatitude($this->post('latitude'));
            $match->setLocation($this->post('location'));
            $match->setLongitude($this->post('longitude'));
            $match->setOwnerId($userId);
            $match->setSportId($this->post('sportId'));
            $match->setStarted($this->post('started'));
            $match->setEnded($this->post('ended'));

            $match->setScoreA($this->post('scoreA'));
            $match->setScoreB($this->post('scoreB'));
            $match->setTitle($this->post('title'));

            $this->post('shared');

            $match->setTeamAId($this->post('teamA'));
            $match->setTeamBId($this->post('teamB'));

            $memberIds = $this->post('memberIdsA');
            if (is_array($memberIds)) {
                foreach ($memberIds as $id) {
                    $member = new Entities\MatchRecordMemberA();
                    $member->setUserId($id);
                    $match->addMemberIdsA($member);
                    $member->setMatch($match);
                }
            }
            $memberIds = $this->post('memberIdsB');
            if (is_array($memberIds)) {
                foreach ($memberIds as $id) {
                    $member = new Entities\MatchRecordMemberB();
                    $member->setUserId($id);
                    $match->addMemberIdsB($member);
                    $member->setMatch($match);
                }
            }

            $memberFbIdsA = $this->post('memberFbIdsA');
            $memberFbIdsB = $this->post('memberFbIdsB');
            $fbAccessToken = $this->post('fbAccessToken');

            $matchId = $this->MatchDao->register($match, $memberFbIdsA, $memberFbIdsB, $fbAccessToken);

            $this->responseOk(array("id" => $matchId));
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function share_post() {
        $accessToken = $this->post('accessToken');
        try {
            $userId = $this->verifyToken($accessToken);

            $sharedInfo = new Entities\MatchRecordShare();
            $sharedInfo->setSharedBy($userId);
            $sharedInfo->setMatchId($this->post('matchId'));
            $sharedTarget = $this->post('targetMedia');
            if ($sharedTarget == null) {
                $sharedTarget = 'Facebook';
            }
            $sharedInfo->setTargetMedia($sharedTarget);
            $sharedInfo->setLink($this->post('link'));
            $sharedInfo->setComment($this->post('comment'));

            $sharedId = $this->MatchDao->shared($sharedInfo);

            $this->responseOk(array("id" => $sharedId));
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    /**
     * Deletes the specified match.
     */
    public function index_delete() {
        $accessToken = $this->delete('accessToken');

        try {
            $userId = $this->verifyToken($accessToken);
            $matchId = $this->delete('matchId');

            $this->MatchDao->deleteMatch($matchId, $userId);
        } catch (Exception $e) {
            $this->responseError($e);
        }
        $this->responseError(new Exception("Not Implemented.", 501));
    }

    /**
     * Returns a list of registered matches or the specified match.
     */
    public function index_get() {
//        $accessToken = $this->get('accessToken');

        try {
//            $userId = $this->verifyToken($accessToken);

            $matchId = $this->get('matchId');
            if ($matchId != null) {
                $match = $this->MatchDao->find($matchId);
                $this->responseOk($match);
            } else {
                $options = array('since' => $this->get('since'),
                    'firstOffset' => $this->get('firstOffset'),
                    'limit' => $this->get('limit'),
                    'sportId' => $this->get('sportId'),
                    'ownerId' => $this->get('ownerId'),
                    'memberId' => $this->get('memberId'));

                $allMatches = $this->MatchDao->findAll($options);
                $data = array('data' => $allMatches);
                $this->responseOk($data);
            }
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    /**
     * Update a previously registered match.
     */
    public function index_put() {
        $accessToken = $this->put('accessToken');

        try {
            $invitorId = $this->verifyToken($accessToken);
        } catch (Exception $e) {
            $this->responseError($e);
        }
        $this->responseError(new Exception("Not Implemented.", 501));
    }

}

?>
