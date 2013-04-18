<?php
	#Include nas funcoes do cliente
	include('functions/banco-login.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancologin;
	
	#Imprimi Valores
	$Conteudo = $banco->CarregaHtml('404');
	$Conteudo = str_replace('<%URLPADRAO%>',UrlPadrao,$Conteudo);
?>