<?php
/*
 * 图片添加水印
 * @param string $img 背景图片(待加水印的图片)
 * @param string $watermark 水印图片地址
 * @param string $district 水印位置
 * @param integer $watermarkquality 图片水印质量
 * @param string $path 水印图片储存路径
 * @return  mixed
 */
function watermark($img, $watermark, $path, $district = 0, $watermarkquality = 95)
{
    $imginfo       = @getimagesize($img); //获取图片大小
    $watermarkinfo = @getimagesize($watermark); //获取水印大小
    $img_w         = $imginfo[0];
    $img_h         = $imginfo[1];
    $watermark_w   = $watermarkinfo[0];
    $watermark_h   = $watermarkinfo[1];
    if ($district == 0) {
        //如果位置默认为0，则随机选择水印位置
        $district = rand(1, 9);
    }

    if (!is_int($district) or 1 > $district or $district > 9) {
        $district = 9;
    }

    switch ($district) {
        case 1:
            $x = +5;
            $y = +5;
            break;
        case 2:
            $x = ($img_w - $watermark_w) / 2;
            $y = +5;
            break;
        case 3:
            $x = $img_w - $watermark_w - 5;
            $y = +5;
            break;
        case 4:
            $x = +5;
            $y = ($img_h - $watermark_h) / 2;
            break;
        case 5:
            $x = ($img_w - $watermark_w) / 2;
            $y = ($img_h - $watermark_h) / 2;
            break;
        case 6:
            $x = $img_w - $watermark_w;
            $y = ($img_h - $watermark_h) / 2;
            break;
        case 7:
            $x = +5;
            $y = $img_h - $watermark_h - 5;
            break;
        case 8:
            $x = ($img_w - $watermark_w) / 2;
            $y = $img_h - $watermark_h - 5;
            break;
        case 9:
            $x = $img_w - $watermark_w - 5;
            $y = $img_h - $watermark_h - 5;
            break;
    }
    //判断图片格式
    switch ($imginfo[2]) {
        case 1: //gif图片
            $im = @imagecreatefromgif($img);
            break;
        case 2: //jpg图片
            $im = @imagecreatefromjpeg($img);
            break;
        case 3: //png图片
            $im = @imagecreatefrompng($img);
            break;
    }
    //判断水印格式
    switch ($watermarkinfo[2]) {
        case 1: //gif图片
            $watermark_logo = @imagecreatefromgif($watermark);
            break;
        case 2: //jpg图片
            $watermark_logo = @imagecreatefromjpeg($watermark);
            break;
        case 3: //png图片
            $watermark_logo = @imagecreatefrompng($watermark);
            break;
    }
    if (!$im or !$watermark_logo) {
        return false;
    }

    $dim = @imagecreatetruecolor($img_w, $img_h);
    if (@imagecopy($dim, $im, 0, 0, 0, 0, $img_w, $img_h)) {
        imagecopy($dim, $watermark_logo, $x, $y, 0, 0, $watermark_w, $watermark_h);
    }
    //保存名称
    $file = dirname($img) . '/w' . basename($img);
    // header('content-type:image/jpeg');
    // imagejpeg($dim);
    $water_file = 'water_' . basename($img);
    $result     = imagejpeg($dim, $path . '/' . $water_file, $watermarkquality);
    imagedestroy($watermark_logo);
    imagedestroy($dim);
    imagedestroy($im);
    if ($result) {
        return $file;

    } else {
        return false;

    }
}
// waterMark('image/高动态光照渲染风景3.jpg', 'image/Sony Lens G.png', 'image/water');
