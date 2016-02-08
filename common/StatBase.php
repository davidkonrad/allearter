<?

class StatBase extends Db {

	public function __construct() {
		parent::__construct();
	}

	protected function th($caption, $width=0) {
		$w = ($width!=0) ? 'width:'.$width.'px;' : '';
		echo '<th class="ui-state-default" style="font-weight:bold;color:darkslategray;'.$w.'">'.$caption.'</th>';
	}

	protected function linkify($table, $caption, $scope) {
		$href=urlencode($caption);
		$href='?'.$table.'='.$href;
		$title='Klik for at se alle arter indenfor '.$scope.' '.$caption;
		return '<a class="stat-link" href="'.$href.'" title="'.$title.'">'.$caption.'</a>';
	}

}

?>
