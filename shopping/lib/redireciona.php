<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-redireciona.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancoredireciona;
	
	$idproduto = $this->PaginaAux[0];
	$link = $banco->BuscaLink($idproduto);
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('redireciona-inner');
	$Conteudo = str_replace('<%URLPADRAO%>',UrlPadrao,$Conteudo);
	$Conteudo = str_replace('<%URL%>',$link,$Conteudo);
?>