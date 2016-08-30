<?php
	function saveUserCode($root,$userId,$type,$codes,$CpuTime){
		//构造文件路径
		$location=$root."/$userId/".$userId.".".$type;
		$file_out=fopen($location,"w");
		$cpu_limit_head=" #include <sys/time.h>
 #include <sys/resource.h>

 void Limit(){
     struct rlimit cpulimit;
     getrlimit(RLIMIT_CPU,&cpulimit);
     cpulimit.rlim_cur=$CpuTime;
     cpulimit.rlim_max=$CpuTime;
     setrlimit(RLIMIT_CPU,&cpulimit); 
	 
     getrlimit(RLIMIT_AS,&cpulimit);
     cpulimit.rlim_max=1;    
     setrlimit(RLIMIT_AS,&cpulimit); 
 }\n";  //cpulimit.rlim_max=1  大约为2100000个 int 单元
		fwrite($file_out,$cpu_limit_head); //输出cpu限制代码的头部
		$count=preg_match("/\bmain\b\ *\([\r\n\ \w\*\,]*\)[\ \n\r]*\{/i",$codes,$mainstr);
		if($count==0){
				return 2; //代码格式识别
				fclose($file_out);
				}
		$codes=str_replace($mainstr[0],$mainstr[0]."Limit();\n",$codes);
		fwrite($file_out,$codes);
		fclose($file_out);
	}
?>


<?php
	function getTime(){
		//统一为毫秒
		$time=gettimeofday();
		$hsecond=(float)$time[sec]*1000+(float)$time[usec]/1000;
	return $hsecond;
	}
?>

<?php
	function Compile($userId,$root,$type){
		$common=$root."/".$userId."/".$userId; //不加格式名的路径信息
		$source=$common.".$type"; //带有格式名的路径完整信息
		$retinfo=`g++ $source -o $common`; //编译代码操作
		if(file_exists($common)||file_exists($common.".exe"))
			return 1; //编译成功
		else 
		{
			return 0; //编译中出现错误，编译失败
		}
			
	}
?>

<?php
	function compFile($cloudOut,$debugOut){
		if(file_exists($cloudOut)&&file_exists($debugOut))
		{	//如果两个文件都存在
			$cloudFile=file($cloudOut);
			$debugFile=file($debugOut);
			$count1=count($cloudFile); 
			$count2=count($debugFile);
			if($count1!=$count2)
				return 0;
			else{
				for($i=0;$i<$count1;$i++)
				{
					if($cloudFile[$i]!=$debugFile[$i]) 
						break;
				}
				if($i==$count1)
					return 1;
				else 
					return 0;
			}
		}
	
	}
?>

<?php
	function runProblem($root,$userId,$file_count,$CpuTime)
	{
		$common=$root."/".$userId."/"; //构造公共路径
		$i=1;
		$starttime=getTime();
		while($i<=$file_count)
		{
			//产生debug out文件
			$exe=$common.$userId; //构造可在执行文件路径
			$cloud=$common."cloud/";//构造云端文件in out 目录路径
			$debug=$common."debug/";//构造用户测试运行产生out的目录
			$cloudin=$cloud.$i.".in";
			$cloudout=$cloud.$i.".out";
			$debugout=$debug.$i.".out";
			if(file_exists($cloud."$i.in"))
			{	//如果云端in文件存在 导入in文件 产生out文件
				$command="$exe <$cloudin >$debugout";
				$eachStart=getTime(); //获取每次的运行的开始时间
				`$command`; //执行命令
				$eachEnd=getTime(); //获取每次运行终止的时间
				if(($eachEnd-$eachStart)>($CpuTime*1000)) //时间超过毫秒单位时间
				{
					return 2; //运行超时
				}	
				
				//产生out文件后 开始匹配文件信息是否相同
				if(!compFile($cloudout,$debugout))
				{
					return 0;
				}
			}
			$i++;
		}
		$endtime=getTime(); //获取毫秒数
		$averageTime = ($endtime-$starttime)/$file_count;
		return $averageTime;  //返回平均运行时间  毫秒
	}

?>