<?php
namespace VaKKuum\AshokaBot\utils;

class PNGParser{
	
	public static function parse(string $path): ?array{
		$info = explode(".", @basename($path));
		$type = $info[count($info) - 1];
		$img = false;
		if($type == "png"){
			$img = @imagecreatefrompng($path);
		}elseif(($type == "jpg") or ($type == "jpeg")){
			$img = @imagecreatefromjpeg($path);
		}
		
		if(!$img){
			return null;
		}
		
		$w = @imagesx($img);
		$h = @imagesy($img);
		$data = [];
		
		for($x = 0; $x < $w; $x++){
			for($y = 0; $y < $h; $y++){
				$color = @imagecolorat($img, $x, $y);
				$rgb = @imagecolorsforindex($img, $color);
				
				$r = $rgb['red'];
				$g = $rgb['green'];
				$b = $rgb['blue'];
				
				$data[$x.":".$y] = [$r, $g, $b];
			}
		}
		
		return $data;
	}
	
	public static function parseImage($img): array{
		$w = @imagesx($img);
		$h = @imagesy($img);
		$data = [];
		
		for($x = 0; $x < $w; $x++){
			for($y = 0; $y < $h; $y++){
				$color = @imagecolorat($img, $x, $y);
				$rgb = @imagecolorsforindex($img, $color);
				
				$r = $rgb['red'];
				$g = $rgb['green'];
				$b = $rgb['blue'];
				
				$data[$x.":".$y] = [$r, $g, $b];
			}
		}
		
		return $data;
	}
	
	public static function loadImage($img): array{
		$w = @imagesx($img);
		$h = @imagesy($img);
		$data = [];
		
		for($x = 0; $x < $w; $x++){
			for($y = 0; $y < $h; $y++){
				$color = @imagecolorat($img, $x, $y);
				$rgb = @imagecolorsforindex($img, $color);
				
				$r = $rgb['red'];
				$g = $rgb['green'];
				$b = $rgb['blue'];
				
				$data[$x.":".$y] = ($r + $g + $b) / 3;
			}
		}
		
		return $data;
	}
	
	public static function toGray($image){
		@imagefilter($image, IMG_FILTER_GRAYSCALE);
		
		return $image;
	}
}
?>