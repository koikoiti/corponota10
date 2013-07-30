<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-redireciona.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancoredireciona;
	
	$idproduto = $this->PaginaAux[0];
	$link = $banco->BuscaLink($idproduto);
	
	#Imprime Valores
	$Conteudo = '<iframe style="display:block;margin: 0 auto;" height="100%" width="1024" src="<%LINKOUT%>"></iframe>';
	$Conteudo = str_replace('<%LINKOUT%>',$link,$Conteudo);
?>