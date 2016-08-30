<?php
    function CheckCloudFile($Problem_id,$cloudServer){
        //根据文件序号构造云端数据路径  如果存在则返回1 否则返回0
        $result=0;
        $link=$cloudServer."/".$Problem_id.".dat";
        $file=fopen($link,"r");
        if($file)
        {
            $result=1;
            fclose($file);
        }
        return $result;
    }
?>

<?php
    function showError($infomation){
        print "<center><a class=\"error\">&nbsp;$infomation&nbsp;</a></center>";
    }
?>

<?php
    function CreateDir($rootDir,$user_id){
        $root=$rootDir."/".$user_id."/";
        $user_cloud_dir=$root."cloud";
        $user_debug_dir=$root."debug";
        if(!mkdir($root,0777))
            return 0;
        else if(!mkdir($user_cloud_dir,0777))
            return 0;
        else if(!mkdir($user_debug_dir,0777))
            return 0;  
        return 1; //如果无错误返回1
    }
?>

<?php
    function rmUserDir($rootDir,$user_id){
        $root=$rootDir."/".$user_id."/";
        $user_cloud_dir=$root."cloud";
        $user_debug_dir=$root."debug"; //注意目录的清理顺序
        if(!rmdir($user_debug_dir))
            return 0;
        else if(!rmdir($user_cloud_dir))
            return 0;
        else if(!rmdir($root))
            return 0; 
        return 1; //如果无错误 返回1
    }
?>

<?php
    function DivFileContent($CloudServer,$problem_id,$user_id,$root){  
        $user_cloud_dir=$root."/".$user_id."/cloud/";//构造目录完毕
        $fileContent=file($CloudServer."/".$problem_id.".dat");
     //   print $user_cloud_dir."<br />" . "http://".$CloudServer."/".$problem_id.".dat";
        $fileid=0;
		$centent_id=-1; //记录#标志数量 从0开始 也代表位置数组中的下标
		$file_out;
		$flag_line=array();
		for($i=0;$i<count($fileContent);$i++)
		{
			if(substr($fileContent[$i],0,1)=="#") //取第一个字符 判断#
			{
				$centent_id++;
				$flag_line[$centent_id]=$i;
			}
		}
		
		if(($centent_id+1)%4)  //如果#总数量不为4的倍数 从0开始计数
		{
			print "<script src=\"js/index.js\"></script>";
			showError("错误：评测文件设计有误，请反馈！");
			exit();
		}
			
		$start=0;
		$end=0;

		for($i=0;$i<$centent_id;$i+=2)
		{
			$start=$flag_line[$i];
			$end=$flag_line[$i+1];
			$fileid++; //文件标志加1
			$file_out=fopen($user_cloud_dir.$fileid.".in","w");
			for($start++;$start<$end;$start++)
				fwrite($file_out,$fileContent[$start]);
			fclose($file_out); //in文件关闭
			
			$i+=2; //跳转到out部分
			$start=$flag_line[$i];
			$end=$flag_line[$i+1];		
			$file_out=fopen($user_cloud_dir.$fileid.".out","w");	
			for($start++;$start<$end;$start++)
			{
				if($start==$end-1) //如果是out下一个#上的最后一行 在linux系统下应该清除\r\n
				{
					$fileContent[$start]=str_replace("\r\n","",$fileContent[$start]);
				}
					fwrite($file_out,$fileContent[$start]);
			}
				
			fclose($file_out); //out文件关闭	
		}

		return $fileid; //返回文件序号最大值 从1开始计数
		
    }
?>

<?php
	function rmUserFile($root,$userId,$fileCount,$type)
	{
		$user_cloud=$root."/".$userId."/cloud/";
		$user_debug=$root."/".$userId."/debug/";
		for($i=1;$i<=$fileCount;$i++)
		{
			unlink($user_cloud.$i.".in"); 
			unlink($user_cloud.$i.".out"); 
			unlink($user_debug.$i.".out");
			unlink($root."/".$userId."/".$userId); //注意linux系统下可以不加exe 删除用户执行文件
			unlink($root."/".$userId."/".$userId.".".$type);//删除用户源码文件
		}
	
	}
?>

<?php
	function checkCodeSer($codes){ //检测代码安全
		if(strpos($codes,"system")) //存在system则反馈威胁
			return 1;
		else if(strpos($codes,"sys/")) //去除linux sys/目录引用
			return 1;
		else if(strpos($codes,"fopen")) 
			return 1;
		else if(strpos($codes,"fcntl.h")) 
			return 1;
		else if(strpos($codes,"signal.h")) 
			return 1;
		else if(strpos($codes,"termios.h")) 
			return 1;
		else if(strpos($codes,"time.h")) 
			return 1;
		else if(strpos($codes,"utime.h")) 
			return 1;
		else if(strpos($codes,"asm/"))  //限制内存操作函数
			return 1;
		else if(strpos($codes,"linux/"))  //限制linux内核文件
			return 1;
	}
?>



<?php
function addCloudCount($Problem_id,$flag){
//flag 为1代表成功 为0代表失败
//CURL发送post信息
$uri = "http://210.45.165.129/list/volumedata/baifenbi/count.php";
// 参数数组
$data = array (
        'problemid' => $Problem_id,
		'flag'=>$flag,
// 'password' => 'password'
);
 
$ch = curl_init ();
// print_r($ch);
curl_setopt ( $ch, CURLOPT_URL, $uri );
curl_setopt ( $ch, CURLOPT_POST, 1 );
curl_setopt ( $ch, CURLOPT_HEADER, 0 );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
$return = curl_exec ( $ch );
curl_close ( $ch );
 
//print($return);
}

?>