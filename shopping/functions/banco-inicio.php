<?php
	class bancoinicio extends banco{
		
	#Funcao que lista os Docs
		function ListaProdutosInicio($Auxilio){
			$Banco_Vazio = "Banco esta Vazio";
			#Query Busca Docs
			$Sql = "Select * from s_produto";
			$result = parent::Execute($Sql);
			$num_rows = parent::Linha($result);
			
			#Monta no Html a Listagem
			if ($num_rows){
				while( $rs = mysql_fetch_array($result , MYSQL_ASSOC) )
				{
					$Linha = $Auxilio;
					$Linha = str_replace('<%ID%>',$rs['idproduto'],$Linha);
					$Linha = str_replace('<%IDLOJA%>', $rs['idloja'], $Linha);
					$Linha = str_replace('<%NOME%>',$rs['nome'],$Linha);
					$Linha = str_replace('<%CATEGORIA%>',$rs['categoria'],$Linha);
					$Linha = str_replace('<%SUBCATEGORIA%>', $rs['subcategoria']);
					$Linha = str_replace('<%IMAGEM%>',$rs['imagem']);
					$Linha = str_replace('<%PRECO%>',$rs['preco']);
					$Linha = str_replace('<%LINK%>',$rs['link']);
					var_dump($Linha);
					$Produtos .= $Linha;
				}
			}else{
				
			}
			return $Produtos;
		}
		
		
	}