<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-categoria.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancocategoria;
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('categoria');
?>