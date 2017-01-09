<?php
class Blog extends CI_Controller {

   	public function __construct()
	{
		parent::__construct();
        $this -> load -> library('session');
    }
    public function index()
    {
       
        $this->session->set_userdata('user',"1");
        echo 'hello!';
    }

    public function comments()
    {
        echo 'Look at this!';
    }
}