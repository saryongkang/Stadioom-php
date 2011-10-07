<?php

require(APPPATH . '/libraries/Stadioom_REST_Controller.php');

class Bra extends Stadioom_REST_Controller {

    private $filterKeys = array('firstRevision', 'latestRevision', 'updateFlag');

    function __construct() {
        parent::__construct();

        $this->load->library('doctrine');
    }

    public function create_get() {
        $bra = new Entities\Bra();
        $bra->setName("SeedShock");
        $this->doctrine->em->persist($bra);
        $bra = new Entities\Bra();
        $bra->setName("Stadioom");
        $this->doctrine->em->persist($bra);
        $this->doctrine->em->flush();
    }

    public function createSpo_get() {
        $spo = new Entities\Spo();
        $spo->setName("BasketBall");
        $this->doctrine->em->persist($spo);
        $spo = new Entities\Spo();
        $spo->setName("Soccer");
        $this->doctrine->em->persist($spo);
        $spo = new Entities\Spo();
        $spo->setName("Tennis");
        $this->doctrine->em->persist($spo);
        $this->doctrine->em->flush();
    }

    public function index_get() {
        try {
            $id = $this->get('id');
            if ($id == null) {
                $q = $this->doctrine->em->createQuery("SELECT b FROM Entities\Bra b");
                $bras = $q->getResult();

                $array = array();
                foreach ($bras as $bra) {
                    array_push($array, $this->filter($bra->toArray(), array('spos')));
                }

                if ($array == null) {
                    $this->responseError(new Exception("Not Found.", 404));
                }

                $data = array("data" => $array);
                $this->responseOk($data);
            } else {
                $bra = $this->doctrine->em->find("Entities\Bra", $id);
                $this->responseOk($this->filter($bra->toArray(), array('spos')));
            }
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }
    
    public function brands_get() {
        try {
            $sportId = $this->get('sportId');
            if ($sportId != null) {
                $q = $this->doctrine->em->createQuery("SELECT b, s FROM Entities\Bra b JOIN b.spos s WHERE s.id = " . $sportId);
                
                $bras = $q->getResult();
                $array = array();
                foreach ($bras as $bra) {
                    array_push($array, $this->filter($bra->toArray(), array('spos')));
                }

                if ($array == null) {
                    $this->responseError(new Exception("Not Found.", 404));
                }

                $data = array("data" => $array);
                $this->responseOk($data);
            }
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }
    
    public function sports_get() {
        try {
            $brandId = $this->get('brandId');
            if ($brandId != null) {
                $bra = $this->doctrine->em->find("Entities\Bra", $brandId);
                
                $spos = $bra->getSpos();
                
                $array = array();
                foreach ($spos as $spo) {
                    array_push($array, $spo->toArray());
                }

                if ($array == null) {
                    $this->responseError(new Exception("Not Found.", 404));
                }

                $data = array("data" => $array);
                $this->responseOk($data);
            }
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

    public function link_get() {
        $bra1 = $this->doctrine->em->find("Entities\Bra", 1);
        $bra2 = $this->doctrine->em->find("Entities\Bra", 2);
        $spo1 = $this->doctrine->em->find("Entities\Spo", 1);
        $spo2 = $this->doctrine->em->find("Entities\Spo", 2);
        $spo3 = $this->doctrine->em->find("Entities\Spo", 3);

        $bra1->addSpos($spo1);
        $bra1->addSpos($spo2);
        $bra1->addSpos($spo3);
        $bra2->addSpos($spo1);
        $bra2->addSpos($spo2);
        $bra2->addSpos($spo3);

        $this->doctrine->em->flush();
    }

    public function spos_get() {
        try {
            $id = $this->get('id');
            if ($id == null) {
                $q = $this->doctrine->em->createQuery("SELECT b FROM Entities\Spo b");
                $bras = $q->getResult();

                $array = array();
                foreach ($bras as $bra) {
                    array_push($array, $this->filter($bra->toArray(), $this->filterKeys));
                }

                if ($array == null) {
                    $this->responseError(new Exception("Not Found.", 404));
                }

                $data = array("data" => $array);
                $this->responseOk($data);
            } else {
                $q = $this->doctrine->em->createQuery("SELECT b FROM Entities\Spo b WHERE b.id = " . $id);
            }
        } catch (Exception $e) {
            $this->responseError($e);
        }
    }

}

?>
