<?
define(CURL_ENABLED, true);

class Scraper  {
	private $url;
	private $header = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8';
	public $result;

	public function __construct($url) {
		$this->url=$url;
	}

	private function exec_CURL() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_HEADER, true);// $this->header);
		curl_setopt($ch, CURLOPT_TRANSFERTEXT, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //
		curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
		curl_setopt($ch, CURLOPT_CURLOPT_MAXREDIRS , 10);
		$this->result = curl_exec($ch);
		curl_close($ch);
	}

	private function exec_FGC() {
		$this->result=file_get_contents($this->url);
	}
	
	public function run() {
		switch (CURL_ENABLED) {
			case true : $this->exec_CURL(); break;
			case false : $this->exec_FGC(); break;
		}
	}
}

$url = $_GET['url'];
$scraper = new Scraper($url);
$scraper->run();
echo '<pre>';
print_r($scraper->result);
echo '</pre>';

?>
