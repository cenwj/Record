<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>文件上传</title>
<script src="./js/jquery-1.8.3.js?cache=<?php echo time(); ?>" type="text/javascript"></script>
<script src="./js/upload/jquery.uploadify.min.js?cache=<?php echo time(); ?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="./css/uploadify.css?cache=<?php echo time(); ?>">
<style type="text/css">
body {
	font: 15px Arial, Helvetica, Sans-serif;
}
</style>
</head>

<body>
    <div style="margin:0px auto;width: 920px;height: 100%">
        <div>
            <h1>视频文件上传</h1>
            <form>
                <div id="queue"></div>
                <input id="file_upload" name="file_upload" type="file" multiple="true" value="文件上传">
            </form>
            <div id="u_msg"></div>
            <div id="u_url"></div>
        </div>
        <div style="margin-top: 25px;font-size: 13px;color: #660000">
            <?php
            function traverse($path = '.') {
                if(!is_dir($path))
                {
                    return '';
                }
                $current_dir = opendir($path);  //opendir()返回一个目录句柄,失败返回false
                if(!$current_dir)
                {
                    return '';
                }
                 while(($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目
                     $sub_dir = $path . DIRECTORY_SEPARATOR . $file;    //构建子目录路径
                     if($file == '.' || $file == '..') {
                         continue;
                     } else if(is_dir($sub_dir)) {    //如果是目录,进行递归
                        echo '<span style="color:660000;pandding:5px">' . $file . '/</span><br>';
                          traverse($sub_dir);
                     } else {    //如果是文件,直接输出
                         $sp = str_replace('./','/',$sub_dir);
                          echo '<div style="color:#000080;height:20px;line-height:20px"><span style="color:#000000">文件地址:</span>./ ' . $sp .'</div>';
                     }
                }
            }

             traverse('./uploads');
         ?>
        </div>
    </div>
	<script type="text/javascript">
		<?php $timestamp = time();?>
		$(function() {
			$('#file_upload').uploadify({
				'formData'     : {
					'timestamp' : '<?php echo $timestamp;?>',
					'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				},
                fileTypeExts  : '*.avi; *.swf; *.wmv; *.flv;',
				'swf'      : './js/upload/uploadify.swf?cache=<?php echo time()?>',
				'uploader' : './uploadify.php',
                'buttonText ': '文件上传',
                onUploadSuccess    :function(file, data, response) {
                    var res = eval('('+data+')');
                    if(res.status == 1)
                    {
                        $("#u_msg").html('上传成功');
                        $("#u_url").html('链接地址:  ' + res.msg);
                    }
                    else
                    {
                        $("#u_msg").html(res.msg);
                    }
                }
			});
		});
	</script>

</body>
</html>