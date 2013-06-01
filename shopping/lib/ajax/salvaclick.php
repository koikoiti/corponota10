<?php
	include('../../functions/banco.php');
	include('../../conf/tags.php');
	$banco = new banco;
	$banco->Conecta();

	$hora = date('Y-m-d H:i:s');
	
	$sql = "Insert Into s_clicks (click_time , remote_addr , link , idproduto) 
			VALUES ('".$hora."' , '".$_SERVER['REMOTE_ADDR']."' , '".$_POST['link']."' , '".$_POST['idproduto']."' )
			";
	$result = mysql_query($sql);
	echo true;
?>