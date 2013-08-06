<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-pesquisar.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancopesquisar;
	
	#Coloca na variável a tag que quer buscar
	$busca = $this->PaginaAux[0];
	
	#monta o auxilio para listar os produtos
	$Auxilio = $banco->CarregaHtml('itens/lista-produto-itens');
	
	#Pega a página
	$numPagina = $banco->RetornaPagina($this->PaginaAux);
	
	$Pesquisar = $banco->ListaPesquisa($Auxilio, $busca);
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('pesquisar');
	$Conteudo = str_replace('<%PESQUISAR%>',$Pesquisar,$Conteudo);
?>