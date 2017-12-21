<?php

class ModelAccountGssOrder extends Model {

	public function getOrderByStatusId($status, $offset, $limit, $date_from, $date_to) {
		$query = "SELECT  o.*, ot.value AS shipping_cost FROM `" . DB_PREFIX . "order` AS o "
				. "LEFT JOIN `" . DB_PREFIX . "order_total` As ot ON o.order_id = ot.order_id "
				. "WHERE o.order_status_id = '" . (int) $status . "' "
				. "AND ot.code = 'shipping' ";
		
		if(!empty($date_from)){
			$query .= "AND date_modified >= '$date_from' "; 
		}
		
		if(!empty($date_to)){
			$query .= "AND date_modified <= '$date_to' ";
		}
		
		$query .=  "LIMIT " . (int)$limit . " OFFSET " . (int)$offset * (int)$limit; 
		
		try{
			$order_query = $this->db->query($query);
		} catch (Exception $ex) {
			$ret = array('error' => $ex->getMessage());
			//Anything else ?? 
		}
		
		if(!empty($ret)){
			return $ret; 
		}

		if ($order_query->num_rows) {

			try{
				$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_query->row['shipping_country_id'] . "'");
			} catch (Exception $ex) {
				return array('error' => $ex->getMessage());
			}
			
			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			try{
				$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['shipping_zone_id'] . "'");
			} catch (Exception $ex) {
				return array('error' => $ex->getMessage());
			}
				
			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
			foreach ($order_query->rows as $key => $value) {
				$order_query->rows[$key]['shipping_iso_code_2'] = $shipping_iso_code_2;
				$order_query->rows[$key]['shipping_iso_code_3'] = $shipping_iso_code_3;
				$order_query->rows[$key]['shipping_zone_code'] = $shipping_zone_code;
				
				//retrieve order_product from DB 
				$order_id = $order_query->rows[$key]['order_id'];
				try{
					$products = $this->db->query("SELECT op.*, p.sku, p.weight FROM `" . DB_PREFIX . "order_product` as op JOIN `" . DB_PREFIX . "product` as p USING (product_id) WHERE op.order_id = '$order_id'");
				} catch (Exception $ex) {
					return array('error' => $ex->getMessage());
				}
				
				$order_query->rows[$key]['line_item'] = $products->rows;
				unset($order_id);
				unset($products);
			}   
			
			return $order_query->rows;
		} else {
			return array();
		}
	}
	
	public function getOrderStatusId($status_name){
		$query = "SELECT  * FROM `" . DB_PREFIX . "order_status` WHERE name = '" . $this->db->escape(trim($status_name)) . "' "; //SQL Injection????
		
		try{
			$status_query = $this->db->query($query);
		} catch (Exception $ex) {
			$ret = array('error' => $ex->getMessage());
			//Anything else ?? 
		}
		
		if(!empty($ret)){
			return $ret; 
		}
			
		if ($status_query->num_rows) {
			return $status_query->rows;
		} else {
			return array(); 
		}
	}
	
	public function get_inv_levels($offset, $limit, $enabled){
		$query = "SELECT  * FROM `" . DB_PREFIX . "product` WHERE status = " . (int)$enabled 
				. " LIMIT " . (int)$limit . " OFFSET " . (int)$offset * (int)$limit;
		try{
			$status_query = $this->db->query($query);
		} catch (Exception $ex) {
			$ret = array('error' => $ex->getMessage());
			//Anything else ?? 
		}
		
		if(!empty($ret)){
			return $ret; 
		}
		
		if ($status_query->num_rows) {
			return $status_query->rows;
		} else {
			return array(); 
		}	
	}
	
	public function update_inv_level($product_id, $quantity){
		//build update query 
		$query = "UPDATE `" . DB_PREFIX . "product` SET quantity = $quantity, date_modified = NOW() WHERE product_id = $product_id "; 
		
		try{
			$status_query = $this->db->query($query);
		} catch (Exception $ex) {
			$ret = array('error' => $ex->getMessage());
			//Anything else ?? 
		}
		
		if(!empty($ret)){
			return $ret; 
		}
		
		if($this->db->countAffected() === 0 ){
			return array('error' => 'Updated 0 row in DB.'); 
		} else {
			return array('success' => 'Update successfully.'); 
		}
	}
	
	
}
