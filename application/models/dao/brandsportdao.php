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
        if (!$this->isInRange($brand->getName(), 5, 32)) {
            throw new Exception("Invalid name (5 <= name <= 32).", 400);
        }

        $prev = $this->em->getRepository('Entities\Brand')->findOneByName($brand->getName());
        if ($prev == null) {
            $this->em->persist($brand);
            $this->em->flush();
        }
        $added = $this->em->getRepository('Entities\Brand')->findOneByName($brand->getName());
        return $added->getId();
    }

    /**
     * Add a new sport.
     *
     * @param Entities\Sport $sport
     */
    public function addSport(&$sport) {
        if (!$this->isInRange($sport->getName(), 5, 32)) {
            throw new Exception("Invalid name (5 <= name <= 32).", 400);
        }

        $prev = $this->em->getRepository('Entities\Sport')->findOneByName($sport->getName());
        if ($prev == null) {
            $this->em->persist($sport);
            $this->em->flush();
        }
        $added = $this->em->getRepository('Entities\Sport')->findOneByName($sport->getName());
        return $added->getId();
    }

    /**
     * Delete the given brand.
     *
     * @param integer $id Brand ID to delete.
     */
    public function removeBrand(&$id) {
        $brand = $this->em->find('Entities\Brand', $id);
        if ($brand != null) {
            $this->em->remove($brand);
            $this->em->flush();
        }
    }

    /**
     * Delete the given sport.
     *
     * @param integer $id Soort ID to delete.
     */
    public function removeSport(&$id) {
        $sport = $this->em->find('Entities\Sport', $id);
        if ($sport != null) {
            $this->em->remove($sport);
            $this->em->flush();
        }
    }

    public function getBrand(&$id) {
        if (!(is_numeric($id) && $id > 0)) {
            throw new Exception("Invalid ID: " . $id, 400);
        }
        $brand = $this->em->find('Entities\Brand', $id);
        if ($brand == null) {
            throw new Exception("Not Found.", 404);
        }
        return $brand;
    }

    public function getSport(&$id) {
        if (!(is_numeric($id) && $id > 0)) {
            throw new Exception("Invalid ID: " . $id, 400);
        }
        $sport = $this->em->find('Entities\Sport', $id);
        if ($sport == null) {
            throw new Exception("Not Found.", 404);
        }
        return $sport;
    }

    public function getAllBrands() {
        $q = $this->em->createQuery('SELECT b FROM Entities\Brand b ORDER BY b.priority DESC');
        return $q->getResult();
    }

    public function getAllSports() {
        $q = $this->em->createQuery('SELECT s FROM Entities\Sport s ORDER BY s.priority DESC');
        return $q->getResult();
    }

    public function findBrandsAfter($after) {
        if ($after == null || $after < 0) {
            $after = 0;
        }

        $q = $this->em->createQuery('SELECT b FROM Entities\Brand b WHERE b.latestRevision > ' . $after);
        return $q->getResult();
    }

    public function findSportsAfter($after) {
        if ($after == null || $after < 0) {
            $after = 0;
        }

        $q = $this->em->createQuery('SELECT s FROM Entities\Sport s WHERE s.latestRevision > ' . $after);
        return $q->getResult();
    }
    
    public function findAllSponsorsOf($sportId) {
        if ($sportId == null) {
            throw new Exception("sportId is required.", 400);
        }
        
        $q = $this->doctrine->em->createQuery("SELECT b, s FROM Entities\Brand b JOIN b.sports s WHERE s.id = " . $sportId);

        return $q->getREsult();
    }
    
//    public function findAllSportsSponsoredBy($brandId) {
//        if ($brandId == null) {
//            throw new Exception("brand ID is required.");
//        }
//        $brand = $this->doctrine->em->find("Entities\Brand", $brandId);
//
//        return $bra->getSpos();
//
//        $array = array();
//        foreach ($spos as $spo) {
//            array_push($array, $spo->toArray());
//        }
//
//        if ($array == null) {
//            $this->responseError(new Exception("Not Found.", 404));
//        }
//
//        $data = array("data" => $array);
//        $this->responseOk($data);
//    }

    private function isInRange(&$str, $min, $max) {
        $length = strlen($str);
        return $length == 0 || ($min <= $length && $length <= $max);
    }

    public function link($brandId, $sportIds) {
        $brand = $this->em->find("Entities\Brand", $brandId);
        $sports = array();
        if (count($sportsIds) > 0) {
            $q = $this->em->createQuery("SELECT s FROM Entities\Sport s WHERE s.id IN (" . implode(",", $sportIds) . ")");
            $sports = $q->getResult();
        }

        $linkedSports = $brand->getSports();
        $linkedSports->clear();
        foreach ($sports as $sport) {
            $linkedSports->addSports($sport);
        }

        $this->em->flush();
    }

}

?>
