<?php

class BrandDao extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');
    }

    /**
     * Add a new sport.
     *
     * @param Entities\Sport $brand
     */
    public function add(&$brand) {
        if (!$this->isInRange($brand->getName(), 5, 32)) {
            throw new Exception("Invalid name (5 <= name <= 32).", 400);
        }

        $prev = $this->doctrine->em->getRepository('Entities\Brand')->findOneBy(array('name' => $brand->getName()));
        if ($prev == null) {
            $this->doctrine->em->persist($brand);
            $this->doctrine->em->flush();
        }
        $added = $this->doctrine->em->getRepository('Entities\Brand')->findOneBy(array('name' => $brand->getName()));
        return $added->getId();
    }

    /**
     * Delete the given sport.
     *
     * @param integer $id
     */
    public function remove(&$id) {
        $brand = $this->doctrine->em->getRepository('Entities\Brand')->findOneBy(array('id' => $id));
        if ($brand != null) {
            $this->doctrine->em->remove($brand);
            $this->doctrine->em->flush();
        }
    }

    public function find(&$id) {
        if (!(is_numeric($id) && $id > 0)) {
            throw new Exception("Invalid ID: " . $id, 400);
        }
        $brand = $this->doctrine->em->getRepository('Entities\Brand')->findOneBy(array('id' => $id));
        if ($brand == null) {
            throw new Exception("Not Found.", 404);
        }
        return $brand;
    }

    public function getAll() {
        $q = $this->doctrine->em->createQuery('SELECT b FROM Entities\Brand b WHERE b.id != 0 ORDER BY b.weight DESC');
        return $q->getResult();
    }

    public function findAfter($after) {
        if ($after == null || $after < 0) {
            $after = 0;
        }
        $q = $this->doctrine->em->createQuery('SELECT b FROM Entities\Brand b WHERE b.latestRevision > ' . $after);
        return $q->getResult();
    }

    /**
     * Check if the length of string is in the given range (inclusive).
     *
     * @return boolean
     */
    private function isInRange(&$str, $min, $max) {
        $length = strlen($str);
        return $length == 0 || (3 <= $min && $length <= $max);
    }

}

?>
