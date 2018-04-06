<?php
    namespace mbrandt13\WordPressNonce;
    class Nonce
    {
        private $action ;
        private $name ;
        

        public function constructor($action = -1, $name = "_wpnonce")
        {
            $this->action = $action;
            $this->name = $name;
        }


        public function get_value()
        {
            return create_nonce($this->action);
        }


        public function get_url($actionurl)
        {
            return nonce_url($actionurl, $this->action, $this->name);
        }


        public function get_field($echo = true)
        {
            return get_field($echo);
        }


        public  function verify_ajax($die = true)
        {
            return verify_ajax($this->action, $this->name);
        }

        public function verify_admin()
        {
            return check_admin($this->action, $this->name);
        }

        public function verify($nonce)
        {
            return verify($nonce,$this->action);
        }



        public  function ays()
        {
            wp_nonce_ays($this->action);
        }
    }


