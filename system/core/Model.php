<?php defined('DIRECT') OR exit('No direct script access allowed');

class Model {

    public  $db;
    private $config;
    private $titan;

    function __construct()
    {
        $this->titan = Loader::getInstance();

        if(ENVIRONMENT != 'production')
            $this->config = $this->titan->config('db','dev');
        else
            $this->config = $this->titan->config('db');

        require_once SYSTEM_DIR . 'plugins/Database.php';

        $this->db = Database::init($this->config);
    }
    
}