<?
//header('Content-type: application/text');

include_once('common/Db.php');

class Index extends Db {
	//private $domain = 'http://192.38.112.80/bif/';
	private $domain = 'http://localhost/bif/';
	private $char;
	private $ca;
	private $arter;

	public function __construct($char) {
		parent::__construct();
		$this->style();

		if (!isset($_GET['arter'])) {
			$this->arter='da';
			$this->ca=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','x','y','z','Æ','Ø','Å');
		} else {
			switch($_GET['arter']) {
				case 'int' : 
					$this->arter='int';
					$this->ca=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','x','y','z');
					break;
				default :
					$this->arter='da';
					$this->ca=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','x','y','z','Æ','Ø','Å');
					break;
			}
		}

		echo '<div style="padding-left:20px;">'
?>
			<a href="/" id="show-search-simple" style="text-decoration:none;">&#171;&nbsp;Simpel søgning</a>
			&nbsp;>&nbsp;
			<a href="http://allearter.dk/" style="text-decoration:none;">Projekt Allearter Startside</a>
			<br><br>
			<h1>Artsregister - alle arter A - Å</h1>
			<br>
<?
		

		$this->arterMenu();

		$this->char= (in_array($char, $this->ca)) ? $char : 'a'; 
		
		if ($this->arter=='da') {
			$SQL='select Videnskabeligt_navn, Dansk_navn from allearter where Dansk_navn like "'.$this->char.'%" order by Dansk_navn asc';
		} else {
			$SQL='select Videnskabeligt_navn, Dansk_navn from allearter where Dansk_navn="" and Videnskabeligt_navn like "'.$this->char.'%" order by Videnskabeligt_navn asc';
		}
		$header='<h1 class="taksonomi">'.ucfirst($this->char).'</h1>';
		//echo $SQL;
		mysql_set_charset('utf8');
		$result=$this->query($SQL);

		echo '<div class="header-cnt">';
		echo $header;
		$this->leftMenu();
		echo '</div>';
		echo '<div class="sitemap-cnt"><br/>';
		while ($row = mysql_fetch_array($result)) {
			$taxon=str_replace(' ','+',$row['Videnskabeligt_navn']);
			if ($this->arter=='da') {
				echo '<a class="taksonomi" href="?taxon='.$taxon.'"><strong>'.$row['Dansk_navn'].'</strong>&nbsp;&nbsp;<i style="font-family:times,serif;color:black;">'.$row['Videnskabeligt_navn'].'</i></a><br>';
			} else {
				echo '<a class="taksonomi" href="?taxon='.$taxon.'"><i style="font-size:1.1em;">'.$row['Videnskabeligt_navn'].'</i></a><br>';
			}
		}
		echo '<br/></div>';
		//01032015
		echo '</div>';
	}

	private function leftMenu() {
		$href='index.php?leksikon&arter='.$this->arter;
		foreach ($this->ca as $a) {
			if ($a==$this->char) {
				echo '<span class="index" style="color:silver;">'.ucfirst($a).'</span>';
			} else {
				echo '<a class="index" href="'.$href.'&index='.$a.'"><span class="index">'.ucfirst($a).'</a></span>';
			}
		}
	}

	private function arterMenu() {
		echo '<div class="arter-menu"><br/>';
		if ($this->arter=='da') {
			echo '<span class="arter-overview">Arter med danske navne</span>';
			echo '&nbsp;&nbsp;&nbsp;&middot;&nbsp;&nbsp;&nbsp;';
			//echo '<span class="arter-overview"><a href="index.php?sitemap=yes&arter=int">Arter uden danske navne</a></span>';
			echo '<span class="arter-overview"><a href="?leksikon&arter=int">Arter uden danske navne</a></span>';
		} else {
			//echo '<span class="arter-overview"><a href="index.php?sitemap=yes&arter=da">Arter med danske navne</a></span>';
			echo '<span class="arter-overview"><a href="?leksikon&arter=da">Arter med danske navne</a></span>';
			echo '&nbsp;&nbsp;&nbsp;&middot;&nbsp;&nbsp;&nbsp;';
			echo '<span class="arter-overview">Arter uden danske navne</span>';
		}
		echo '</div>';
	}				

		
	public function style() {
?>
<style type="text/css">
a.taksonomi {
	text-decoration: none;
	margin-left: 25px;
	font-size: 1.3em;
	white-space: nowrap;
}
h1.taksonomi {
	font-size: 10em;
	padding-left: 20px;
	font-family : 'times','times new roman','serif';
}

span.arter-overview {
	font-size: 1.2em;
	color: silver;
}
span.arter-overview a {
	text-decoration: none;
}
.arter-menu {
	width: 765px;
	border-bottom: 1px solid silver;
}
a.index {
	text-decoration: none;
}
span.index {
	text-decoration: none;
	font-size: 3em;
	margin-right: 45px;	
	float: right;
	clear: right;
	font-family : 'courier new','times','times new roman','serif';
}
.header-cnt {
	float: left;
	width: 140px;
	height: 100%;
	clear : left;
}
.sitemap-cnt {
	float: left;
	width: 500px;
	height: 100%;
	clear: none;
	border-left: 1px solid silver;	
}
</style>
<?
	}

}

if (isset($_GET['index'])) {
	$char=$_GET['index'];
} else {
	$char='a';
}
$index = new Index($char);

?>
