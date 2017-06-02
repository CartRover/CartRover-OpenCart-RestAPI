<?php

class ControllerFeedGssApi extends Controller {

	//API call endpoint: feed/gss_api/orders
	public function orders() {
		$check = $this->checkPlugin();
		if(!empty($check['error'])){
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($check));
		} else {
			$json = array(); 
			//if (!isset($this->session->data['api_id'])) {
			//	$json['error'] = $this->language->get('error_permission');
			//} else {
				/* check offset parameter */
				if (isset($this->request->get['offset']) && $this->request->get['offset'] != "" && ctype_digit($this->request->get['offset'])) {
					$offset = intval($this->request->get['offset']);
				} else {
					$offset = 0;
				}

				/* check limit parameter */
				if (isset($this->request->get['limit']) && $this->request->get['limit'] != "" && ctype_digit($this->request->get['limit'])) {
					$limit = intval($this->request->get['limit']);
				} else {
					$limit = 500;
				}

				if (isset($this->request->get['status']) && $this->request->get['status'] != "" && ctype_digit($this->request->get['status'])) {
					$status = intval($this->request->get['status']);
				} else {
					$status = 2;
				}

				if (isset($this->request->get['date_from']) && $this->request->get['date_from'] != "") {
					$date_from = date('Y-m-d H:i:s', strtotime($this->request->get['date_from']));
				} else {
					$date_from = "";
				}

				if (isset($this->request->get['date_to']) && $this->request->get['date_to'] != "") {
					$date_to = date('Y-m-d H:i:s', strtotime($this->request->get['date_to']));
				} else {
					$date_to = "";
				}

				$this->load->model('account/gss_order');

				$json = $this->model_account_gss_order->getOrderByStatusId($status, $offset, $limit, $date_from, $date_to);
			//}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}

	//API call to obtain a list of order status with corresponding status_id: feed/gss_api/order_status
	public function order_status() {
		$check = $this->checkPlugin();
		if(!empty($check['error'])){
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($check));
		} else {
			$json = array(); 
			//if (!isset($this->session->data['api_id'])) {
			//	$json['error'] = $this->language->get('error_permission');
			//} else {
				if (isset($this->request->get['status']) && $this->request->get['status'] != "") {
					$status_name = $this->request->get['status'];

					$this->load->model('account/gss_order');
					$json = $this->model_account_gss_order->getOrderStatusId($status_name);
				} else {
					$json['error'] = "Please pass in status name"; 
				}
			//}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}	
	}
	
	//API call to obtain a list of inventory levels: feed/gss_api/obtain_inv_levels
	public function obtain_inv_levels(){
		$check = $this->checkPlugin(); 
		if(!empty($check['error'])){
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($check));
		} else {
			$json = array(); 
			//if (!isset($this->session->data['api_id'])) {
			//	$json['error'] = $this->language->get('error_permission');
			//} else {
				/* check offset parameter */
				if (isset($this->request->get['offset']) && $this->request->get['offset'] != "" && ctype_digit($this->request->get['offset'])) {
					$offset = intval($this->request->get['offset']);
				} else {
					$offset = 0;
				}

				/* check limit parameter */
				if (isset($this->request->get['limit']) && $this->request->get['limit'] != "" && ctype_digit($this->request->get['limit'])) {
					$limit = intval($this->request->get['limit']);
				} else {
					$limit = 200;
				}
				
				/* show enabled/disabled (1/0) product */
				if (isset($this->request->get['enabled']) && $this->request->get['enabled'] != "" && ctype_digit($this->request->get['enabled'])) {
					$enabled = intval($this->request->get['enabled']);
				} else {
					$enabled = 1;
				}
				
				$this->load->model('account/gss_order'); 
				$json = $this->model_account_gss_order->get_inv_levels($offset, $limit, $enabled);
			//}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}
	
	//API call to update inventory level: feed/gss_api/update_inv_level
	public function update_inv_level(){
		$check = $this->checkPlugin(); 
		if(!empty($check['error'])){
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($check));
		} else {
			$json = array(); 
			//if (!isset($this->session->data['api_id'])) {
			//	$json['error'] = $this->language->get('error_permission');
			//} else {
				//1. pass in product_id 
				if (isset($this->request->post['product_id']) && $this->request->post['product_id'] != "" && ctype_digit($this->request->post['product_id'])) {
					$product_id = intval($this->request->post['product_id']);
				} else {
					$json['error'] = 'Please make sure that product_id is valid. '; 
				}

				//2. pass in new quantity 
				if (isset($this->request->post['quantity']) && $this->request->post['quantity'] != "" && is_numeric($this->request->post['quantity'])) {
					$quantity = intval($this->request->post['quantity']);
				} else {
					$json['error'] = 'Please make sure that quantity provided is valid. '; 
				}
				
				if(empty($json)){
					$this->load->model('account/gss_order'); 
					$json = $this->model_account_gss_order->update_inv_level($product_id, $quantity);
				}
			//}
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}
	
	protected function checkPlugin() {
		$json = array(); 
		
		//feed/gss_api must be enabled
		if (!$this->config->get('gss_api_status')) {
			$json["error"] = 'Extension -> Feeds ->GSS API is disabled. Enable it!';
		}
		
		return $json; 
	}

}
