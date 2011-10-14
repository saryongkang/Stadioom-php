<?php
class Main extends FBAuth_Controller {

	function index()
	{
        $data['session'] = $this->session->userdata;
        
        $this->security->csrf_set_cookie();
        
        $this->load->model('dao/UserDao');
        $matchesData = $this->UserDao->getLatestMatches($data['session']['user']['id'], 0, 1);
        //getLatestMatches($userId, $firstOffset, $maxResult)
        
        $this->load->model('dao/SportDao');
        $sports= $this->SportDao->getAll();
        
        $this->load->model('dao/BrandDao');
        $brands = $this->BrandDao->getAll();
        
        $data['matches'] = array();
        
        for($j=0; $j<sizeof($matchesData); $j++){
            $data['matches'][$j] = array();
            $data['matches'][$j]['title'] = $sports[$matchesData[$j]->getSportId()-1]->getName().' Match, sponsored by '.$brands[$matchesData[$j]->getBrandId()-1]->getName();
            
            $currentTeam='';
            $players;
            //$data['matches'][$j]['object'] = $matchesData[$j];
            for($k=1; $k<3; $k++){
                if($k===1){
                    $currentTeam='A';
                    $players = $matchesData[$j]->getMembersA();
                    $score=$matchesData[$j]->getScoreA();
                }else{
                    $currentTeam='B';
                    $players = $matchesData[$j]->getMembersB();
                    $score=$matchesData[$j]->getScoreB();
                }
                // FOR PLAYERS in TEAM A

                $playersDetailDiv ='<div class="players'.$currentTeam.'Detail">';
                $summaryPlayersText= '';
                $firstPlayer= array();
                $isPlayerInTeam= false;
                $numberOfPlayers = sizeof($players);
                for ($i=0; $i<($numberOfPlayers); $i++){
                    $player =$players[$i];
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
                        $isPlayerInTeam=true;
                    }
                    $playersDetailDiv .='<div class="playerInfo"><img src="https://graph.facebook.com/'.$player->getFbId().'/picture" /> '.$player->getName().'</div>';
                }

                $otherPlayers = ($numberOfPlayers-1);
                $othersText='';
                if($otherPlayers>0){
                    $othersText = ' &amp; '.$otherPlayers. '+';
                }
                if($isPlayerInTeam===true){
                    $summaryPlayersText = '<strong>You</strong>'. $othersText;
                    $summaryPlayersPic ='https://graph.facebook.com/'.$data['session']['fbUser']['id'].'/picture';
                }else{
                    $summaryPlayersText = $firstPlayer['name'].$othersText;
                    $summaryPlayersPic ='https://graph.facebook.com/'.$firstPlayer['fbId'].'/picture';
                }

                $playersDetailDiv .='</div>';

                $data['matches'][$j]['summaryPlayers'.$currentTeam.'Text'] = $summaryPlayersText;
                $data['matches'][$j]['summaryPlayers'.$currentTeam.'Pic'] = $summaryPlayersPic;
                $data['matches'][$j]['players'.$currentTeam.'DetailDiv'] = $playersDetailDiv;
                $data['matches'][$j]['score'.$currentTeam] = $score;
            }
        }
        
        
        //BUILDING THE TEMPLATE
        $this->template->add_css('main');
        
        
        $this->template->set_content('mainView', $data);
        $this->template->build('main');
    }
}
?>
