<?php
/*缩略图制作函数封装
 *@param1 string $src 目标图片地址
 *@param2 string $path 存储路径
 *@param3 int $max_w 画布宽度，默认100px
 *@param4 int $max_h 画布高度，默认100px
 *@param5 $flag bool 是否等比缩放图 默认为false
 */
function thumb($src, $path, $max_w, $max_h, $flag = true)
{
//1.打开原图资源
    $imginfo = @getimagesize($src); //获取图片大小
    //判断图片格式
    switch ($imginfo[2]) {
        case 1: //gif图片
            $img = @imagecreatefromgif($src);
            break;
        case 2: //jpg图片
            $img = @imagecreatefromjpeg($src);
            break;
        case 3: //png图片
            $img = @imagecreatefrompng($src);
            break;
    }
    // $src_img = imagecreatefromjpeg($src);
    //2.打开缩略图资源
    $dst_img = imagecreatetruecolor($max_w, $max_h);
    //补白背景
    $bg_color = imagecolorclosest($dst_img, 255, 255, 255);
    imagefill($dst_img, 0, 0, $bg_color);
//3.将原图塞到缩略图中
    $src_info = getimagesize($src);
    //原图的宽
    $src_w = imagesx($img);
    //原图的高
    $src_h = imagesy($img);
    //是否等比缩放
    if ($flag) {
//等比
        //求目标图片的宽高
        if ($max_w / $max_h < $src_w / $src_h) {
            //横屏图片以宽为标准
            $dst_w = $max_w;
            $dst_h = $max_h * $src_h / $src_w;
        } else {
            //竖屏图片以高为标准
            $dst_h = $max_h;
            $dst_w = $max_h * $src_w / $src_h;
        }
        //在目标图上显示的位置
        $dst_x = (int) (($max_w - $dst_w) / 2);
        $dst_y = (int) (($max_h - $dst_h) / 2);
    } else {
//不等比
        $dst_x = 0;
        $dst_y = 0;
        $dst_w = $max_w;
        $dst_h = $max_h;
    }
    //生成缩略图
    imagecopyresampled($dst_img, $img, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
//4.保存输出
    // header('content-type:image/jpeg');
    //显示
    // imagejpeg($dst_img);
    //保存
    $img_file = 'img_' . basename($src);
    imagejpeg($img, $path . '/' . $img_file);
//5销毁
    imagedestroy($img);
    imagedestroy($dst_img);
//6.返回执行结果
    return $img_file;

}
//调用函数
// thumb('image/w34512986_p0.jpg', 'image', 200, 200);
