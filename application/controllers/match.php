<?php

class Match extends FBAuth_Controller {
    
    function __construct() {
        parent::__construct();
    }

	function index()
	{
        echo "Unaccessible";
    }
    
    function create(){
        $data['session'] = $this->session->userdata;
        
        $this->load->model('dao/SportDao');
        $data['sports'] = $this->SportDao->getAll();
        
        $this->security->csrf_verify();
        
        
        $this->template->add_js('tdfriendselector');
        $this->template->add_js('jquery.iphone-switch');
        $this->template->add_js('jquery.cookie');
        $this->template->add_js('bootstrap/bootstrap-modal');
        $this->template->add_js('ui/jquery.ui.core.min');
        $this->template->add_js('ui/jquery.ui.datepicker.min');
//        $this->template->add_js('jquery-ui-1.7.1.custom.min');
//        $this->template->add_js('daterangepicker.jQuery');
        
        $this->template->add_css('ui/themes/base/jquery-ui.min');
        $this->template->add_css('ui/themes/base/jquery.ui.core.min');
        $this->template->add_css('ui/themes/base/jquery.ui.datepicker.min');
        $this->template->add_css('tdfriendselector');
        $this->template->add_css('match');
//        $this->template->add_css('ui.daterangepicker');
//        $this->template->add_css('redmond/jquery-ui-1.7.1.custom');
        $this->template->add_css('main');
        
        $this->template->set_content('createMatchView', $data);
        $this->template->build('main');
    }
    
    function viewMine(){
        $data['session'] = $this->session->userdata;    
        $this->load->model('dao/UserDao');
        $matchesData = $this->UserDao->getLatestMatches($data['session']['user']['id'], 10);
        $this->load->model('dao/SportDao');
        $sports = $this->SportDao->getAll();
        $this->load->model('dao/BrandDao');
        $brands = $this->BrandDao->getAll();
        $data['matches'] = array();
        for($j=0; $j<sizeof($matchesData); $j++){
            //$data['matches'][$j]['object'] = $matchesData[$j];
            $playersA = $matchesData[$j]->getMemberIdsA();
            $playersDetailDiv ='<div class="playersDetail">';
            $summaryPlayersText= '';
            $firstPlayer= array();
            $isPlayerInTeamA= false;
            $numberOfPlayersA = sizeof($playersA)+1;
            for ($i=0; $i<($numberOfPlayersA-1); $i++){
                $player =$playersA[$i];
                //$player = $this->UserDao->find($player->getId());
                if($i==0);
                {
                    //$firstPlayer['id']=$player->getId();
                    $firstPlayer['id']=$player->getId();
                    $firstPlayer['fbId']=$player->getFbId();
                    //$firstPlayer['fbId']=$this->UserDao->find($player->getId())->getFbId();
                    $firstPlayer['name']=$player->getName();
                }

                if ($player->getId()==$data['session']['user']['id']){
                    $isPlayerInTeamA=true;
                }
                $playersDetailDiv .='<div class="playerInfo"><img src="https://graph.facebook.com/'.$player->getFbId().'/picture" /> '.$player->getName().'</div>';
            }

            if($isPlayerInTeamA===true){
                $summaryPlayersAText = 'You + '.sizeof($playersA). 'more';
                $summaryPlayersAPic ='https://graph.facebook.com/'.$data['session']['fbUser']['id'].'/picture';
            }else{
                $summaryPlayersAText = $firstPlayer['name'].' + '.($numberOfPlayersA-1). 'more';
                $summaryPlayersAPic ='https://graph.facebook.com/'.$firstPlayer['fbId'].'/picture';
            }

            $playersDetailDiv .='</div>';
            
            $data['matches'][$j]['summaryPlayersAText']->$summaryPlayersAText;
            $data['matches'][$j]['summaryPlayersAPic']->$summaryPlayersAPic;
            $data['matches'][$j]['playersADetailDiv']->$playersDetailDiv;
            $data['matches'][$j]['scoreA']->$matchesData[$j]->getScoreA();
            
            $data['matches'][$j]['title']->$sports[$matchesData[$j]->getSportId()-1]->getName(); ?> Match, sponsored by <?php echo $brands[$matchesData[$j]->getBrandId()-1]->getName();
        }
                
        $this->template->add_css('matchesview');
        $this->template->add_css('main');
        
        $this->template->add_js('jquery.timeago');
        
        $this->template->set_content('myMatchesView', $data);
        $this->template->build('main');
    }
}
?>
