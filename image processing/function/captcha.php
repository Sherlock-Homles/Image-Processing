<?php
/*
 *制作验证码图片
 *@param1 int $width,宽度
 *@param2 int $height,高度
 *@param3 int $length=4,字符宽度,默认四个
 *@param4 int $line,干扰线条
 *@param4 int $star,干扰星号
 */
function getCaptcha($width, $height, $length = 4, $line = 20, $star = 50)
{
//1.制作画布：画布的宽度，高度
    $img = imagecreatetruecolor($width, $height);
//2.背景色：默认背景颜色为黑色
    //2.1 分配颜色：让颜色能够在画布上使用，颜色是由0-255，数字越大，颜色越深，使用mt_rand函数实现颜色的随机显示
    $bg_color = imagecolorallocate($img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
    //2.2 填充上色（涂色）：上色逻辑，找到一个点开始上色，系统自动匹配相邻点是否与当前上色点一致：一致一起渲染，不一致不上色
    imagefill($img, 0, 0, $bg_color);
//3.写入内容
    //求位置
    $start   = $width / ($length + 1);
    $captcha = '';
    for ($i = 0; $i < $length; $i++) {
        //随机得到写入内容
        switch (mt_rand(1, 3)) {
            case 1: //大写字母
                $captcha .= chr(mt_rand(97, 122));
                break;
            case 2: //小写字母
                $captcha .= chr(mt_rand(60, 90));
                break;
            case 3: //数字
                $captcha .= mt_rand(1, 9);
                break;
        }
        //字体颜色
        $font_color = imagecolorallocate($img, mt_rand(0, 50), mt_rand(0, 50), mt_rand(0, 50));
        //字体路径
        $font = "C:\Windows\Fonts\FZSTK.TTF";
        //输入文本
        imagettftext($img, mt_rand(20, 30), mt_rand(-45, 45), $start * ($i + 1), mt_rand($height / 2 - 10, $height / 2 + 20), $font_color, $font, $captcha[$i]);
    }
    //将验证码数据存入session中
    @session_start();
    $_SESSION['captcha'] = $captcha;
//4.增加干扰
    //4.1增加干扰点（*)
    for ($i = 0; $i < $star; $i++) {
        $dots_color = imagecolorallocate($img, mt_rand(140, 190), mt_rand(140, 190), mt_rand(140, 190));
        imagestring($img, 5, mt_rand(0, $width), mt_rand(0, $height), '*', $dots_color);
    }

//4.2干扰线
    for ($j = 0; $j < $line; $j++) {
        $lin_color = imagecolorallocate($img, mt_rand(50, $height), mt_rand(50, $height), mt_rand(50, $height));
        imageline($img, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $lin_color);
    }
//5.保存输出
    //告知浏览器当前内容是图片
    header('Content-type:image/png');
    imagepng($img);
//6.销毁资源
    imagedestroy($img);
    echo "<hr/>";
    var_dump($captcha);
}
//调用函数
getCaptcha(200, 100);
