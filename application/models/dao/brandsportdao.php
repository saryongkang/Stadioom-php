<?php

class BrandSportDao extends CI_Model {

    /**
     *
     * @var \Doctrine\EntityManager 
     */
    private $em;

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');

        $this->em = $this->doctrine->em;
    }

    /**
     * Add a new sport.
     *
     * @param Entities\Brand $brand
     */
    public function addBrand(&$brand) {
        log_message('debug', "addBrand: enter.");

        if (!$this->isInRange($brand->getName(), 5, 32)) {
            throw new Exception("Invalid name (5 <= name <= 32).", 400);
        }

        $prev = $this->em->getRepository('Entities\Brand')->findOneByName($brand->getName());
        if ($prev == null) {
            $this->em->persist($brand);
            $this->em->flush();
        }
        $added = $this->em->getRepository('Entities\Brand')->findOneByName($brand->getName());

        log_message('debug', "addBrand: exit.");
        return $added->getId();
    }

    /**
     * Add a new sport.
     *
     * @param Entities\Sport $sport
     */
    public function addSport(&$sport) {
        log_message('debug', "addSport: enter.");

        if (!$this->isInRange($sport->getName(), 5, 32)) {
            throw new Exception("Invalid name (5 <= name <= 32).", 400);
        }

        $prev = $this->em->getRepository('Entities\Sport')->findOneByName($sport->getName());
        if ($prev == null) {
            $this->em->persist($sport);
            $this->em->flush();
        }
        $added = $this->em->getRepository('Entities\Sport')->findOneByName($sport->getName());

        log_message('debug', "addSport: exit.");
        return $added->getId();
    }

    /**
     * Delete the given brand.
     *
     * @param integer $id Brand ID to delete.
     */
    public function removeBrand(&$id) {
        log_message('debug', "removeBrand: enter.");

        $brand = $this->em->find('Entities\Brand', $id);
        if ($brand != null) {
            $this->em->remove($brand);
            $this->em->flush();
        }

        log_message('debug', "removeBrand: exit.");
    }

    /**
     * Delete the given sport.
     *
     * @param integer $id Soort ID to delete.
     */
    public function removeSport(&$id) {
        log_message('debug', "removeSport: enter.");

        $sport = $this->em->find('Entities\Sport', $id);
        if ($sport != null) {
            $this->em->remove($sport);
            $this->em->flush();
        }

        log_message('debug', "removeSport: exit.");
    }

    public function getBrand(&$id) {
        log_message('debug', "getBrand: enter.");

        if (!(is_numeric($id) && $id > 0)) {
            throw new Exception("Invalid ID: " . $id, 400);
        }
        $brand = $this->em->find('Entities\Brand', $id);
        if ($brand == null) {
            throw new Exception("Not Found.", 404);
        }

        log_message('debug', "getBrand: exit.");
        return $brand;
    }

    public function getSport(&$id) {
        log_message('debug', "getSport: enter.");

        if (!(is_numeric($id) && $id > 0)) {
            throw new Exception("Invalid ID: " . $id, 400);
        }
        $sport = $this->em->find('Entities\Sport', $id);
        if ($sport == null) {
            throw new Exception("Not Found.", 404);
        }

        log_message('debug', "getSport: exit.");
        return $sport;
    }

    public function getAllBrands() {
        log_message('debug', "getAllBrands: enter.");

        $q = $this->em->createQuery('SELECT b FROM Entities\Brand b ORDER BY b.priority DESC');

        log_message('debug', "getAllBrands: exit.");
        return $q->getResult();
    }

    public function getAllSports() {
        log_message('debug', "getAllSports: enter.");

        $q = $this->em->createQuery('SELECT s FROM Entities\Sport s ORDER BY s.priority DESC');

        log_message('debug', "getAllSports: exit.");
        return $q->getResult();
    }

    public function findBrandsAfter($after) {
        log_message('debug', "findBrandsAfter: enter.");
        
        if ($after == null || $after < 0) {
            $after = 0;
        }

        $q = $this->em->createQuery('SELECT b FROM Entities\Brand b WHERE b.latestRevision > ' . $after);
        
        log_message('debug', "findBrandsAfter: exit.");
        return $q->getResult();
    }

    public function findSportsAfter($after) {
        log_message('debug', "findSportsAfter: enter.");
        
        if ($after == null || $after < 0) {
            $after = 0;
        }

        $q = $this->em->createQuery('SELECT s FROM Entities\Sport s WHERE s.latestRevision > ' . $after);

        log_message('debug', "findSportsAfter: exit.");
        return $q->getResult();
    }

    public function findAllSponsorsOf($sportId) {
        log_message('debug', "findAllSponsorsOf: enter.");
        
        if ($sportId == null) {
            throw new Exception("sportId is required.", 400);
        }

        $q = $this->doctrine->em->createQuery("SELECT b, s FROM Entities\Brand b JOIN b.sports s WHERE s.id = " . $sportId);

        log_message('debug', "findAllSponsorsOf: exit.");
        return $q->getREsult();
    }

    private function isInRange(&$str, $min, $max) {
        $length = strlen($str);
        return $length == 0 || ($min <= $length && $length <= $max);
    }

    public function link($brandId, $sportIds) {
        log_message('debug', "link: enter.");
        
        $brand = $this->em->find("Entities\Brand", $brandId);
        $sports = array();
        if (count($sportIds) > 0) {
            $q = $this->em->createQuery("SELECT s FROM Entities\Sport s WHERE s.id IN (" . implode(",", $sportIds) . ")");
            $sports = $q->getResult();
        }

        $linkedSports = $brand->getSports();
        $linkedSports->clear();
        foreach ($sports as $sport) {
            $brand->addSports($sport);
        }

        $this->em->flush();
        log_message('debug', "link: exit.");
    }

}

?>
