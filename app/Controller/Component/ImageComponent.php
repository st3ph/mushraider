<?php
require APP . 'Vendor/autoload.php';
use Imagine\Image\ImageInterface;
use Imagine\Image\Box;
use Imagine\Image\Point;

class ImageComponent extends Component {
	function makePath($path) {
		if(!is_dir($path)) {
			mkdir($path);
		}
	}

	function resize($source, $path, $fileName, $maxWidth = 500, $maxHeight = 500) {
		$imagine = new Imagine\Gd\Imagine($source);
		$image = $imagine->open($source);
		if($maxWidth || $maxHeight) {
			$image->resize(new Box($maxWidth, $maxHeight));
		}
		$this->makePath($path);
		$image->save($path.'/'.$fileName);
	}
}