<?php

class BrandSportMapDao extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');
    }

    public function link(&$brandId, &$sportId) {
        $q = $this->doctrine->em->createQuery('SELECT m FROM Entities\BrandSportMap m WHERE m.brandId = ' . $brandId . ' AND m.sportId = ' . $sportId);
        $result = $q->getResult();
        if (array_count_values($result) == 0) {
            $map = new Entities\BrandSportMap();
            $map->setBrandId($brandId);
            $map->setSportId($sportId);

            $this->doctrine->em->persist($map);
            $this->doctrine->em->flush();
        }
    }

    public function unlink(&$brandId, &$sportId) {
        $q = $this->doctrine->em->createQuery('SELECT m FROM Entities\BrandSportMap m WHERE m.brandId = ' . $brandId . ' AND m.sportId = ' . $sportId);
        $result = $q->getResult();
        foreach ($result as $map) {
            $this->doctrine->em->delete($map);
            $this->doctrine->em->flush();
        }
    }
    
    public function findSponsorsOf(&$sportId) {
        if (!(is_numeric($sportId) && $sportId > 0)) {
            throw new Exception("Invalid ID: " . $sportId, 400);
        }
        
        $result = array();
        $q = $this->doctrine->em->createQuery('SELECT m FROM Entities\BrandSportMap m WHERE m.sportId = ' . $sportId);
        $maps = $q->getResult();
        foreach ($maps as $map) {
            $brand = $this->doctrine->em->getRepository('Entities\Brand')->findOneBy(array('id' => $map->getBrandId()));
            array_push($result, $brand);
        }
        
        return $result;
    }
    
    public function findSponsoredBy(&$brandId) {
        if (!(is_numeric($brandId) && $brandId > 0)) {
            throw new Exception("Invalid ID: " . $brandId, 400);
        }
        
        $result = array();
        $q = $this->doctrine->em->createQuery('SELECT m FROM Entities\BrandSportMap m WHERE m.brandId = ' . $brandId);
        $maps = $q->getResult();
        foreach ($maps as $map) {
            $sport = $this->doctrine->em->getRepository('Entities\Sport')->findOneBy(array('id' => $map->getSportId()));
            array_push($result, $sport);
        }
        
        return $result;
    }

//    /**
//     * Add a new sport.
//     *
//     * @param Entities\Sport $brand
//     */
//    public function add(&$brand) {
//        if (!$this->isInRange($brand->getName(), 5, 32)) {
//            throw new Exception("Invalid name (5 <= name <= 32).", 400);
//        }
//
//        $prev = $this->doctrine->em->getRepository('Entities\Brand')->findOneBy(array('name' => $brand->getName()));
//        if ($prev == null) {
//            $this->doctrine->em->persist($brand);
//            $this->doctrine->em->flush();
//        }
//        $added = $this->doctrine->em->getRepository('Entities\Brand')->findOneBy(array('name' => $brand->getName()));
//        return $added->getId();
//    }
//
//    /**
//     * Delete the given sport.
//     *
//     * @param integer $id
//     */
//    public function remove(&$id) {
//        $brand = $this->doctrine->em->getRepository('Entities\Brand')->findOneBy(array('id' => $id));
//        if ($brand != null) {
//            $this->doctrine->em->remove($brand);
//            $this->doctrine->em->flush();
//        }
//    }
//
//    public function findSponsoredBy(&$brandId) {
//        if (!(is_numeric($brandId) && $brandId > 0)) {
//            throw new Exception("Invalid ID: " . $brandId, 400);
//        }
//
//        $q = $this->doctrine->em->createQuery('SELECT s FROM Entities\Sport s WHERE s.sportId != 0 ORDER BY s.weight DESC');
//
//        $brand = $this->doctrine->em->getRepository('Entities\Brand')->findOneBy(array('id' => $id));
//        if ($brand == null) {
//            throw new Exception("Not Found.", 404);
//        }
//        return $brand;
//    }
//
//    public function getAll() {
//        $q = $this->doctrine->em->createQuery('SELECT s FROM Entities\Brand s WHERE s.id != 0 ORDER BY s.weight DESC');
//        return $q->getResult();
//    }
//
//    /**
//     * Check if the length of string is in the given range (inclusive).
//     *
//     * @return boolean
//     */
//    private function isInRange(&$str, $min, $max) {
//        $length = strlen($str);
//        return $length == 0 || (3 <= $min && $length <= $max);
//    }
//
}

?>
