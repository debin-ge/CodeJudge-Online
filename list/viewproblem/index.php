<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8" />
<meta name="author" content="Mobify">
<title>ICCP 挑战详情</title>
<link rel="stylesheet" href="http://isaves.qiniudn.com/iccp/list/viewproblem/bellows.min.css">
<link rel="stylesheet" href="http://isaves.qiniudn.com/iccp/list/viewproblem/bellows-theme.min.css">
<link rel="stylesheet" href="http://isaves.qiniudn.com/iccp/list/viewproblem/main.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="-bellows" style="background: url('http://isaves.qiniudn.com/bg/iccp/bg2.png');">

<div class="viewport">
	<div class="main-content">
		<h2 id="demo" style="color:white;"><?php print $_GET['name'] ?></h2>
		<div class="bellows">
			<div class="bellows__item bellows--is-open">
				<div class="bellows__header">
					<h3>题目描述</h3>
				</div>
				<div class="bellows__content">
					<pre>
<?php
$commonDir="../volumedata/info/".iconv("utf-8","GB2312",$_GET['name'])."/";
if(is_dir($commonDir))
{
	$content=file($commonDir."problem_info");
	$totalcount=count($content);
	for($i=0;$i<$totalcount;$i++)
		print $content[$i];
}
?>
					</pre>
				</div>
			</div>
			<div class="bellows__item">
				<div class="bellows__header">
					<h3>输入规则</h3>
				</div>
				<div class="bellows__content">
<pre>
<?php
$commonDir="../volumedata/info/".iconv("utf-8","GB2312",$_GET['name'])."/";
if(is_dir($commonDir))
{
	$content=file($commonDir."problem_in");
	$totalcount=count($content);
	for($i=0;$i<$totalcount;$i++)
		print $content[$i];
}
?>
</pre>
				</div>
			</div>
			<div class="bellows__item">
				<div class="bellows__header">
					<h3>输出规则</h3>
				</div>
				<div class="bellows__content">
<pre>
<?php
$commonDir="../volumedata/info/".iconv("utf-8","GB2312",$_GET['name'])."/";
if(is_dir($commonDir))
{
	$content=file($commonDir."problem_out");
	$totalcount=count($content);
	for($i=0;$i<$totalcount;$i++)
		print $content[$i];
}
?>
</pre>
				</div>
			</div>
			<div class="bellows__item">
				<div class="bellows__header">
					<h3> 限制 & 运行</h3>
				</div>
				<div class="bellows__content">
<pre>
<?php
$commonDir="../volumedata/info/".iconv("utf-8","GB2312",$_GET['name'])."/";
if(is_dir($commonDir))
{
	$content=file($commonDir."problem_limit");
	$totalcount=count($content);
	for($i=0;$i<$totalcount;$i++)
		print $content[$i];
}
?>
 <center><div style="font-weight:bold;font-size:16px;width:100px;height:60px;background-color:#293134;border-radius:5px;color:#D3891F;" onclick="location.href='../../judge/?idnum=<?php print $_GET['idnum']; ?>';">
开始评测</center></pre>
				</div>
				</div>
			</div>
		</div>
		<!-- JavaScript -->
		<script src="http://isaves.qiniudn.com/iccp/list/viewproblem/js/jquery-1.10.1.min.js"></script>
		<script src="http://isaves.qiniudn.com/iccp/list/viewproblem/js/highlight.pack.js"></script>
		<script src="http://isaves.qiniudn.com/iccp/list/viewproblem/js/velocity.min.js"></script>
		<script src="http://isaves.qiniudn.com/iccp/list/viewproblem/js/bellows.min.js"></script>
		<script>
			$(function(){
			$('.bellows').bellows();
			 });
		</script>
	</div>
</div>
</body>
</html>
