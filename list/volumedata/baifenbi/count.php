<?php
if($_POST['problemid']&&$_POST['flag'])
{
	$flag=$_POST['flag'];
	switch ($flag){
		case "1":
				
				$location="./".$_POST['problemid']."/"."solve";
				print $location;
				$num=file($location);
				$solve=(int)$num[0];
				$solve++;
			//	unlink($location);
				$file=fopen($location,"w");
				fprintf($file,"%d",$solve);
				fclose($file);
				break;
		case "2":
				$location="./".$_POST['problemid']."/"."nosolve";	
				print $location;
				$num=file($location);
				$total=(int)$num[0];	
				$total++;
				//unlink($location);
				$file=fopen($location,"w");
				fprintf($file,"%d",$total);
				fclose($file);
				break;
	}
}

?>