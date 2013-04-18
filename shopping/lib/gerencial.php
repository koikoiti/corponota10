<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-gerencial.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancogerencial;
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('gerencial');
?>