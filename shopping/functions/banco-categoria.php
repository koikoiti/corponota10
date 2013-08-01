<?php
	class bancocategoria extends banco{
		
		#Função que lista as categorias
		function ListaCategoria($Auxilio, $pagina, $categoria){
			$limite = 3;
			$inicio = ($pagina * $limite) - $limite;
			$Banco_Vazio = "Banco esta Vazio";
			#Query Busca Produtos
			$Sql = "SELECT P . * , L.nome AS nome_loja, S.nome AS nome_sub, C.nome AS nome_cat
					FROM s_produtos P
					INNER JOIN s_lojas L ON P.idloja = L.idloja
					INNER JOIN fixo_subcategorias S ON P.idsubcategoria = S.idsubcategoria
					INNER JOIN fixo_categorias C ON S.idcategoria = C.idcategoria
					WHERE C.nome = '".$categoria."'
					ORDER BY P.nome
					LIMIT ".$inicio.", ".$limite."
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
						$Categoria .= $Linha;
					}
			}else{
					
			}
			return $Categoria;
		}
		
		#Função que lista as páginas
		function PaginaCategoria($PaginaAux){
			if($PaginaAux[1] == "pg"){
				$pagina = $PaginaAux[2];
			}else if($PaginaAux[2] == "pg"){
				$pagina = $PaginaAux[3];
			}else{
				$pagina = 1;
			}
			return $pagina;
		}
		
		#Função que monta a Paginação da categoria
		function MontaPaginacaoCat($pagina, $categoria){
			$Sql = "SELECT P . * , L.nome AS nome_loja, S.nome AS nome_sub, C.nome AS nome_cat
					FROM s_produtos P
					INNER JOIN s_lojas L ON P.idloja = L.idloja
					INNER JOIN fixo_subcategorias S ON P.idsubcategoria = S.idsubcategoria
					INNER JOIN fixo_categorias C ON S.idcategoria = C.idcategoria
					WHERE C.nome = '".$categoria."'
					";
			$result = parent::Execute($Sql);
			$num_rows = parent::Linha($result);
			$total = ceil($num_rows/3);
			
			$Auxilio = parent::CarregaHtml('paginacao');
			
			if($pagina == 1){
				$anterior = "<li class='nolink'><< Anterior </li>";
			}else{
				$paginaAnterior = $pagina-1;
				$anterior = "<li><a href='".UrlPadrao."/categoria/".$categoria."/pg/".$paginaAnterior."'><< Anterior </a></li>";
			}
			
			for($i=1; $i<=$total; $i++){
				if($i == $pagina){
					$paginacao .= "<li class='current'><a href='".UrlPadrao."/categoria/".$categoria."/pg/".$i."''>".$i."</a></li>";
				}else{
					$paginacao .= "<li><a href='".UrlPadrao."/categoria/".$categoria."/pg/".$i."''>".$i."</a></li>";
				}
			}
			
			if($pagina == $total){
				$proxima = "<li class='nolink'>Próxima >> </li>";
			}else{
				$paginaProxima = $pagina+1;
				$proxima = "<li><a href='".UrlPadrao."/categoria/".$categoria."/pg/".$paginaProxima."'>Próxima >> </a></li>";
			}
			
			$Auxilio = str_replace('<%ANTERIOR%>',$anterior,$Auxilio);
			$Auxilio = str_replace('<%PAGINACAO%>',$paginacao,$Auxilio);
			$Auxilio = str_replace('<%PROXIMA%>',$proxima,$Auxilio);
			return $Auxilio;
		}
		
	}
?>