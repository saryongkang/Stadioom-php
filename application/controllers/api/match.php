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

            $matchType = $this->post('matchType');
            if ($matchType == null) {
                throw new Exception("Insufficient data ('matchType' is required.)", 400);
            }

            // fill data
            $match = new Entities\MatchRecord();
            $match->setBrandId($this->post('brandId'));
            $match->setCanceled($this->post('canceled'));
            $match->setLatitude($this->post('latitude'));
            $match->setLocation($this->post('location'));
            $match->setLongitude($this->post('longitude'));
            $match->setMatchType($matchType);
            $match->setOwnerId($userId);
            $match->setSportId($this->post('sportId'));
            $match->setStarted($this->post('started'));
            $match->setEnded($this->post('ended'));

            $match->setScoreA($scoreA);
            $match->setScoreB($scoreB);
            $match->setTitle($title);

            $this->post('shared');

            $args = array(
                'match' => $match,
                'teamA' => $this->post('teamA'),
                'teamB' => $this->post('teamB'),
                'teamAFbIds' => $this->post('teamAFbIds'),
                'teamAStIds' => $this->post('teamAStIds'),
                'teamBFbIds' => $this->post('teamBFbIds'),
                'teamBStIds' => $this->post('teamBStIds')
            );
            
            // add to dao.
            $this->MatchDao->register($args);
        } catch (Exception $e) {
            $this->responseError($e);
        }

        $this->responseError(new Exception("Not Implemented.", 501));
    }

    /**
     * Deletes the specified match.
     */
    public function index_delete() {
        $accessToken = $this->delete('accessToken');

        try {
            $userId = $this->verifyToken($accessToken);
            $matchId = $this->delete('matchId');

            $this->MatchDao->delete($matchId, $userId);
        } catch (Exception $e) {
            $this->responseError($e);
        }
        $this->responseError(new Exception("Not Implemented.", 501));
    }

    /**
     * Returns a list of registered matches or the specified match.
     */
    public function index_get() {
        $accessToken = $this->get('accessToken');

        try {
            $userId = $this->verifyToken($accessToken);

            $sportId = $this->get('sportId');
            $limit = $this->get('limit');
            $since = $this->get('since');
            $page = $this->get('page');

            return $this->MatchDao->find($since, $limit, $page, $sportId);
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
