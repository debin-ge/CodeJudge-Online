<?php
function ListVolume(){
 //对卷轴数量进行列表
//构造目录路径
$dir="./volumelist/";
$i=1;
while(file_exists($dir."volume".$i))
{
	$line=sprintf("<li><a href=\"?Volume=%d\">Volume %d</a></li>",$i,$i);
	print $line;
	$i++;
}
}
?>

<?php
function listProblem($cloud,$volumeDir)
{
	$problem_name=file($volumeDir);
	$problem_count=count($problem_name); //对卷轴中题目数量进行计数
	//进行云端文件判断
	$cloud_link=$cloud."/set/"; //构造云端根目录下的set目录
	for($i=0;$i<$problem_count;$i++)
	{
		//$problem_name[$i]=iconv("gb2312","utf-8",$problem_name[$i]); //window下转码
		$problem_name[$i]=str_replace("\r\n","",$problem_name[$i]);
		$file_set_link=$cloud_link.$problem_name[$i].".set";
		//print $file_set_link."<br />";
		$test=fopen($file_set_link,"r");
		if($test) //如果云端文件存在
		{
			fclose($test);
			$set_list=file($file_set_link); //读取题目信息
			$set_name=$set_list[0]; //文件第一行是名称
			$set_name=iconv("gb2312","utf-8",$set_name);
			$set_jianshu=$set_list[1];//文件第二行是简述
			$set_jianshu=iconv("gb2312","utf-8",$set_jianshu);
			$set_admin=$set_list[2];//文件第三行是发布者
			$set_admin=iconv("gb2312","utf-8",$set_admin);
			$set_judge_id=$set_list[3]; //获取评测序号
			//构造百分比目录路径
			$location="./volumedata/baifenbi/".$set_judge_id."/"; 
			
			$temp=file($location."solve");
			$solve=$temp[0]; //解决的人数
			$temp=file($location."nosolve");
		//	print $localhost;
			$nosolve=$temp[0];//总共尝试次数
			$line=sprintf("<tr><td class=\"user-name\"><a href=\"./viewproblem/?name=%s&idnum=%s\">%s</a></td><td class=\"user-email\">%s</td><td class=\"user-phone\">%s</td><td class=\"user-mobile\">%d/%d</td></tr>",$set_name,$set_judge_id,$set_name,$set_jianshu,$set_admin,$solve,$solve+$nosolve);
			print $line;
		}
	
	}
}

?>