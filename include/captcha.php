<?
//error_reporting(0);
//echo '<pre>';
//require_once 'tpl/frag/3dlib.php';
//require_once '3dlib.php';

$clrFg = 0x203956; // fg color
$clrBg = 0xDAE3E0; // bg color

$imgW = 200;
$imgH = 70;
$grainW = mt_rand(1,2);
$grainH = mt_rand(1,2);
$noise = 6;

$bevel = 90;
$bgVariance = 50;
//$text = "923";
$d1 = mt_rand(0,9);
$d2 = mt_rand(1,9);
$d3 = mt_rand(0,9);
//$d1 = 1;
//$d2 = 2;
//$d3 = 0;
$d4 = (abs(($d1+$d2-$d3+10)*($d1/$d2+$d3))*date('j')*date('n'))%10;
$text = $d1.$d2.$d3.$d4;

//$L = new C3DLib(-15,-650);
//$L = new C3DLib(0,-100);
//$L->mAddTransform('Turn', 'X', -85);
//$L->mAddTransform('Turn', 'X', -180);
//$L->mAddTransform('Turn', 'Z', -10);
//$L->mAddTransform('Turn', 'Y', 7);

$it = imageCreate($imgW, $imgH);
$itBg = imageColorAllocate($it, 128, 128, 128);
$itFg = imageColorAllocate($it, 0, 0, 0);

//	imageTTFText($it, $imgH*0.52, 0, 0,$imgH*0.825, $itFg, 'img/cambriab.ttf', $text);
//	imageTTFText($it, $imgH*0.52, 0, 0,$imgH*0.825, $itFg, 'img/impact.ttf', $text);

imageTTFText($it, $imgH*0.6, 0, $imgW*.1,$imgH*0.9, $itFg, $_SERVER['DOCUMENT_ROOT'].'/mail/captcha/digifaw.ttf', $text);


$im = imageCreate($imgW, $imgH);

$clrBgR = ($clrBg>>16)&0xFF;
$clrBgG = ($clrBg>>8)&0xFF;
$clrBgB = $clrBg&0xFF;
$clrFgR = ($clrFg>>16)&0xFF;
$clrFgG = ($clrFg>>8)&0xFF;
$clrFgB = $clrFg&0xFF;

$imBg = imageColorAllocate($im, $clrBgR, $clrBgG, $clrBgB);
$imFg = imageColorAllocate($im, $clrFgR, $clrFgG, $clrFgB);
$imBdr = imageColorAllocate($it, 0, 0, 0);

if (1) {
//	$c = array();
	$v = round($bgVariance/2);
	for ($i=0; $i<$bgVariance;$i++) {
		$r = mt_rand(-$v, $v);
//		$r = mt_rand(0, -$v);
		$c[] = imageColorAllocate($im, $clrBgR+$r, $clrBgG+$r, $clrBgB+$r);
//			imagecolordeallocate($im, $c[$i]);
	}
//		var_dump($c);
	for($x=0; $x<$imgW; $x++) {
//		echo $x.' ';
//			$c = imageColorAllocate($im, $clrBgR+mt_rand(0,115), $clrBgG+mt_rand(0,115), $clrBgB+mt_rand(0,115));
		for($y=0; $y<$imgH; $y++) {
//			echo $y.' ';
//				$c = imageColorAllocate($it, $clrBgR+mt_rand(0,115), $clrBgG+mt_rand(0,115), $clrBgB+mt_rand(0,115));
//				$c = imagecolorallocate($im, )
//				imagesetpixel($im, $y*2, $x*2, $c);
//			imagesetpixel($im, $x, $y, $c[mt_rand(0,9)]);
//			imagecolordeallocate($im, $c);
		}
	}

	$b = mt_rand(0,$imgW*2);
	for($i=0;$i<=$imgW;$i++) {
		$aSin[$i] = round($imgH/8*sin(($b+$i)/$imgH*3))-$imgW/30;
//		echo $i. ' -> '.$aSin[$i].'<br>';
	}
//	var_dump($c);

	for($x=$grainW; $x<$imgW; $x += $grainW) {
//		echo '<br>';
		for($y=$grainH; $y<$imgH; $y += $grainH) {
			if (imagecolorat($it, $x, $y)) {
//				echo $y.' / ';
				$y1 = $y;
				$y1 = $aSin[$x]+$y1;
//				$y = number_format($y,0);
//				echo $y.' ';
//				$L->mBar3d($im, array($x, $y, 0), 1, 1, $bevel, $imFg);
				imageline($im, $x+mt_rand(-$noise,$noise), $y1+mt_rand(-$noise,$noise), $x+mt_rand(-$noise,$noise), $y1+mt_rand(-$noise,$noise), $itFg);
//				imageline($im, $x, $y1, $x+2, $y1+2, $itFg);

			} else {
				imagesetpixel($im, $x, $y, $c[mt_rand(0,$bgVariance-1)]);
			}
//				$L->mFilledPolygon($im, array(
//					array($x-$grainW, $y-$grainH, imageColorAt($it, $x-$grainW, $y-$grainH) ? 0 : $bevel),
//					array($x, $y-$grainH, imagecolorat($it, $x, $y-$grainH) ? 0 : $bevel),
//					array($x, $y, imagecolorat($it, $x, $y) ? 0 : $bevel),
//					array($x-$grainW, $y, imagecolorat($it, $x-$grainW, $y) ? 0 : $bevel),
//				), $imBg);
//				$L->mPolygon($im, array(
//					array($x-$grainW, $y-$grainH, imagecolorat($it, $x-$grainW, $y-$grainH) ? 0 : $bevel),
//					array($x, $y-$grainH, imagecolorat($it, $x, $y-$grainH) ? 0 : $bevel),
//					array($x, $y, imagecolorat($it, $x, $y) ? 0 : $bevel),
//					array($x-$grainW, $y, imagecolorat($it, $x-$grainW, $y) ? 0 : $bevel),
//				), $imFg);
		}
	}
}


//echo '</pre>';
//ImageFilledPolygon($im, array(0,0,10,50,50,30), 3, $itFg);
//$L->mFilledPolygon($im, array(array(0,0,0), array(10,150,10), array(50,30,70), array(5,3,7)), $itFg);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");                     // ��� � ��諮�
header("Last-Modified: " . gmdate("D, d M Y H:i:s", 10000) . " GMT"); // 1 ﭢ��� 1970
header("Cache-Control: no-store, no-cache, must-revalidate");         // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);            // �� ࠧ, ��� ���������
header("Pragma: no-cache");                                           // HTTP/1.0
header("Content-Type:image/png");
//imagePNG($it);
//imageJPEG($im);
imagePNG($im);
imagedestroy($im);
imagedestroy($it);
?>