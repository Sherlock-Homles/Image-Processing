<?php
//后台文件上传处理
//1.接收表单数据
$captcha = trim($_POST['captcha']);
$file    = $_FILES['file'];
// var_dump($captcha,$file);
// 2.验证码合法性判断：验证码不能为空，且长度为4位
if (empty($captcha) || strlen($captcha) != 4) {
    //mb_strlen(mb扩展需开启)
    //验证码有问题：回到上级页面
    echo "<script>alert('验证码格式错误！');history.go(-1);</script>";
    exit();
}
//3.验证码有效性验证
session_start();
if (strtolower($_SESSION['captcha']) != strtolower($captcha)) {
    var_dump($captcha);
    var_dump($_SESSION['captcha']);
    //用户提交的验证码与服务器不一致
    // header("refresh:3;url=" . $_SERVER['HTTP_REFERER']);//PHP方式回到上一级页面
    echo "<script>alert('验证信息有误，请重新输入！');history.go(-1);</script>"; //JavaScript方式回到上级页面
    exit();
}
//4文件上传-->普通方式
//4.1 判断文件是否有误
// if ($file['error'] != 0) {
//     //有错误
//     header("refresh:3;url=" . $_SERVER['HTTP_REFERER']);
//     echo "文件上传失败！";
//     exit();
// }
// //4.2 没有错误：移动到指定的上传目录
// $dir = 'uploads/';
// move_uploaded_file($file['tmp_name'], $dir . '' . $file['name']);

//4文件上传-->利用上传函数实现
include 'function/uploader_function.php';
$filename = uploader($file, 'Images/upload', $error);
//判定
if (!$filename) {
    header("refresh:3;url=" . $_SERVER['HTTP_REFERER']);
    echo $error;
    exit();
}

//5.制作水印图
include 'function/watermark.php';
//调用
watermark('Images/upload/' . $filename, 'Images/Sony Lens G.png', 'Images/upload/watermark');
//6.制作缩略图
include 'function/thumb.php';
//调用
thumb('Images/upload/' . $filename, 'Images/upload/thumb', 200, 200);
//6.上传+水印+缩略图成功，返回成功信息
echo '<div style="width: 100%; color: #66ccff;text-align: center;font-size: 50px;font-family: 幼圆；">文件上传成功！<br/><img src="Images/77.jpg"></div>';
