<?php

class BrandSportMapDao extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');
    }

    public function link(&$brandId, &$sportId) {
        $q = $this->doctrine->em->createQuery('SELECT m FROM Entities\BrandSportMap m WHERE m.brandId = ' . $brandId . ' AND m.sportId = ' . $sportId);
        $result = $q->getResult();
        if (count($result) == 0) {
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
        
        $count = count($maps);
        if ($count != 0) {
            $dql = "SELECT b FROM Entities\Brand b WHERE b.id IN (";
            for ($i = 1; $i <= $count; $i++) {
                $dql = $dql . $i;
                if ($i < $count) {
                    $dql = $dql . ", ";
                }
            }
            $dql = $dql . ")";
            $q = $this->doctrine->em->createQuery($dql);
            $result = $q->getResult();
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
        
        $count = count($maps);
        if ($count != 0) {
            $dql = "SELECT s FROM Entities\Sport s WHERE s.id IN (";
            for ($i = 1; $i <= $count; $i++) {
                $dql = $dql . $i;
                if ($i < $count) {
                    $dql = $dql . ", ";
                }
            }
            $dql = $dql . ")";
            $q = $this->doctrine->em->createQuery($dql);
            $result = $q->getResult();
        }
        
        return $result;
    }

}

?>
