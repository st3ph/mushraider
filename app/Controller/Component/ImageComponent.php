<?php
App::import('Vendor', 'PhpThumbFactory', array('file' => 'phpthumb'.DS.'ThumbLib.inc.php'));

class ImageComponent extends Component {
	function makePath($path) {
		if(!is_dir($path)) {
			mkdir($path);
		}
	}

	function resize($source, $path, $fileName, $maxWidth = 500, $maxHeight = 500) {
		$image = PhpThumbFactory::create($source);
		if($maxWidth || $maxHeight) {
			$image->resize($maxWidth, $maxHeight);
		}
		$this->makePath($path);
		$image->save($path.'/'.$fileName);
	}
}