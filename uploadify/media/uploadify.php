<?php
/*
*@brief 上传文件
*/
defined('DIR_SEP') or define('DIR_SEP',DIRECTORY_SEPARATOR);
defined('DIR_APP_ROOT') or define('DIR_APP_ROOT',dirname(__FILE__));
$targetFolder = 'uploads'; // Relative to the root

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
    $tempName = $_FILES['Filedata']['name'];
    $filetype = pathinfo($tempName, PATHINFO_EXTENSION);

    $folder = checkDir($targetFolder);
    $fileName = time().rand(1000,9999) . '.'.$filetype;

	$fileTypes = array('swf','flv','avi','wmv'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);

	if (in_array($fileParts['extension'],$fileTypes)) {
        $saveFile = DIR_APP_ROOT . DIR_SEP . $targetFolder . DIR_SEP . $folder . DIR_SEP . $fileName;
        move_uploaded_file($tempFile,$saveFile);
        if(filesize($saveFile))
        {
            $sp = str_replace('uploadify.php','',$_SERVER['REQUEST_URI']);
            $json['status'] =1;
//            $json['msg'] = 'http://data.kapai.com/'. $targetFolder . DIR_SEP . $folder . DIR_SEP .$fileName;
            $json['msg'] = $_SERVER['HTTP_HOST']  . $sp . $targetFolder . DIR_SEP . $folder . DIR_SEP .$fileName;
            exit(json_encode($json));
            return '';
        }
        else
        {
            $json['status'] =0;
            $json['msg'] = "上传失败";
            exit(json_encode($json));
            return '';
        }

	} else {
        $json['status'] =0;
        $json['msg'] = "上传类型错误";
        exit(json_encode($json));
        return '';
	}
}

function checkDir($targetFolder)
{

    $folder = date('Y') . DIR_SEP . date('m') . DIR_SEP . date('d');
    $dir = DIR_APP_ROOT . DIR_SEP . $targetFolder . DIR_SEP . $folder;

    if(is_dir($dir))
    {
        return $folder;
    }
    else
    {
        mkdirs($dir);
    }
    return $folder;
}

function mkdirs($path)
{
		if(is_dir($path))
        {
            return TRUE;
        }
        else
        {
            $pPath = dirname($path);
            if(mkdirs($pPath))
            {
                mkdir($path);
            }
        }
		return TRUE;
	}
?>