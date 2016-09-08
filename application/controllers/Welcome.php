<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	var $api_key = "e040f073d3aae2cdccb1c9da0d6a2bca";
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('zomato_model');
	}

	public function index()
	{
		$data['cities'] = $this->zomato_model->get_cities();
		$data['cuisines'] = $this->zomato_model->get_cuisines();
		$data['categories'] = $this->zomato_model->get_categories();
		$data['restaurants'] = $this->zomato_model->get_restaurants();
		$this->load->view('welcome_message',$data);
	}
	public function get_data($uri,$header){
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => 'https://developers.zomato.com/api/v2.1/'.$uri,
			CURLOPT_HTTPHEADER => $header
		));
		$response = curl_exec($ch);
		return($response);
	}

	public function save_categories(){
		try{
			$header = array('user-key:'.$this->api_key,"Accept: application/json");
			$api_data = json_decode($this->get_data('categories',$header),true);

			$total_records = count($api_data['categories']);
			$success_cnt = 0;
			if($api_data['categories']){
				$category_data = array();
				foreach ($api_data['categories'] as $value){
					if($value['categories']['id']){
						if($this->zomato_model->save_categories($value['categories'])){
							$success_cnt++;
						}
					}
				}
				if($total_records == $success_cnt)
				$this->session->set_flashdata('msg', array('message' => "Categories saved successfully.",'class' => 'success'));
			} else{
				throw new Exception($api_data['message']);
			}
		} catch (Exception $e){
			log_message("error", $e->getMessage());
			$this->session->set_flashdata('msg', array('message' => $e->getMessage(),'class' => 'error'));
		}
		redirect(BASE_URL());
	}

	public function get_locations(){
		try{
			$city_name = $this->input->get('city_name');
			if($this->input->get('city_name') ==""){
				throw new Exception("Please enter city name.");
			}
			$header = array('user-key:'.$this->api_key,"Accept: application/json");
			$api_data = json_decode($this->get_data('cities?q='.$city_name,$header),true);
			//echo "<pre>";print_r($api_data);exit;
			$total_records = count($api_data['location_suggestions']);
			$success_cnt = 0;
			if($total_records == 0){
				throw new Exception("No records found");
			}
			if(!empty($api_data['location_suggestions'])){
				$category_data = array();
				foreach ($api_data['location_suggestions'] as $value){
					if(is_array($value)){
						if($this->zomato_model->save_locations($value)){
							$success_cnt++;
						}
					}
				}
				if($total_records == $success_cnt)
					$this->session->set_flashdata('msg', array('message' => "City details saved successfully.",'class' => 'success'));

			} else{
				throw new Exception($api_data['message']);
			}
		} catch (Exception $e){
			log_message("error", $e->getMessage());
			$this->session->set_flashdata('msg', array('message' => $e->getMessage(),'class' => 'error'));
		}
		redirect(BASE_URL());
	}

	public function get_collections(){
		try{
			$param = $this->input->get('param');
			if($param ==""){
				throw new Exception("Please enter city id.");
			}
			$header = array('user-key:'.$this->api_key,"Accept: application/json");
			$api_data = json_decode($this->get_data('collections?city_id='.$param,$header),true);
			//echo "<pre>";print_r($api_data);exit;
			$total_records = count($api_data['collections']);
			$success_cnt = 0;
			if($total_records == 0){
				throw new Exception("No records found");
			}
			if(!empty($api_data['collections'])){
				$category_data = array();
				foreach ($api_data['collections'] as $value){
					if(is_array($value)){
						if($this->zomato_model->save_collections($value['collection'])){
							$success_cnt++;
						}
					}

				}
				if($success_cnt == 0){
					$this->session->set_flashdata('msg', array('message' => "Collection details already exists",'class' => 'success'));
				} else {
					$this->session->set_flashdata('msg', array('message' => "Collection details saved successfully.",'class' => 'success'));
				}


			} else{
				throw new Exception($api_data['message']);
			}
		} catch (Exception $e){
			log_message("error", $e->getMessage());
			$this->session->set_flashdata('msg', array('message' => $e->getMessage(),'class' => 'error'));
		}
		redirect(BASE_URL());
	}

	public function get_cuisines(){
		try{
			$param = $this->input->get('param');
			if($param ==""){
				throw new Exception("Please select city id.");
			}
			$header = array('user-key:'.$this->api_key,"Accept: application/json");
			$api_data = json_decode($this->get_data('cuisines?city_id='.$param,$header),true);
			//echo "<pre>";print_r($api_data);exit;
			$total_records = count($api_data['cuisines']);
			$success_cnt = 0;
			if($total_records == 0){
				throw new Exception("No records found");
			}
			if(!empty($api_data['cuisines'])){
				$category_data = array();
				foreach ($api_data['cuisines'] as $value){
					if(is_array($value['cuisine'])){
						if($this->zomato_model->save_cuisines($value['cuisine'])){
							$success_cnt++;
						}
					}

				}
				if($success_cnt == 0){
					$this->session->set_flashdata('msg', array('message' => "Cuisines details already exists",'class' => 'success'));
				} else {
					$this->session->set_flashdata('msg', array('message' => "Cuisines details saved successfully.",'class' => 'success'));
				}


			} else{
				throw new Exception($api_data['message']);
			}
		} catch (Exception $e){
			log_message("error", $e->getMessage());
			$this->session->set_flashdata('msg', array('message' => $e->getMessage(),'class' => 'error'));
		}
		redirect(BASE_URL());
	}

	public function get_establishments(){
		try{
			$param = $this->input->get('param');
			if($param ==""){
				throw new Exception("Please select city id.");
			}
			$header = array('user-key:'.$this->api_key,"Accept: application/json");
			$api_data = json_decode($this->get_data('establishments?city_id='.$param,$header),true);
			//echo "<pre>";print_r($api_data);exit;
			$total_records = count($api_data['establishments']);
			$success_cnt = 0;
			if($total_records == 0){
				throw new Exception("No records found");
			}
			if(!empty($api_data['establishments'])){
				$category_data = array();
				foreach ($api_data['establishments'] as $value){
					if(is_array($value['establishment'])){
						if($this->zomato_model->save_establishments($value['establishment'])){
							$success_cnt++;
						}
					}

				}
				if($success_cnt == 0){
					$this->session->set_flashdata('msg', array('message' => "Establishments details already exists",'class' => 'success'));
				} else {
					$this->session->set_flashdata('msg', array('message' => "Establishments details saved successfully.",'class' => 'success'));
				}


			} else{
				throw new Exception($api_data['message']);
			}
		} catch (Exception $e){
			log_message("error", $e->getMessage());
			$this->session->set_flashdata('msg', array('message' => $e->getMessage(),'class' => 'error'));
		}
		redirect(BASE_URL());
	}

	public function get_geo_locations(){
		try{
			$param1 = $this->input->get('param1');
			$param2 = $this->input->get('param2');
			if($param1 =="" || $param2 ==""){
				throw new Exception("Please Enter latitude and longitude.");
			}
			$header = array('user-key:'.$this->api_key,"Accept: application/json");
			$api_data = json_decode($this->get_data('/geocode?lat='.$param1.'&lon='.$param2,$header),true);
			//echo "<pre>";print_r($api_data);exit;
			$total_records = count($api_data['location']);
			$success_cnt = 0;
			if($total_records == 0){
				throw new Exception("No records found");
			}
			//echo "<pre>";
			if(!empty($api_data['location'])){
				$data = array();
				$restaurant_data = array();
				foreach ($api_data as $key => $value){

					if($key == "location" || $key == "popularity"){
						foreach ($value as $key1 => $val){

							if($key1 == "top_cuisines" || $key1 == "nearby_res"){
								$data[$key1] = implode("||",$val);
								continue;
							}
							$data[$key1] = $val;
						}
					} else if($key=="nearby_restaurants"){
						foreach ($value as $restaurant){
							$restaurant_data = $this->flatten($restaurant);
							//echo "<pre>";print_r($restaurant_data);exit;
							if(is_array($restaurant_data)){
								$this->zomato_model->save_restaurant($restaurant_data);
							}
						}

					}else{
						$data[$key] = $value;
					}
				}
				$this->zomato_model->save_geo_locations($data);
				$this->session->set_flashdata('msg', array('message' => "GEO location details saved.",'class' => 'success'));


			} else{
				throw new Exception($api_data['message']);
			}
		} catch (Exception $e){
			log_message("error", $e->getMessage());
			$this->session->set_flashdata('msg', array('message' => $e->getMessage(),'class' => 'error'));
		}
		redirect(BASE_URL());
	}

	public function search(){
		try{
			$param = $this->input->post('param');
			$param1 = $this->input->post('param1');
			$param2 = $this->input->post('param2');
			$param3 = implode(',',$this->input->post('param3'));
			$param4 = implode(',',$this->input->post('param4'));
			//print_r();exit;
			if($param =="" && $param1 == "" && $param2 =="" && $param3 =="" && $param4 == ""){
				throw new Exception("Please input any of the details.");
			}
			$url_str = "";
			if($param !=""){
				$url_str = 'q='.$param;
			}
			if($param1 !=""){
				if($url_str !="")
					$url_str = '&lat='.$param1;
				else
					$url_str = 'lat='.$param1;
			}
			if($param2 !=""){
				if($url_str !="")
					$url_str = '&lon='.$param2;
				else
					$url_str = 'lon='.$param2;
			}
			if($param3 !=""){
				if($url_str !="")
					$url_str = '&cuisines='.$param3;
				else
					$url_str = 'cuisines='.$param3;
			}

			if($param4 !=""){
				if($url_str !="")
					$url_str = '&category='.$param4;
				else
					$url_str = 'category='.$param4;
			}
			$header = array('user-key:'.$this->api_key,"Accept: application/json");
			$api_data = json_decode($this->get_data('search?'.$url_str,$header),true);
			//echo "<pre>";print_r($api_data);exit;
			$total_records = count($api_data['restaurants']);
			$success_cnt = 0;
			if($total_records == 0){
				throw new Exception("No records found");
			}
			if(!empty($api_data['restaurants'])){
				foreach ($api_data['restaurants'] as $restaurant){
					$restaurant_data = $this->flatten($restaurant);
					//echo "<pre>";print_r($restaurant_data);exit;
					if(is_array($restaurant_data)){
						if($this->zomato_model->save_restaurant($restaurant_data)){
							$success_cnt++;
						}
					}
				}
				if($total_records == $success_cnt){
					$this->session->set_flashdata('msg', array('message' => "Restaurant details saved successfully.",'class' => 'success'));
				} else if($success_cnt == 0) {
					$this->session->set_flashdata('msg', array('message' => "Restaurant details already exists.",'class' => 'success'));
				}else
				{
					$this->session->set_flashdata('msg', array('message' => $success_cnt." New Restaurant details saved successfully.",'class' => 'success'));
				}

			} else{
				throw new Exception($api_data['message']);
			}
		} catch (Exception $e){
			log_message("error", $e->getMessage());
			$this->session->set_flashdata('msg', array('message' => $e->getMessage(),'class' => 'error'));
		}
		redirect(BASE_URL());
	}

	public function get_reviews(){
		try{
			$param = $this->input->get('param4');
			if($param ==""){
				throw new Exception("Please select Restaurant.");
			}
			$header = array('user-key:'.$this->api_key,"Accept: application/json");
			$api_data = json_decode($this->get_data('reviews?res_id='.$param.'count=100',$header),true);
			//echo "<pre>";print_r($api_data);exit;
			$total_records = count($api_data['user_reviews']);
			$success_cnt = 0;
			if($total_records == 0){
				throw new Exception("No records found");
			}
			if(!empty($api_data['user_reviews'])){
				$category_data = array();
				foreach ($api_data['user_reviews'] as $value){
					if(is_array($value['review'])){

						$reviews= $value['review'];
						$reviews = $reviews + $this->flatten_pre($value['review']['user'],'user_');
						$reviews['rest_id'] = $param;
						unset($reviews['user']);
						//echo "<pre>";print_r($reviews);exit;
						if($this->zomato_model->save_reviews($reviews)){
							$success_cnt++;
						}
					}

				}
				if($success_cnt == 0){
					$this->session->set_flashdata('msg', array('message' => "Revives and rating details already exists",'class' => 'success'));
				} else {
					$this->session->set_flashdata('msg', array('message' => "Revives and rating details saved successfully.",'class' => 'success'));
				}


			} else{
				throw new Exception($api_data['message']);
			}
		} catch (Exception $e){
			log_message("error", $e->getMessage());
			$this->session->set_flashdata('msg', array('message' => $e->getMessage(),'class' => 'error'));
		}
		redirect(BASE_URL());
	}

	public function flatten($array, $prefix = "") {
		$result = array();
		foreach($array as $key=>$value) {
			if(is_array($value)) {
				$result = $result + $this->flatten($value, $key);
			}
			else {
				$result[$key] = $value;
			}
		}
		return $result;
	}

	public function flatten_pre($array, $prefix = null) {
		$result = array();
		foreach($array as $key=>$value) {
			if(is_array($value)) {
				$result = $result + $this->flatten_pre($value, $prefix.$key);
			}
			else {
				$result[$prefix.$key] = $value;
			}
		}
		return $result;
	}
}
