<?php
	error_reporting(0);
	include('includes/config.php');
	/*Just things which are going to database like ip etc.*/
	$today = date("y-m-d"); 
	$ip=$_SERVER['REMOTE_ADDR'];
	$address = $_POST['address'];
	$amount = $dogecoin->getbalance();
	$value = rand(1, 10);
	if($amount < 10){
		$status = 2;
	}else{
	/*Uncomment if you want your payout be dependent on amount you have in wallet*/
	/*if($amount > 10){
		if($amount > 100000){
			$value = rand(1, 1000);
		}elseif($amount > 10000){
			$value = rand(1, 100);
		}elseif($amount > 1000){
			$value = rand(1, 10);
		}else{
			$value = rand(1,5);
		}
	}*/


	$check = "SELECT * FROM logs WHERE DATE(date)=DATE(NOW()) AND ((ip='$ip') OR (wallet='$address')) " or die("Error in the consult.." . mysqli_error($link));
	$result2 = mysqli_query($link, $check);
	echo mysqli_num_rows($result2);
	if(mysqli_num_rows($result2) > 0){
		$status=3;
		$value=0;
	}else{
		$transaction =  $dogecoin->sendtoaddress($address,(float)$value);
		$query = "INSERT INTO logs VALUES (null,'$today',$value,'$address','$ip')" or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link, $query);	
		$status=1;
	}
	}
	header("Location: index.php?status=".$status."&doge=".$value);
?>