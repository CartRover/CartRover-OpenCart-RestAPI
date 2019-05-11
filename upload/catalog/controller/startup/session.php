<?php
class ControllerStartupSession extends Controller {
	public function index() {
		if (isset($this->request->get['token']) && isset($this->request->get['route']) 
				&& ( substr($this->request->get['route'], 0, 4) == 'api/' || substr($this->request->get['route'], 0, 13) == 'feed/gss_api/' ) ) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "api_session` WHERE TIMESTAMPADD(HOUR, 1, date_modified) < NOW()");
		
			$ip_addr = $this->request->server['REMOTE_ADDR'];
			
			// Check for cloudflare forwarded IP address - If coming from a valid Cloudflare address
			if(!empty($this->request->server['HTTP_CF_CONNECTING_IP'])){
				// List of Cloudflare IPv4 ranges: https://www.cloudflare.com/ips/
				$ips = array(
					'103.21.244.0/22',
					'103.22.200.0/22',
					'103.31.4.0/22',
					'104.16.0.0/12',
					'108.162.192.0/18',
					'131.0.72.0/22',
					'141.101.64.0/18',
					'162.158.0.0/15',
					'172.64.0.0/13',
					'173.245.48.0/20',
					'188.114.96.0/20',
					'190.93.240.0/20',
					'197.234.240.0/22',
					'198.41.128.0/17'
				);
				if($this->ipMatch($this->request->server['REMOTE_ADDR'], $ips)){
					$ip_addr = $this->request->server['HTTP_CF_CONNECTING_IP'];
				}
			}
			
			$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "api` `a` LEFT JOIN `" . DB_PREFIX . "api_session` `as` ON (a.api_id = as.api_id) LEFT JOIN " . DB_PREFIX . "api_ip `ai` ON (as.api_id = ai.api_id) WHERE a.status = '1' AND as.token = '" . $this->db->escape($this->request->get['token']) . "' AND ai.ip = '" . $this->db->escape($ip_addr) . "'");
		
			if ($query->num_rows) {
				$this->session->start('api', $query->row['session_id']);
				
				// keep the session alive
				$this->db->query("UPDATE `" . DB_PREFIX . "api_session` SET date_modified = NOW() WHERE api_session_id = '" . (int)$query->row['api_session_id'] . "'");
			}
		} else {
			$this->session->start();
		}
	}
	
	/**
	* Checks if a given IP address matches the specified CIDR subnet/s
	* https://gist.github.com/tott/7684443
	* 
	* @param string $ip The IP address to check
	* @param mixed $cidrs The IP subnet (string) or subnets (array) in CIDR notation
	* @return boolean TRUE if the IP matches a given subnet or FALSE if it does not
	*/
   private function ipMatch($ip, $cidrs) {
	   foreach((array) $cidrs as $cidr) {
			list($subnet, $mask) = explode('/', $cidr);
			if(((ip2long($ip) & ($mask = ~ ((1 << (32 - $mask)) - 1))) == (ip2long($subnet) & $mask))) {
				return true;
			}
	   }
	   return false;
   }
}