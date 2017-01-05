<?php

class ControllerFeedGssApi extends Controller {

	//API call endpoint: feed/gss_api/orders
	public function orders() {
		$this->checkPlugin();

		$json = array(); 
		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			/* check offset parameter */
			if (isset($this->request->get['offset']) && $this->request->get['offset'] != "" && ctype_digit($this->request->get['offset'])) {
				$offset = $this->request->get['offset'];
			} else {
				$offset = 0;
			}

			/* check limit parameter */
			if (isset($this->request->get['limit']) && $this->request->get['limit'] != "" && ctype_digit($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 500;
			}

			if (isset($this->request->get['status']) && $this->request->get['status'] != "" && ctype_digit($this->request->get['status'])) {
				$status = $this->request->get['status'];
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
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	
	public function order_status() {
		$this->checkPlugin();

		$json = array(); 
		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['status']) && $this->request->get['status'] != "") {
				$status_name = $this->request->get['status'];
				
				$this->load->model('account/gss_order');
				$json = $this->model_account_gss_order->getOrderStatusId($status_name);
			} else {
				$json['error'] = "Please pass in status name"; 
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	
	protected function checkPlugin() {
		$json = array("success" => false);
		
		//make sure that extension is enabled
		if (!$this->config->get('gss_api_status')) {
			$json["error"] = 'Extension -> Feeds ->GSS API is disabled. Enable it!';
		}

		if (isset($json["error"])) {
			$this->response->addHeader('Content-Type: application/json');
			echo(json_encode($json));
			exit;
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

}
