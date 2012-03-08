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
        $quality = $this->setting('quality');
        $align = $this->setting('text-align');
        
        $quality = intVal($quality);
        if ($quality==0) {
            $quality = 4;
        }
        $this->setting('quality',$quality);
        
        // do some decoding, as it is possible that html will get passed
        $text = $this->htmlspecialchars_decode($settings['text']);
        $text = strip_tags($text);

        if ($this->islatin()) {
            $text = utf8_decode($text);
        }

        if ($wrap>0) {
            $text = wordwrap($text,$wrap,"\n");
        }
        

        $this->setting('text',$text);
        
        if (empty($font) || empty($size) || empty($color) || empty($text)) {
            $this->VALID = false;
            $this->ERROR = '[$font, $size or $color missing]';
        }
        
        
        if (!empty($font) && !empty($size) && !empty($color) && file_exists($fontpath.$font)) {
        
            $this->VALID = true;
            
            $cachekey = md5(serialize($this->settings));
            $cachepath = $this->filespath().'soh/';
            
            if (!file_exists($cachepath)) {
                $result = @mkdir($cachepath);
                
                $this->setting('result',$result);
            }
            
            $cachefile = $cachepath.'soh-'.$cachekey.'.png';
            $this->setting('cachefile',$cachefile);
            
            if (!file_exists($cachefile)) {
                $this->generate();
            }
            //force compiling for development
            #$this->generate();
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
    
    function filespath() {
        global $REX;
        if (!defined('IS_SALLY')) {
            return $REX['HTDOCS_PATH'].'files/';
        } else {
            return $REX['HTDOCS_PATH'].'data/dyn/public/sleightofhand/';
        }
        
    }
    
    
    function generate() {
        global $REX;
        
        // this isn't really needed, and should be commented out while testing
        // it is only here for poorly configured servers
        @ini_set('max_execution_time', 300); 
        @ini_set('memory_limit','256M');
        
        $width = 0;
        $height = 0;
        $offset_x = 0;
        $offset_y = 0;
        $bounds = array();
        $image = "";
        
        $size_multiply = $this->setting('size')*$this->setting('quality');
        $spacing = $this->setting('spacing');
        
        ###############################################################
        ## determine font height.
        // andreas: http://www.redaxo.de/165-Moduldetails.html?module_id=188
        $abc = 'öäüÖÄÜßABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_0123456789;:<>/(){}%$§"!';
        $bounds = ImageTTFBBox($size_multiply, 0, $this->setting('fontpath').$this->setting('font'), $abc);
        $size = $this->convertBoundingBox($bounds);
        
        $bounds = ImageTTFBBox($size_multiply, 0, $this->setting('fontpath').$this->setting('font'), $this->setting('text'));
        $size2 = $this->convertBoundingBox($bounds);
        
        $width = $size2['width']+40; //this will be cropped
        $height = $size['height'];
        $offset_y = $size['yOffset'];
        $offset_x = 0;

        ###############################################################
        ## Deal with multiple lines
        
        $spacing = floatVal($spacing);
        if ($spacing == 0 ) {
            $spacing = 1.4;
        }
        $x = $offset_x;
        $y = $offset_y;
        $lines=explode("\n",$this->setting('text'));
        $newY = 0;

        for($i=0; $i< count($lines); $i++) {
            $newY=$y+($i * $size_multiply * $spacing);
        }
        $newHeight = $newY+$size['belowBasepoint'];
        
        ###############################################################
        ## Create Alpha Channel
        $image = ImageCreateTrueColor($width,$newHeight);
        ImageSaveAlpha($image, true);
        //ImageAlphaBlending($image, false); 
        $bg = ImageColorAllocateAlpha($image, 220, 220, 220, 127);
        $bg2 = $bg;
        ImageFill($image, 0, 0, $bg);
        $fg = $this->convertHex($this->setting('color'));
        $foreground = ImageColorAllocateAlpha($image, $fg[0], $fg[1], $fg[2], 0);

        ###############################################################
        ## Render all lines
        $newY = 0;
        $align = $this->setting('text-align');
        
        for($i=0; $i< count($lines); $i++) {
            $newY=$y+($i * $size_multiply * $spacing);
            
            $bounds = ImageTTFBBox($size_multiply, 0, $this->setting('fontpath').$this->setting('font'), $lines[$i]);
            switch ($align) {
                case "left":
                case "l":
                case "":
                    // do nothing
                break;
                
                case "center":
                case "centre":
                case "c":
                    $x = ceil(($width - $bounds[2]) / 2); 
                break;
                
                case "right":
                case "r":
                    $x = ($width - $bounds[2]);
                break;
            }
        
            ImageTTFText($image, $size_multiply, 0, $x, $newY, $foreground, $this->setting('fontpath').$this->setting('font'),  $lines[$i]);
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
        
        for ($iy=0; $iy<$imh; $iy++) {
            $first = true;
            for ($ix=0; $ix<$imw; $ix++){
                $ndx = imagecolorat($image, $ix, $iy);

                if ($ndx != $bg && $ndx !=$bg2){
                    if ($xmin > $ix){ $xmin = $ix; }
                    if ($xmax < $ix){ $xmax = $ix; }
                    if ($first){ $ix = $xmax; $first = false; }
                }
            }
        }

        $imw = 1+$xmax-$xmin; // Image width in pixels
        
        // Make another image to place the trimmed version in.
        $im2 = imagecreatetruecolor($imw+$p[1]+$p[3], $imh+$p[0]+$p[2]);
        
        
        // Make the background of the new image the same as the background of the old one.
        ImageSaveAlpha($im2, true);
        ImageAlphaBlending($im2, false); 
        
        // Copy it over to the new image.
        imagecopy($im2, $image, $p[3], $p[0], $xmin, 0, $imw, $imh);
        $image = $im2;
        
        
        // Antialiasing (downsampling)
        // robcs (http://forum.redaxo.de/sutra74521.html#74521)
        $imgw_X = imagesx($image);
        $imgh_X = imagesy($image);
        $image_antialised = imagecreatetruecolor($imgw_X / $this->setting('quality'), $imgh_X / $this->setting('quality'));
        ImageSaveAlpha($image_antialised, true);
        ImageAlphaBlending($image_antialised, false);
        imagecopyresampled($image_antialised, $image, 0,0,0,0, $imgw_X / $this->setting('quality'), $imgh_X / $this->setting('quality'), $imgw_X, $imgh_X);

        // Combine mouseover with main image
        if ($this->setting('mouseover')!="") {
                $tmp = $this->settings;
                $tmp['color'] = $this->setting('mouseover');
                unset($tmp['mouseover']);
                $mouseover = a561_sleightofhand($tmp,true);
                
                $sw = imagesx($image_antialised);
                $sh = imagesy($image_antialised);
                
                $newcanvas = imagecreatetruecolor(($sw*2)+10,$sh);
                $bg = ImageColorAllocateAlpha($image, 220, 220, 220, 127);
                ImageFill($newcanvas, 0, 0, $bg);
                
                ImageSaveAlpha($newcanvas, true);
                ImageAlphaBlending($newcanvas, false);
                $basepng =  $image_antialised;
                $mouseoverpng =  imagecreatefrompng($mouseover);
                
                $this->setting('mouseoverpng',$mouseover);
                
                imagecopyresampled($newcanvas,$basepng, 0,  0,  0,  0, $sw , $sh, $sw , $sh);
                imagecopyresampled($newcanvas,$mouseoverpng, $sw+10,  0,  0,  0, $sw , $sh, $sw , $sh);
                
                //overwrite the original base png with combined version
                $image_antialised = $newcanvas;
        }
        
        
        ###############################################################
        ## Cache the file
        ImagePNG($image_antialised,$this->setting('cachefile'));
        
        
    }

    function convertHex($hex) {
        if (is_array($hex)) {
            return $hex;
        }
        $hex = str_replace('#','',$hex);
        
        if (strlen($hex)==6) {
            return array(
                hexdec(substr($hex, 0, 2)),
                hexdec(substr($hex, 2, 2)),
                hexdec(substr($hex, 4, 2))
            );
        }
    }
    
    function getImageLink() {
        global $REX;
        $cachefile = $this->setting('cachefile');
        
        if (file_exists($cachefile)) {
            return $this->filespath().'soh/'.basename($cachefile);
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
            
            $classes = array();
            $classes[] = 'soh';
            
            if ($this->setting('mouseover')!="") {
                $this->setting('width',intVal($dims[0]/2)-5); // 5 = padding/2
                $classes[] = 'soh-mouseover';
            }
            
            $code .= '<span class="'.implode(' ',$classes).'"'; 
            
            if ($this->setting('mouseoverpng')!="") {
                $rel = $this->getImageLink().','.$this->setting('mouseoverpng');
                    
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
            $code .= ' style="width:'.$this->setting('width').'px;height:'.$this->setting('height').'px;background-image:url('.$this->filespath().'soh/'.basename($cachefile).')">'.$text.'</span>';
            
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
        
    function convertBoundingBox($bbox) {
        if ($bbox[0] >= -1) {
            $xOffset = -abs($bbox[0] + 1);
        } else {
            $xOffset = abs($bbox[0] + 2);
        }
        $width = abs($bbox[2] - $bbox[0]);
        if ($bbox[0] < -1) {
            $width = abs($bbox[2]) + abs($bbox[0]) - 1;
        }
        $yOffset = abs($bbox[5] + 1);
        if ($bbox[5] >= -1) {
            $yOffset = -$yOffset; // Fixed characters below the baseline.
        }
        $height = abs($bbox[7]) - abs($bbox[1]);
        if ($bbox[3] > 0) {
            $height = abs($bbox[7] - $bbox[1]) - 1;
        }
        return array(
            'width' => $width,
            'height' => $height,
            'xOffset' => $xOffset, // Using xCoord + xOffset with imagettftext puts the left most pixel of the text at xCoord.
            'yOffset' => $yOffset, // Using yCoord + yOffset with imagettftext puts the top most pixel of the text at yCoord.
            'belowBasepoint' => max(0, $bbox[1])
        );
    }
    
    
    function htmlspecialchars_decode($string,$style=ENT_COMPAT) {
        $translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS,$style));
        if($style === ENT_QUOTES){ $translation['&#039;'] = '\''; }
            $string = strtr($string,$translation);
            $string = str_replace('&amp;','&',$string);
            return $string;
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