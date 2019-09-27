<?php
/*
 *文件上传
 *@param1 array  $file，上传文件从$_FIELS里面取出上传文件信息：包含5个元素
 *@param2 string $path,存储路径
 *@param3 string &$error
 *@return string $filename,上传后对应的文件名
 */
function uploader($file, $path, &$error)
{
    //1.验证文件是否正确上传
    switch ($file['error']) {
        case 1:
        case 2:
            //文件过大
            $error = '文件超出大小范围！';
            return false;
        case 3:
            $error = '网络不稳定，文件上传未完成！';
            return false;
        case 4:
            $error = '请选择要上传的文件！';
            return false;
        case 6:
        case 7:
            $error = '服务器未响应！';
            return false;
    }
    //没有问题：移动文件
    //生成随机文件名
    $filename = date('YmdHis'); //时间部分
    //构造随机字母
    for ($i = 0; $i < 6; $i++) {
        $filename .= chr(mt_rand(65, 90));
    }
    //构造后缀
    $filename .= strchr($file['name'], '.');
    //移动
    if (move_uploaded_file($file['tmp_name'], $path . '/' . $filename)) {
        //上传成功
        return $filename;
    } else {
        $error = "文件上传失败，请检查后重试！";
        return false;
    }

}
