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
		$imageSize  = $image->getSize();
		if($maxWidth || $maxHeight) {
			if(!$maxHeight && $imageSize->getWidth() > $maxWidth) {
				$image->resize($imageSize->widen($maxWidth));
			}elseif(!$maxWidth && $imageSize->getHeight() > $maxHeight) {
				$image->resize($imageSize->heighten($maxHeight));
			}elseif($maxWidth && $maxHeight) {
				$image->resize(new Box($maxWidth, $maxHeight));
			}
		}
		$this->makePath($path);
		$image->save($path.'/'.$fileName);
	}
}