<?php

namespace Entities;

/**
 * Entities\BrandSportMap
 */
class BrandSportMap
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $brandId
     */
    private $brandId;

    /**
     * @var integer $sportId
     */
    private $sportId;


    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set brandId
     *
     * @param integer $brandId
     */
    public function setBrandId($brandId)
    {
        $this->brandId = $brandId;
    }

    /**
     * Get brandId
     *
     * @return integer $brandId
     */
    public function getBrandId()
    {
        return $this->brandId;
    }

    /**
     * Set sportId
     *
     * @param integer $sportId
     */
    public function setSportId($sportId)
    {
        $this->sportId = $sportId;
    }

    /**
     * Get sportId
     *
     * @return integer $sportId
     */
    public function getSportId()
    {
        return $this->sportId;
    }
}