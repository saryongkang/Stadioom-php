<?php

namespace Entities;

/**
 * Entities\MatchRecordShare
 */
class MatchRecordShare {

    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $matchId
     */
    private $matchId;

    /**
     * @var string $comment
     */
    private $comment;

    /**
     * @var integer $sharedBy
     */
    private $sharedBy;

    /**
     * @var string $targetMedia
     */
    private $targetMedia;

    /**
     * @var string $link
     */
    private $link;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set sharedBy
     *
     * @param integer $sharedBy
     */
    public function setSharedBy($sharedBy) {
        $this->sharedBy = $sharedBy;
    }

    /**
     * Get sharedBy
     *
     * @return integer $sharedBy
     */
    public function getSharedBy() {
        return $this->sharedBy;
    }

    /**
     * Set targetMedia
     *
     * @param string $targetMedia
     */
    public function setTargetMedia($targetMedia) {
        $this->targetMedia = $targetMedia;
    }

    /**
     * Get targetMedia
     *
     * @return string $targetMedia
     */
    public function getTargetMedia() {
        return $this->targetMedia;
    }

    /**
     * Set link
     *
     * @param string $link
     */
    public function setLink($link) {
        $this->link = $link;
    }

    /**
     * Get link
     *
     * @return string $link
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * Set comment
     *
     * @param string $comment
     */
    public function setComment($comment) {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return string $comment
     */
    public function getComment() {
        return $this->comment;
    }

    /**
     * Set matchId
     *
     * @param integer $matchId
     */
    public function setMatchId($matchId) {
        $this->matchId = $matchId;
    }

    /**
     * Get matchId
     *
     * @return integer $matchId
     */
    public function getMatchId() {
        return $this->matchId;
    }

}