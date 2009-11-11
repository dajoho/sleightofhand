<?php
class a561_sleightofhand {
	var $VALID=false;
	
	function a561_sleightofhand($settings=array()) {
		global $REX;
		
		$this->settings = $settings;
		
		$this->setting('fontpath',$REX['INCLUDE_PATH'].'/addons/sleightofhand/fonts/');
		
		$font = $this->setting('font');
		$fontpath = $this->setting('fontpath');
		$size = $this->setting('size');
		$color = $this->setting('color');
		$wrap = $this->setting('wordwrap');
		
		
		// do some decoding, as it is possible that html will get passed
		$text = $this->htmlspecialchars_decode($settings['text']);
		$text = strip_tags($text);

		if ($wrap>0) {
			$text = wordwrap($text,$wrap,"\n");
		}
		

		$this->setting('text',$text);
		
		if (empty($font) || empty($size) || empty($color)) {
			$this->VALID = false;
			$this->ERROR = '[$font, $size or $color missing]';
		}
		
		
		if (!empty($font) && !empty($size) && !empty($color) && file_exists($fontpath.$font)) {
		
			$this->VALID = true;
			
			$cachekey = md5(serialize($this->settings));
			$cachepath = $REX['HTDOCS_PATH'].'files/soh/';
			
			if (!file_exists($cachepath)) {
				$result = @mkdir($cachepath);
				
				$this->setting('result',$result);
			}
			
			$cachefile = $cachepath.'soh-'.$cachekey.'.png';
			$this->setting('cachefile',$cachefile);
			
			if (!file_exists($cachefile)) {
			//					$this->generate();
			}
			$this->generate();
		}
	}
	
	
	function islatin() {
		global $REX;
		$pos = strpos($REX['LANG'], '_utf8');
		if ($pos === false) {
			return true;
		} else {
			return false;
		}
	}
	
	
	function generate() {
		global $REX;
		
		$width = 0;
        $height = 0;
        $offset_x = 0;
        $offset_y = 0;
        $bounds = array();
        $image = "";
        
        
		###############################################################
		## determine font height.
        $bounds = ImageTTFBBox($this->setting('size'), 0, $this->setting('fontpath').$this->setting('font'), "W");
		$font_height = abs($bounds[7]-$bounds[1]);		
		$bounds = ImageTTFBBox($this->setting('size'), 0, $this->setting('fontpath').$this->setting('font'), $this->setting('text'));
		$width = abs($bounds[4]-$bounds[6]);
		$height = abs($bounds[7]-$bounds[1]);
		$offset_y = $font_height;
		$offset_x = 0;

		###############################################################
		## Deal with multiple lines
		$spacing = floatVal($this->setting('spacing'));
		if ($spacing == 0 ) {
			$spacing = 1.4;
		}
		$x = $offset_x+20;
		$y = $offset_y+20;
		$lines=explode("\n",$this->setting('text'));
		for($i=0; $i< count($lines); $i++)
		{	$newY=$y+($i * $this->setting('size') * $spacing);			
		}
		$newHeight = $newY + $font_height;
		
		
		
		
		###############################################################
		## Create Alpha Channel
		$image = ImageCreateTrueColor($width+41,$newHeight+41);
		ImageSaveAlpha($image, true);
		//ImageAlphaBlending($image, false); 
		$bg = ImageColorAllocateAlpha($image, 220, 220, 220, 127);
		$bg2 = $bg;
		ImageFill($image, 0, 0, $bg);
		$fg = $this->setting('color');
		$foreground = ImageColorAllocateAlpha($image, $fg[0], $fg[1], $fg[2], 0);
		
		

		###############################################################
		## Render all lines
		$newY = 0;
		for($i=0; $i< count($lines); $i++)
		{	$newY=$y+($i * $this->setting('size') * $spacing);			
			ImageTTFText($image, $this->setting('size'), 0, $x, $newY, $foreground, $this->setting('fontpath').$this->setting('font'),  $lines[$i]);
		}
		
		
		###############################################################
        ## Rotation
		$angle = $this->setting('rotateX');
		if ($angle>0) {
			$magic = new a561_magic;
			$image = $magic->rotate($image,$angle);
			$bg2 = imagecolorat($image, 5, 5);
		}
		
        
   		###############################################################
        ## Auto-Crop
		$p = array_fill(0, 4, 0);
		
		// Get the image width and height.
		$imw = imagesx($image);
		$imh = imagesy($image);
		
		// Set the X variables.
		$xmin = $imw;
		$xmax = 0;

		// Start scanning for the edges.
		for ($iy=0; $iy<$imh; $iy++){
			$first = true;
			for ($ix=0; $ix<$imw; $ix++){
				$ndx = imagecolorat($image, $ix, $iy);

				if ($ndx != $bg && $ndx !=$bg2){
					if ($xmin > $ix){ $xmin = $ix; }
					if ($xmax < $ix){ $xmax = $ix; }
					if (!isset($ymin)){ $ymin = $iy; }
					$ymax = $iy;
					if ($first){ $ix = $xmax; $first = false; }
				}
			}
		}

		$imw = 1+$xmax-$xmin; // Image width in pixels
		$imh = 1+$ymax-$ymin; // Image height in pixels
		
		// Make another image to place the trimmed version in.
		$im2 = imagecreatetruecolor($imw+$p[1]+$p[3], $imh+$p[0]+$p[2]);
	
		// Make the background of the new image the same as the background of the old one.
		ImageSaveAlpha($im2, true);
		ImageAlphaBlending($im2, false); 
		
	    // Copy it over to the new image.
	    imagecopy($im2, $image, $p[3], $p[0], $xmin, $ymin, $imw, $imh);
	    $image = $im2;
		
		
		
		###############################################################
		## Cache the file
		ImagePNG($image,$this->setting('cachefile'));
	}

	
	function getImageLink() {
		global $REX;
		$cachefile = $this->setting('cachefile');
		
		if (file_exists($cachefile)) {
			return $REX['HTDOCS_PATH'].'files/soh/'.basename($cachefile);
		}
	}
	
	
	function getCode() {
		global $REX;
		$cachefile = $this->setting('cachefile');
		if (file_exists($cachefile)) {
			
			$dims = getimagesize($cachefile);
			
			$this->setting('width',$dims[0]);
			$this->setting('height',$dims[1]);

			
			$code = '';
			
			
			$code .= '<span class="soh"';
			
			if ($this->setting('mouseover')!="") {
				$tmp = $this->settings;
				$tmp['color'] = $this->setting('mouseover');
				$rel = $this->getImageLink().','.a561_sleightofhand($tmp,true);
				
				// if using html5 mode, the validator stupidly thinks "rel" is invalid
				// here we remove the rel= for the validator.
				// remember, html5 isn't final, so there is still a good chance rel will be reactivated
				// if you disagree with this, don't use sleightofhand
				$ua = substr($_SERVER['HTTP_USER_AGENT'],0,3);
				if ($ua!="W3C") {
					$code .= ' rel="'.$rel.'"';
				}
			}
			if ($REX['REDAXO']) {
				$text = '';
			} else {
				if ($this->islatin()) {
				$text = htmlentities($this->setting('text'));
				} else {
				$text = htmlentities($this->setting('text'),ENT_QUOTES,'UTF-8');
				}
			}
			$code .= ' style="width:'.$this->setting('width').'px;height:'.$this->setting('height').'px;background-image:url('.$REX['HTDOCS_PATH'].'files/soh/'.basename($cachefile).')">'.$text.'</span>';
			
			if ($this->setting('link')!="") {
				$code = '<a href="'.$this->setting('link').'">'.$code.'</a>';
			}
			
			if ($this->setting('prefix')!="") {
				$code = $this->setting('prefix').$code;
			}
			
			if ($this->setting('suffix')!="") {
				$code = $code.$this->setting('suffix');
			}

			
		
			return $code;
		} else {
			return '<p class="soh-error"><strong>sleightofhand</strong>- Please check permissions on: '.$cachepath.'</p>';
		}
	}
		
		
	
	
	function htmlspecialchars_decode($string,$style=ENT_COMPAT)
    {	$translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS,$style));
		if($style === ENT_QUOTES){ $translation['&#039;'] = '\''; }
		return strtr($string,$translation);
    }
	
	function setting($key=null,$value=null) {
		
		//getter
		if ($key!=null && $value==null) {
			if (isset($this->settings[$key])) {
				return $this->settings[$key];
			} else {
				return '';
			}
		}
		
		//setter
		else if ($key!=null && $value!=null) {
			$this->settings[$key]=$value;
			return true;
		}
	
	}
	
}
?>