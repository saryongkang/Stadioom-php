<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Match extends Stadioom_REST_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('dao/MatchDao');

        force_ssl();
//        if (function_exists('force_ssl'))
//            remove_ssl();
    }

    /**
     * Registers a new match.
     */
    public function index_post() {
        log_message('debug', "index_post entered.");
        $accessToken = $this->post('accessToken');

        try {
            if ($accessToken != null) {
                $userId = $this->verifyToken($accessToken);
            } else {
                $this->security->csrf_verify();
                $user = $this->session->userdata('user');
                $userId = $user['id'];
                if ($userId == null) {
                    throw new Exception("'userId' was not set.", 400);
                }
            }

            // fill data
            $match = new Entities\MatchRecord();
            $match->setOwnerId($userId);

            $match->setBrandId($this->post('brandId'));
            $match->setSportId($this->post('sportId'));



            $format = "Y-m-d H:i:s";
            $started = DateTime::createFromFormat($format, $this->post('started'), new DateTimeZone("GMT"));
            if ($started != null) {
                $match->setStarted($started);
            }
            $ended = DateTime::createFromFormat($format, $this->post('ended'), new DateTimeZone("GMT"));
            if ($ended != null) {
                $match->setEnded($ended);
            }
            $canceled = DateTime::createFromFormat($format, $this->post('canceled'), new DateTimeZone("GMT"));
            if ($canceled != null) {

                $match->setCanceled($canceled);
            }
            $match->setLocation($this->post('location'));
            $match->setLatitude($this->post('latitude'));
            $match->setLongitude($this->post('longitude'));

            $match->setTitle($this->post('title'));

            $match->setScoreA($this->post('scoreA'));
            $match->setScoreB($this->post('scoreB'));

            $match->setTeamAId($this->post('teamA'));
            $match->setTeamBId($this->post('teamB'));

            $memberIdsA = $this->post('memberIdsA');
            $memberIdsB = $this->post('memberIdsB');
            $memberFbIdsA = $this->post('memberFbIdsA');
            $memberFbIdsB = $this->post('memberFbIdsB');

            log_message('debug', "trying to register.");
            $matchId = $this->MatchDao->register($match, $memberIdsA, $memberIdsB, $memberFbIdsA, $memberFbIdsB);
            log_message('debug', "done.");

            log_message('debug', "index_post exit.");
            $this->responseOk(array("id" => $matchId));
        } catch (Exception $e) {
            log_message('debug', "index_post exit with error: " . $e->getMessage());
            $this->responseError($e);
        }
    }

    public function share_post($id) {
        // TODO: not properly implemented.
        $accessToken = $this->post('accessToken');
        try {
            $userId = $this->verifyToken($accessToken);

            $sharedInfo = new Entities\MatchRecordShare();
            $sharedInfo->setSharedBy($userId);
            $sharedInfo->setMatchId($id);
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
    public function match_delete($id) {
        $accessToken = $this->delete('accessToken');

        try {
            $userId = $this->verifyToken($accessToken);

            $this->MatchDao->deleteMatch($id, $userId);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    /**
     * Returns a list of registered matches or the specified match.
     */
    public function index_get() {
        try {
            $matchId = $this->get('matchId');
            if ($matchId != null) {
                // TODO this path is deprecated by 'match_get'.
                $match = $this->MatchDao->find($matchId);
                $this->responseOk($match);
            } else {
                $options = array(/*'since' => $this->get('since'),*/
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
     * Returns the specified match.
     */
    public function match_get($id) {
        try {
            $match = $this->MatchDao->find($matchId);
            $this->responseOk($match);
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

    /**
     * Returns the historical record of the specified user.
     */
    public function record_get() {
        $userId = $this->get('userId');
        try {
            $record = $this->MatchDao->getRecord($userId);
            $this->responseOk($record);
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

}

?>
