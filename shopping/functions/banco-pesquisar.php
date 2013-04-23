<?php
	class bancopesquisar extends banco{
		
		function ListaPesquisa($Auxilio, $busca){
			$Banco_Vazio = "Banco esta Vazio";
			$busca = urldecode($busca);
			#Query Busca Produtos
			$Sql = "Select P.*,L.nome as nomeloja from s_produto P
					INNER JOIN s_loja L on P.idloja = L.idloja";
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
					$Linha = str_replace('<%SUBCATEGORIA%>', $rs['subcategoria'],$Linha);
					$Linha = str_replace('<%IMAGEM%>',$rs['imagem'],$Linha);
					$Linha = str_replace('<%PRECO%>',$rs['preco'],$Linha);
					$Linha = str_replace('<%LINK%>',$rs['link'],$Linha);
					$Linha = str_replace('<%NOMELOJA%>',$rs['nomeloja'],$Linha);
					$Pesquisa .= $Linha;
				}
			}else{
			
			}
			return $Pesquisa;
		}
		
	}
?>