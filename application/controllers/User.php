<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class User extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Topcoder SRM: Dashboard';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://gameonapi.herokuapp.com/api/v1/player/tournaments");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer '. $this->token,
        'Session-Id: '. $this->sessionId
        ));

        $result = json_decode(curl_exec($ch));
        
        curl_close($ch);

        $this->session->set_userdata(array( 'TOURNAMENT_ID' => $result->tournaments[0]->tournamentId));

        $data['tournaments'] = $result->tournaments;

        // If the user already entered the tournament, he can only enter Match not the tournament

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://gameonapi.herokuapp.com/api/v1/player/matches/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer '. $this->token,
        'Session-Id: '. $this->sessionId
        ));

        $result = json_decode(curl_exec($ch));
        
        curl_close($ch);

        if ($result !== "") {
            $this->session->set_userdata(array('MATCH_ID' => $result->matches[0]->matchId));
        }
        
        $this->loadViews("live-tournaments", $this->global, $data, NULL);
    }

    /**
     * This function used to load the first screen of the user
     */
    public function participate()
    {
        $this->global['pageTitle'] = 'Topcoder SRM: Dashboard';

        $ch = curl_init();
        if ($this->session->userdata('MATCH_ID')) {
            curl_setopt($ch, CURLOPT_URL, "https://gameonapi.herokuapp.com/api/v1/player/matches/". $this->session->userdata('MATCH_ID') ."/enter");
        } else {
            curl_setopt($ch, CURLOPT_URL, "https://gameonapi.herokuapp.com/api/v1/player/tournaments/". $this->session->userdata('TOURNAMENT_ID') ."/enter");
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer '. $this->token,
        'Session-Id: '. $this->sessionId
        ));

        $result = json_decode(curl_exec($ch));
        
        curl_close($ch);

        $this->session->set_userdata(array('MATCH_ID' => $result->matchId));
        
        $this->loadViews("competition-form", $this->global, NULL);
    }

    /* Function to submit the User attempt to the Amazon
     * Score generation is random at backend
     */

    public function submit() {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://gameonapi.herokuapp.com/api/v1/player/matches/" . $this->session->userdata('MATCH_ID') ."/score");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer '. $this->token,
        'Session-Id: '. $this->sessionId
        ));

        $result = json_decode(curl_exec($ch));
        
        curl_close($ch);

        redirect('/leaderboard');
    }

    /**
     * This function used to load the first screen of the user
     */
    public function leaderboard()
    {
        $this->global['pageTitle'] = 'Topcoder SRM: Dashboard';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://gameonapi.herokuapp.com/api/v1/player/matches/" . $this->session->userdata('MATCH_ID') ."/leaderboard");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer '. $this->token,
        'Session-Id: '. $this->sessionId
        ));

        $result = json_decode(curl_exec($ch));
        
        curl_close($ch);

        $data['leaderboard'] = $result->leaderboard;
        
        $this->loadViews("leaderboard", $this->global, $data, NULL);
    }

    /**
     * This function used to load the upcoming matches from Amazon Gameon
     */
    public function upcoming()
    {
        $this->global['pageTitle'] = 'Topcoder SRM: Dashboard';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://gameonapi.herokuapp.com/api/v1/player/upcoming-tournaments");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer '. $this->token,
        'Session-Id: '. $this->sessionId
        ));

        $result = json_decode(curl_exec($ch));
        
        curl_close($ch);

        $data['tournaments'] = $result->tournaments;
        
        $this->loadViews("table", $this->global, $data, NULL);
    }

    /**
     * Page not found : error 404
     */
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Topcoder : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }

    
}

?>