<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->isLoggedIn();
    }

    /**
     * This function used to check the user is logged in or not
     */
    function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');

        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $this->load->view('login');
        }
        else
        {
            redirect('/tournaments');
        }
    }


    /**
     * This function used to logged in user
     */
    public function loginMe()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'username', 'required|max_length[20]|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');

        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            $username = $this->security->xss_clean($this->input->post('username'));
            $password = $this->input->post('password');

            $user = [
                'username' => $username,
                'password' => $password,
            ];
            $json_user = json_encode($user);
            // Login
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://gameon-api.herokuapp.com/api/v1/login");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$json_user);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            ));

            $result = json_decode(curl_exec($ch));

            curl_close($ch);

            if(!empty($result))
            {

                $sessionArray = array('sessionId'=>$result->session->sessionId,
                                      'token' => $result->token,
                                      'role'=>'Player',
                                      'roleText'=>'Player',
                                      'name'=> $username,
                                      'isLoggedIn' => TRUE
                                );

                $this->session->set_userdata($sessionArray);

                redirect('/tournaments');
            }
            else
            {
                $this->session->set_flashdata('error', 'Email or password mismatch');

                redirect('/login');
            }
        }
    }

}

?>
