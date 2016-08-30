<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>代码评测 CorerMan</title>
		<link rel="stylesheet" href="http://isaves.qiniudn.com/judge/css/judge2.css" type="text/css" />
	</head>
<body>
    <?php
        include("./function/function.php");
		include("./function/judgefunc.php");
        $js="<script src=\"http://isaves.qiniudn.com/judge/js/index.js\"></script>";
        
	/*	error_reporting(E_ALL);
		ini_set('display_errors', '1');
	*/
    ?>
	<center>
		<div class="box">
			<h1>Codes Judge</h1>
		</div>
		<div class="render"></div>
	</center>
	
	<form  action="index.php" method="post" > 
		<label for="id">评测序号:</label> 
		<input style="border-color:#293134;" type="text" value="<?php if($_GET['idnum']=="") print $_POST['id']; else print $_GET['idnum']; ?>" name="id" required />
		<input type="hidden" name="type" value="c" />
		<label for="code">C/C++语言代码:</label> 
		<textarea  style="border-color:#293134;" name="code" required ><?php print $_POST['code'] ?></textarea>
		<input style="border-color:red;" type="submit" value="Judge Code" />
	</form>
	
	<?php
	    //保存代码文件
	   if($_POST['id']&&$_POST['code'])
	   {
	       $cloud_server="./judge_data";
	       $root="./code_temp";
		   $CpuTime=0.5; //设置CPU默认最长运行程序为1秒
		   $Memory=65536;//设置内存默认最大值 kB单位
	        //获取文件信息
	       $problem_id=$_POST['id']; //获取评测序号
	       $codes=$_POST['code']; //获取代码
		   $codes=str_replace("\\n","\\r\\n",$codes); //为linux系统替换换行符号\r\n
	       $format=$_POST['type']; //获取代码语言 为文件类型名
	       $user_id=time().rand(1,100); //为当前用户分配用户id 这个id为关键标识
	       $file_count=0;  //记录评测数组的组数
		   
	      //检测云端评测数据文件是否存在
	      if(!CheckCloudFile($problem_id,$cloud_server))
	      {
	          showError("错误:请输入正确的评测序号!"); //显示错误信息
	          print $js; //输入尾部的js信息
	          exit();
	      }
	      
		  if(checkCodeSer($codes)){
	        showError("错误:您的代码具有威胁性!");
	        print $js; //输入尾部的js信息
	        exit(); 
		  }
		  
	      //数据文件存在 继续执行
	      //根据 user_id 创建相应的用户目录 
	      if(!CreateDir($root,$user_id))
	      {
	        showError("错误:创建用户目录失败!");
	        print $js; //输入尾部的js信息
	        exit();
	      }
	      
	      //读取云端数据 分割保存到 cloud 目录中
	      $file_count=DivFileContent($cloud_server,$problem_id,$user_id,$root); //分割文件并获取in 与 out 的对数
	       
	      //保存用户代码文件  $root  $user_id  $type 这里应该对用户的代码进行安全检测
		  
		  
		  //读取评测条件设置 CpuTime Memory
			$link=$cloud_server."/".$problem_id.".dat"; //构造网络文件链接
			
			if(fopen("$link","r"))  //检测网络文件是否存在
			{
				$Cloud_Data=file($link);
				if(substr($Cloud_Data[0],0,8)=="cputime:")
				{
					$CpuTime=substr($Cloud_Data[0],8); //获取CPU限制时间 以秒为单位
						if($CpuTime>2)
							$CpuTime=2;
							
					if(substr($Cloud_Data[1],0,7)=="memory:") //获取内存大小限制值
					{
						$Memory=substr($Cloud_Data[1],7);
							if($Memory>65536)
							$Memory=65536;		
					}
				}		
			}
			
			//获取文件中CPU时间 内存大小限制数据结束
			$Memory=$Memory*1024; //把KB单位转换为BYTE
			
			$ret=saveUserCode($root,$user_id,$format,$codes,$CpuTime);
			if($ret==2)
			{
				showError("错误:源代码格式无法识别!");
				rmUserFile($root,$user_id, $file_count,$format);//清理垃圾文化
				rmUserDir("./code_temp",$user_id);//清理目录垃圾
				print $js; //输入尾部的js信息
				exit();				
			}
			
			//编译用户代码
			if(Compile($user_id,$root,$format)!=1)
			{
				showError("错误:源代码编译失败!");
				rmUserFile($root,$user_id, $file_count,$format);//清理垃圾文化
				rmUserDir("./code_temp",$user_id);//清理目录垃圾
			//	addCloudCount($_POST[id],"2");
				print $js; //输入尾部的js信息
				exit();
			}
			
			//编译成功 运行文件并产生out数据 $root  $userId   $file_count
			//文件是否存在判断已在编译函数中测试过了
			//每运行一次进行一次判断
			$runRet=runProblem($root,$user_id,$file_count,$CpuTime); //获取运行返回值
			if($runRet==0) //生成的数据不符合
			{
				showError("Wrong Answer!");
				rmUserFile($root,$user_id, $file_count,$format);//清理垃圾文化
				rmUserDir("./code_temp",$user_id);//清理目录垃圾
				//错误计数加1
				addCloudCount($_POST[id],"2");
				print $js; //输入尾部的js信息
				exit();				
			}
			else if($runRet==2) //运行超时
			{
				showError("Time Limit Exceeded!");
				rmUserFile($root,$user_id, $file_count,$format);//清理垃圾文化
				rmUserDir("./code_temp",$user_id);//清理目录垃圾
				//成功计数加1		
				print $js; //输入尾部的js信息
				exit();		
			}
			else
			{
				showError("Accepted!");
				$runRet=round($runRet,5);
				showError("Time:$runRet&nbsp;ms");
				addCloudCount($_POST[id],"1");
			}
			
			
			
			
			
			//清理文件  注意先清理文件再清理目录
			rmUserFile($root,$user_id, $file_count,$format);
	      //清理目录 收尾工作  目录一定要注意权限问题 权限在本脚本运行完后失效
			  if(!rmUserDir("./code_temp",$user_id)) 
			  {
				 showError("错误:清理用户目录失败!");
				 print $js; //输入尾部的js信息
				 exit();	         
			  }
	        
	   } 
	?>
	<?php print $js; ?>
</body>
</html>