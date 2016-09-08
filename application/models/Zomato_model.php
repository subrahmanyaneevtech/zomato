<?php

/**
 * Created by PhpStorm.
 * User: subrahmanya
 * Date: 6/9/16
 * Time: 6:02 PM
 */
class Zomato_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function save_categories($data){
        try{
            $this->db->insert('categories',$data);
            $error = $this->db->error();
            //echo $error['code'];exit;
            if($error['code'] !=0 ){
                switch ($error['code']){
                    case "1062":
                         throw new Exception("Data already exists.");
                        break;
                    default :
                        throw new Exception("Error while saving category data.");
                        break;
                }
                return false;
            }
            return true;
        } catch (Exception $e){
            log_message("error", $e->getMessage());
            $this->session->set_flashdata('msg', array('message' => $e->getMessage(),'class' => 'error'));
            return false;
        }
    }

    public function save_locations($data){
        try{
            $this->db->insert('locations',$data);
            $error = $this->db->error();
            if($error['code'] !=0 ){
                switch ($error['code']){
                    case "1062":
                        throw new Exception("Data already exists.");
                        break;
                    default :
                        throw new Exception("Error while saving city.");
                        break;
                }
                return false;
            }
            return true;
        } catch (Exception $e){
            log_message("error", $e->getMessage());
            $this->session->set_flashdata('msg', array('message' => $e->getMessage(),'class' => 'error'));
            return false;
        }
    }

    public function save_collections($data){
        try{
            $this->db->insert('collections',$data);
            $error = $this->db->error();
            if($error['code'] !=0 ){
                switch ($error['code']){
                    case "1062":
                        //throw new Exception("Data already exists.");
                        break;
                    default :
                        throw new Exception("Error while saving city.");
                        break;
                }
                return false;
            }
            return true;
        } catch (Exception $e){
            log_message("error", $e->getMessage());
            $this->session->set_flashdata('msg', array('message' => $e->getMessage(),'class' => 'error'));
            return false;
        }
    }

    public function get_cities(){
        $this->db->select('id,name');
        $this->db->order_by('name');
        $locations = $this->db->get('locations');

        if($locations->num_rows()>0){
            return $locations->result_array();
        } else {
            return null;
        }
    }

    public function get_cuisines(){
        $this->db->select('cuisine_id,cuisine_name');
        $this->db->order_by('cuisine_name');
        $locations = $this->db->get('cuisines');

        if($locations->num_rows()>0){
            return $locations->result_array();
        } else {
            return null;
        }
    }
    public function get_categories(){
        $this->db->select('id,name');
        $this->db->order_by('name');
        $locations = $this->db->get('categories');

        if($locations->num_rows()>0){
            return $locations->result_array();
        } else {
            return null;
        }
    }

    public function get_restaurants(){
        $this->db->select('id,name,locality,city');
        $this->db->order_by('name');
        $locations = $this->db->get('restaurant');

        if($locations->num_rows()>0){
            return $locations->result_array();
        } else {
            return null;
        }
    }

    public function save_cuisines($data){
        try{
            $this->db->insert('cuisines',$data);
            $error = $this->db->error();
            if($error['code'] !=0 ){
                switch ($error['code']){
                    case "1062":
                        //throw new Exception("Data already exists.");
                        break;
                    default :
                        throw new Exception("Error while saving cuisines.");
                        break;
                }
                return false;
            }
            return true;
        } catch (Exception $e){
            log_message("error", $e->getMessage());
            $this->session->set_flashdata('msg', array('message' => $e->getMessage(),'class' => 'error'));
            return false;
        }
    }

    public function save_establishments($data){
        try{
            $this->db->insert('establishments',$data);
            $error = $this->db->error();
            if($error['code'] !=0 ){
                switch ($error['code']){
                    case "1062":
                        //throw new Exception("Data already exists.");
                        break;
                    default :
                        throw new Exception("Error while saving establishments.");
                        break;
                }
                return false;
            }
            return true;
        } catch (Exception $e){
            log_message("error", $e->getMessage());
            $this->session->set_flashdata('msg', array('message' => $e->getMessage(),'class' => 'error'));
            return false;
        }
    }

    public function save_restaurant($data){
        try{
            $this->db->insert('restaurant',$data);
            $error = $this->db->error();
            if($error['code'] !=0 ){
                switch ($error['code']){
                    case "1062":
                        //throw new Exception("Data already exists.");
                        break;
                    default :
                        throw new Exception("Error while saving Restaurant.");
                        break;
                }
                return false;
            }
            return true;
        } catch (Exception $e){
            log_message("error", $e->getMessage());
            $this->session->set_flashdata('msg', array('message' => $e->getMessage(),'class' => 'error'));
            return false;
        }
    }

    public function save_geo_locations($data){
        try{
            $this->db->insert('geo_locations',$data);
            $error = $this->db->error();
            if($error['code'] !=0 ){
                switch ($error['code']){
                    case "1062":
                        //throw new Exception("Data already exists.");
                        break;
                    default :
                        throw new Exception("Error while saving geo locations.");
                        break;
                }
                return false;
            }
            return true;
        } catch (Exception $e){
            log_message("error", $e->getMessage());
            $this->session->set_flashdata('msg', array('message' => $e->getMessage(),'class' => 'error'));
            return false;
        }
    }

    public function save_reviews($data){
        try{
            $this->db->insert('user_reviews',$data);
            $error = $this->db->error();
            if($error['code'] !=0 ){
                switch ($error['code']){
                    case "1062":
                        //throw new Exception("Data already exists.");
                        break;
                    default :
                        throw new Exception("Error while saving user reviews.");
                        break;
                }
                return false;
            }
            return true;
        } catch (Exception $e){
            log_message("error", $e->getMessage());
            $this->session->set_flashdata('msg', array('message' => $e->getMessage(),'class' => 'error'));
            return false;
        }
    }
}