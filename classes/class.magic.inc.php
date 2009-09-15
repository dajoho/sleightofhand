<?php
class a561_magic {
	
	function a561_magic() {
		global $REX;
		$this->locateMagic();
		$this->generated = $REX['INCLUDE_PATH'].'/generated/files/';
	}
	
	function locateMagic() {
		global $REX;
		$this->match = false;
		$paths = array(
			$REX['ADDON']['settings']['sleightofhand']['imagemagic'].'/convert',
			'convert',
			'./convert',
			'/usr/bin/convert',
			'/opt/local/bin/convert',
		);
		
		foreach ($paths as $path) {
			if (!$this->match) {
			$x = @exec($path);
				if (strlen($x)>0) {
					$this->match = true;
					$this->convert = $path;
				}
			}
		}
	}
	
	
	function rotate($image, $angle) {
		if ($this->match) {
			$angle = intVal($angle);
			$srckey = $this->generated.'soh-'.rand().rand();
			$dstkey = $srckey.'-rotated';
			ImagePNG($image,$srckey.'.png');
			
			exec($this->convert.'  -background none -rotate '.$angle.' '.$srckey.'.png png32:'.$dstkey.'.png');
			$image = imagecreatefrompng($dstkey.'.png');
			ImageSaveAlpha($image, true);
			ImageAlphaBlending($image, false); 
						
			@unlink($srckey.'.png');
			@unlink($dstkey.'.png');
		}
		return $image;
	}
	

}
?>