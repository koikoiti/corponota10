<?php
	class bancoinicio extends banco{
		
	#Funcao que lista os Docs
		function ListaProdutosInicio($Auxilio){
			$Banco_Vazio = "Banco esta Vazio";
			#Query Busca Docs
			$Sql = "SELECT P . * , L.nome AS nome_loja, S.nome AS nome_sub, C.nome AS nome_cat
					FROM s_produtos P
					INNER JOIN s_lojas L ON P.idloja = L.idloja
					INNER JOIN fixo_subcategorias S ON P.idsubcategoria = S.idsubcategoria
					INNER JOIN fixo_categorias C ON S.idcategoria = C.idcategoria
					";
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
					$Linha = str_replace('<%CATEGORIA%>',$rs['nome_cat'],$Linha);
					$Linha = str_replace('<%SUBCATEGORIA%>', $rs['nome_sub'],$Linha);
					$Linha = str_replace('<%IMAGEM%>',$rs['imagem'],$Linha);
					$Linha = str_replace('<%PRECO%>',$rs['preco'],$Linha);
					$Linha = str_replace('<%LINK%>',$rs['link'],$Linha);
					$Linha = str_replace('<%NOMELOJA%>',$rs['nome_loja'],$Linha);
					$Produtos .= $Linha;
				}
			}else{
				
			}
			return $Produtos;
		}
	
	}
?>