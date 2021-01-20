<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $html_title;?></title>
<link href="css/install.css" rel="stylesheet" type="text/css">
<link href="css/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
</head>
<body>
<?php echo $html_header;?>
<div class="main">
  <div class="text-box" id="text-box">
    <div class="license">
       <p>
                请您在使用(快简易CMS)前仔细阅读如下条款。包括免除或者限制作者责任的免责条款及对用户的权利限制。您的安装使用行为将视为对本《用户许可协议》的接受，并同意接受本《用户许可协议》各项条款的约束。 <br /><br />
                一、安装和使用： <br />
                (快简易CMS)是免费和开源提供给您使用的，您可安装无限制数量副本。 您必须保证在不进行非法活动，不违反国家相关政策法规的前提下使用本软件。 <br /><br />
                二、免责声明：  <br />
                本软件并无附带任何形式的明示的或暗示的保证，包括任何关于本软件的适用性, 无侵犯知识产权或适合作某一特定用途的保证。  <br />
                在任何情况下，对于因使用本软件或无法使用本软件而导致的任何损害赔偿，作者均无须承担法律责任。作者不保证本软件所包含的资料,文字、图形、链接或其它事项的准确性或完整性。作者可随时更改本软件，无须另作通知。  <br />
                所有由用户自己制作、下载、使用的第三方信息数据和插件所引起的一切版权问题或纠纷，本软件概不承担任何责任。<br /><br />
                三、协议规定的约束和限制：  <br />
                禁止去除(快简易CMS)源码里的版权信息，商业授权版本可去除后台界面及前台界面的相关版权信息。</br>
                禁止在(快简易CMS)整体或任何部分基础上发展任何派生版本、修改版本或第三方版本用于重新分发。</br></br>
                <strong>版权所有 (c) 2020-2020,快简易CMS,保留所有权利</strong>。
            </p>
    </div>
  </div>
  <div class="btn-box"><a href="index.php?step=1" class="btn btn-primary">同意协议进入安装</a><a href="javascript:window.close()" class="btn">不同意</a></div>
</div>
<?php echo $html_footer;?>
<script type="text/javascript">
$(document).ready(function(){
    //自定义滚定条
    $('#text-box').perfectScrollbar();
});
</script>
</body>
</html>
