<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    function is_active_page($page,$class)
    {
        $_this =& get_instance();
        if($page == $_this->uri->segment(2)) return $class;
    }

    function is_active_menu($page,$class)
    {
        $_this =& get_instance();
        if ($_this->uri->segment(1) == $page) {
            return $class;
        }
    }
?>