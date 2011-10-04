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

}

?>
