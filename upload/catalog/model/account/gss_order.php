<?php

class ModelAccountGssOrder extends Model {

	public function getOrderByStatusId($status, $offset, $limit, $date_from, $date_to) {
		$query = "SELECT  * FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int) $status . "' ";
		
		if(!empty($date_from)){
			$query .= "AND date_modified >= '$date_from' "; 
		}
		
		if(!empty($date_to)){
			$query .= "AND date_modified <= '$date_to' ";
		}
		
		$query .=  "LIMIT " . (int)$limit . " OFFSET " . (int)$offset * (int)$limit; 
		
		$order_query = $this->db->query($query);

		if ($order_query->num_rows) {

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
			foreach ($order_query->rows as $key => $value) {
				$order_query->rows[$key]['shipping_iso_code_2'] = $shipping_iso_code_2;
				$order_query->rows[$key]['shipping_iso_code_3'] = $shipping_iso_code_3;
				$order_query->rows[$key]['shipping_zone_code'] = $shipping_zone_code;
			}   
			return $order_query->rows;
		} else {
			return array();
		}
	}
	
}
