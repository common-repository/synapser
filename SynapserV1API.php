<?php

define("SYNAPSER_BASE_API", "http://ws.synapser.net/api/v1");
define("SYNAPSER_API_VERSION", "v1_1.2.0");

/**
 * This is the Synapser API client
 * 
 * @author Synapser.net
 *
 */
class SynapserV1API{

const TRACKING_CODE_TEMPLATE = '
<!-- Start Synapser Code -->
<script type="text/javascript">
var _syq = _syq || {};
(function(){ var syu=(("https:" == document.location.protocol) ? "https://ws.synapser.net/" : "http://ws.synapser.net/");
_syq.account="{domainKey}";
var syd=document, syg=syd.createElement("script"), sys=syd.getElementsByTagName("script")[0]; syg.type="text/javascript"; syg.defer=true; syg.async=true; syg.src=syu+"sy.js";
sys.parentNode.insertBefore(syg,sys); })();
</script>
<!-- End Synapser Code -->
';
	
	var $meta_api_url = '';
	var $keys_api_url = '';
	
	var $hashKey = '';
	var $domainKey = '';
	
	var $debugEnabled = false;
	
	
	/**
	 * Constructor
	 * 
	 * @param unknown_type $hashKey the string identify your hashKey
	 */
	public function SynapserV1API($hashKey, $domainKey, $debugEnabled=false){
		$this->meta_api_url = SYNAPSER_BASE_API . "/page/meta/json";
		$this->keys_api_url = SYNAPSER_BASE_API . "/page/keys/json";

		$this->hashKey = $hashKey;
		$this->domainKey = $domainKey;
		
		$this->debugEnabled = $debugEnabled;
		
		if($this->debugEnabled){
			echo "<!-- SynapserV1API.DEBUG - SYNAPSER_API_VERSION=" . SYNAPSER_API_VERSION . " --> \n";
		}
	}
	
	
	/**
	 * Call the service URL and return the result as a JSON object
	 * 
	 * @param unknown_type $url
	 */
	private function getURL($url){
		if($this->debugEnabled){
			echo "<!-- SynapserV1API.DEBUG - getURL::url=" . $url . " --> \n";
		}
	
		$json = file_get_contents($url);
		return json_decode($json, false);
	}
	
	
	/**
	 * Return the meta for the current page
	 *
	 */
	public function getCurrentPageMeta(){
		$pageUrl = $_SERVER["REQUEST_URI"];
		
		if($this->debugEnabled){
			echo "<!-- SynapserV1API.DEBUG - getCurrentPageMeta::pageUrl=" . $pageUrl . " --> \n";
		}
		
		return $this->getPageMeta($pageUrl);
	}
	
	
	/**
	 * Return the meta for a page
	 * 
	 * @param unknown_type $pageUrl
	 */
	public function getPageMeta($pageUrl){
		$url = $this->meta_api_url . "?hashkey=" . $this->hashKey . "&url=" . urlencode($pageUrl);
		
		$meta = $this->getURL($url);
		
		if($this->debugEnabled){
			echo "<!-- SynapserV1API.DEBUG - getPageMeta::meta= \n";
			print_r( $meta );
			echo "-->\n";
		}
		
		return $meta;
	}
	
	
	/**
	 * Return the keys for the current page
	 *
	 */
	public function getCurrentPageKeys(){
		$pageUrl = $_SERVER["REQUEST_URI"];
		
		if($this->debugEnabled){
			echo "<!-- SynapserV1API.DEBUG - getCurrentPageKeys::pageUrl=" + $pageUrl + " --> \n";
		}
		
		return $this->getPageKeys($pageUrl);
	}
	
	
	/**
	 * Return the keys for a page
	 * 
	 * @param unknown_type $pageUrl
	 */
	public function getPageKeys($pageUrl){
		$url = $this->keys_api_url . "?hashkey=" . $this->hashKey . "&url=" . urlencode($pageUrl);
		
		$keys = $this->getURL($url);
		
		if($this->debugEnabled){
			echo "<!-- SynapserV1API.DEBUG - getPageKeys::keys= \n";
			print_r( $keys );
			echo "-->\n";
		}
		
		return $keys;
	}
	
	
	/**
	 * Return the tracking code as JS block
	*/
	public function getTrackingCode(){
		$tracking_js = str_replace("{domainKey}", $this->domainKey, self::TRACKING_CODE_TEMPLATE);
		
		return $tracking_js;
	}
	
	
	/**
	 * Return the API client version
	*/
	public function getVersion(){
		return SYNAPSER_API_VERSION;
	}
}
?>