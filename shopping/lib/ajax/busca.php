<?php

	include('../../functions/banco.php');
	include('../../conf/tags.php');
	$banco = new banco;
	$banco->Conecta();
	session_start('login');
	
	if(isset($_SESSION["id"]) && $_SESSION["id"] != ''){
		$var = $banco->VerificaAlerta($_SESSION['id']);
	}else{
		$var = '0';
	}
	
	if($var == '1'){
		if(isset($_POST["idchat"]) && $_POST["idchat"] != ''){
			$idchat = $_POST['idchat'];
			$SqlUpdate = "UPDATE c_chat_acomp SET status = '0' where idchat = '".$idchat."' ";
			$result = mysql_query($SqlUpdate);
		}else{
			$sql = "SELECT A.pregao, A.uasg, A.pasta, C.idchat, C.mensagem, C.data
					FROM c_chat_acomp C
					INNER JOIN c_acomp A ON A.idacomp = C.idacomp
					WHERE C.status = '1'
					ORDER BY C.data ASC
					";
			$result = mysql_query($sql);
			$num_rows = mysql_num_rows($result);
			if($num_rows){
				while( $rs = mysql_fetch_array($result , MYSQL_ASSOC) )
				{
					echo utf8_encode('Pregуo: ' . $rs['pregao'] . ' - Pasta: ' . $rs['pasta'] .'|'. $rs['data'] .'|"'. $rs['mensagem'] .'"|Clique em OK para confirmar visualizaчуo|' . $rs['idchat']);
					exit(0);
				}
			}else{
				echo false;
			}
		}
	}else{
		echo false;
	}
?>