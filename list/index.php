<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ICCP Information List</title>
<link rel="stylesheet" href="http://isaves.qiniudn.com/iccp/list/css/style2.css" media="screen" type="text/css" />
<link rel="stylesheet" type="text/css" href="http://isaves.qiniudn.com/iccp/list/css/demo.css" />
<!--必要样式-->
<link rel="stylesheet" type="text/css" href="http://isaves.qiniudn.com/iccp/list/css/component.css" />
</head>
<body>
<?php
include("./list_function.php");
?>
<nav>
    <ul>
		<li><a href="">卷轴</a></li>
		<?php
		ListVolume(); //对卷轴数量进行列表 ./volumelist/volume1
		?>
    </ul>
</nav>
<div class="component" >
		<table>
			<thead>
				<tr>
					<th>挑战名称</th>
					<th>简要描述</th>
					<th>发布者</th>
					<th>通过率</th>
				</tr>
			</thead>
			<tbody>
				<?php
				//列题目
				$volumeId=1;
				if($_GET["Volume"])
				{
					if(file_exists("./volumelist/volume".$_GET["Volume"]))
					{
						$volumeId=$_GET["Volume"];
					}	
				}
				$cloud="./volumedata";//云端服务器地址
				listProblem($cloud,"./volumelist/volume".$volumeId); //根据volume路径读取里面的题目名称	
				
				?>
				</tbody>
		</table>
	</div>


<script src="http://isaves.qiniudn.com/iccp/list/js/jquery.min.js"></script>
<script src="http://isaves.qiniudn.com/iccp/list/js/jquery.ba-throttle-debounce.min.js"></script>
<script src="http://isaves.qiniudn.com/iccp/list/js/jquery.stickyheader.js"></script>
</body>
</html>