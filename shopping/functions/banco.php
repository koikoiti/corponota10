<?php
	class banco{
		
		#funcao que inicia conexao com banco
		function Conecta(){	
			$link = mysql_connect(DB_Host,DB_User,DB_Pass);
			if (!$link) {
				die('Not connected : ' . mysql_error());
			}
			$db_selected = mysql_select_db(DB_Database, $link);
			if (!$db_selected) {
				die ('Can\'t use biblio : ' . mysql_error());
			}
			
		}
		
		#Verifica se tem acesso
		function VerificaAcesso($pagina){
			$idpagina = $this->BuscaPaginaPorId($pagina);
			$Sql = 'Select acesso from c_usuarios where nome = "'.$_SESSION['usuario'].'"';
			$result = $this->Execute($Sql);
			$num_rows = $this->Linha($result);
			if($num_rows){
				$rs = mysql_fetch_array($result , MYSQL_ASSOC);
				$acesso = $rs['acesso'];
				$acesso = explode(",", $acesso);
				if (in_array($idpagina, $acesso)){
					return true;
				}
				return false;
			}
		}
		
		#Busca pagina por id
		function BuscaPaginaPorId($nome){
			$Sql = 'Select idpagina from c_paginas where nome = "'.$nome.'" ';
			$result = $this->Execute($Sql);
			$num_rows = $this->Linha($result);
			if($num_rows){
				$rs = mysql_fetch_array($result , MYSQL_ASSOC);
				$idpagina = $rs['idpagina'];
				return $idpagina;
			}
		}
		
		#Verifica se esta logado
		function VerificaSessao(){
			if( isset($_SESSION['usuario']) ){
				return true;
			}
		}
		
		#Busca o Usuario Logado
		function BuscaUsuarioLogado(){
			$bemVindo = 'Bem Vindo: '.$_SESSION['usuario'].' <a href="<%URLPADRAO%>login/sair">( Sair )</a>';
			return $bemVindo;
		}
		
		#Abre Sessao
		function AbreSessao($nome){
			session_start('login');
			$Sql = 'Select * from c_usuarios where nome = "'.$nome.'" ';
			$result = $this->Execute($Sql);
			$num_rows = $this->Linha($result);
			if($num_rows){
				$rs = mysql_fetch_array($result , MYSQL_ASSOC);
				$_SESSION['usuario'] = $nome;
				$_SESSION['idsetor'] = $rs['idsetor'];
				$_SESSION['id'] = $rs['idusuario'];
			}
		}
		
		#Fecha Sessao
		function FechaSessao(){
			$_SESSION = array();
			session_destroy();
		}		
		
		#funcao que forca a imprimir erros
		public function ChamaErros(){
			ini_set('display_errors', 1);
			ini_set('log_errors', 1);
			ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
			error_reporting(E_ALL);
		}
		
		#Função que chama a pagina.php desejada.
		public function ChamaPhp($Nome){
			@require_once('lib/'.$Nome.'.php');
			return $Conteudo;
		}
	
		#função que monta o html da pagina
		public function CarregaHtml($Nome){
			$filename = 'html/'.$Nome.".html";
			$handle = fopen($filename,"r");
			$Html = fread($handle,filesize($filename));
			fclose($handle);
			return $Html;
		}
		
		#Funcao que executa uma Sql e retorna.
		static function Execute($Sql){
			$result = mysql_query($Sql);
			return $result;
		}
		
		#Funcao que retorna o numero de linhas 
		static function Linha($result){
			$num_rows = mysql_num_rows($result);
			return $num_rows;
		}
		
		#Funcao que redireciona para pagina solicitada
		function RedirecionaPara($nome){
			header("Location: ".UrlPadrao.$nome);
		}
		
		#Funcao que Valida se um campo esta Vazio
		function ValidaVazio($var){
			if (empty($var)){
				$var = false;
			}else{
				$var = true;
			}
			return $var;
		}
		
		#Funcao que valida Senha
		function ValidaSenha($senha,$senhac){
			if ($senha == $senhac && $senha!=""){
				$var = true;
			}else{
				$var = false;
			}
			return $var;
		}
		
		#Funcao que valida se ja existe
		//TODO melhorar funcao.criar uma universal
		function ValidaSeExiste($var){
			$Sql = "Seleect * from c_usuarios where nome = '".$nome."'";
			$result = $this->Execute($Sql);
			$num_rows = $this->Linha($result);
			if($num_rows){
				$var = true;
			}else{
				$var =  false;
			}
			return $var;
		}
		
		#Funcao que verifica se existe uma pagina
		function BuscaPagina($nome){
			$Sql = "Select idpagina from s_paginas where nome='".$nome."'";	
			$result = $this->Execute($Sql);
			$num_rows = $this->Linha($result);
			return $num_rows;
		}
		
		#Funcao que busca o nome da Tela BONITA
		function BuscaNomePaginaTela($nomePagina){
			$Sql = "Select nome_pagina_tela from c_paginas where nome='".$nomePagina."'";
			$result = $this->Execute($Sql);
			$num_rows = $this->Linha($result);
			if ($num_rows){
				$rs = mysql_fetch_array($result , MYSQL_ASSOC);
				$nomePaginaTela = $rs['nome_pagina_tela'];
			}
			return $nomePaginaTela;
		}
		
		#Funcao que carrega as peginas
		function CarregaPaginas(){
			
			#urlDesenvolve = ignora 'livraria' e oq tiver antes.
			$urlDesnvolve = 'corponota10';
			$urlDesnvolve2 = 'shopping';
			$primeiraBol = true;
			$uri = $_SERVER["REQUEST_URI"];
			$exUrls = explode('/',$uri);
			$SizeUrls = count($exUrls)-1;

			$p = 0;
			foreach( $exUrls as $chave => $valor ){
				if( $valor != '' && $valor != $urlDesnvolve && $valor != $urlDesnvolve2 ){
					$valorUri = $valor;
					$valorUri = strip_tags($valorUri);
					$valorUri = trim($valorUri);
					$valorUri = addslashes($valorUri);
					
					if( $primeiraBol ){
						$this->Pagina = $valorUri;
						$primeiraBol = false;
					}else{
						$this->PaginaAux[$p] = $valorUri;
						$p++;
					}
				}
			}
		}
		
		#Função que monta o botao novo, de acordo com a página
		function MontaNovo($pagina){
			$botao = "<button onclick=\"location.href='<%URLPADRAO%>".$pagina."'\" style=\"margin-left: 9px;\" class=\"btn btn-success\" type=\"button\">Novo</button>";
			return $botao;
		}
		
		#Funcao que lista as empresas
		function ListaEmpresa(){
			$Sql = "Select * from fixo_empresas";
			$result = $this->Execute($Sql);
			return $result;
		}
		
		#Funcao que retorna o nome da empresa
		function BuscaNomeEmpresa($id){
			$Sql = "SELECT *
					FROM fixo_empresas
					WHERE idempresa = '".$id."'
					";
			$result = $this->Execute($Sql);
			$rs = mysql_fetch_array($result , MYSQL_ASSOC);
			return $rs['nome'];
		}
		
		#Funcao que lista portais
		function ListaPortais(){
			$Sql = "Select * from fixo_portais";
			$result = $this->Execute($Sql);
			return $result;
		}
		
		#funcao verifica alerta
		function VerificaAlerta($id){
			$Sql = "Select acomp from c_usuarios where idusuario = '".$id."' ";
			$result = $this->Execute($Sql);
			$num_rows = $this->Linha($result);
			if ($num_rows){
				$rs = mysql_fetch_array($result , MYSQL_ASSOC);
				$var = $rs['acomp'];
			}
			return $var;
		}
		
		#
		function Navegacao(){
			if($this->PaginaAux[0]){
				$navegacao = ucfirst($this->Pagina);
				$navegacao .= " >> ";
				$navegacao .= '<a href="'.UrlPadrao.'/'.$this->Pagina.'/'.$this->PaginaAux[0].'">'.$this->PaginaAux[0].'</a>';
				if($this->PaginaAux[1]){
					$navegacao .= " >> ";
					$navegacao .= '<a href="'.UrlPadrao.'/'.$this->Pagina.'/'.$this->PaginaAux[0].'/'.$this->PaginaAux[1].'">'.$this->PaginaAux[1].'</a>';
				}
				return $navegacao;
			}
			
		}
		
		#Funcao que monta o menu
		function MontaMenu(){
			$menu = '<div class="menu-secondary-container">
			<ul class="menus menu-secondary sf-js-enabled">
				<li class="cat-item cat-item-1"><a href="<%URLMODELO%>/shopping/categoria/Massa-Muscular">Massa Muscular</a></li>
				<li class="cat-item cat-item-2"><a href="<%URLMODELO%>/shopping/categoria/Emagrecimento">Emagrecimento</a></li>
				<li class="cat-item cat-item-3"><a href="<%URLMODELO%>/shopping/categoria/Vitaminas-&-Minerais">Vitaminas & Minerais</a></li>
				<li class="cat-item cat-item-4"><a href="<%URLMODELO%>/shopping/categoria/Energia">Energia</a></li>
				<li class="cat-item cat-item-4"><a href="<%URLMODELO%>/shopping/categoria/Outros">Outros</a></li>
			</ul>
			</div>';
			
			if ($this->Pagina == 'loja'){
				$menu = '<div class="menu-secondary-container">
				<ul class="menus menu-secondary sf-js-enabled">
				<li class="cat-item cat-item-1"><a href="<%URLMODELO%>/shopping/loja/'.$this->PaginaAux[0].'/Massa-Muscular">Massa Muscular</a></li>
				<li class="cat-item cat-item-2"><a href="<%URLMODELO%>/shopping/loja/'.$this->PaginaAux[0].'/Emagrecimento">Emagrecimento</a></li>
				<li class="cat-item cat-item-3"><a href="<%URLMODELO%>/shopping/loja/'.$this->PaginaAux[0].'/Vitaminas-&-Minerais">Vitaminas & Minerais</a></li>
				<li class="cat-item cat-item-4"><a href="<%URLMODELO%>/shopping/loja/'.$this->PaginaAux[0].'/Energia">Energia</a></li>
				<li class="cat-item cat-item-4"><a href="<%URLMODELO%>/shopping/loja/'.$this->PaginaAux[0].'/Outros">Outros</a></li>
				</ul>
				</div>';
			}
			$menu = str_replace('<%URLMODELO%>',UrlModelo,$menu);
			return $menu;
		}
		
	#funcao busca subcategoria por nome
		function BuscaSubCategoria($paginaAux){
			$Sql = "SELECT * FROM fixo_subcategorias
						WHERE nome = '".$paginaAux[2]."'
						";
			$result = $this->Execute($Sql);
			$num_rows = $this->Linha($result);
			if ($num_rows){
				return true;
			}else{
				return false;
			}
		}
		
		#funcao busca categoria por nome
		function BuscaCategoria($paginaAux){
			$Sql = "SELECT * FROM fixo_categorias
						WHERE nome = '".$paginaAux[1]."'
						";
			$result = $this->Execute($Sql);
			$num_rows = $this->Linha($result);
			if ($num_rows){
				return true;
			}else{
				return false;
			}
		}
		
		#Função que monta o menu com subcategorias
		function Subcategorias(){
			if($this->Pagina == "categoria"){
				$categoria = $this->PaginaAux[0];
				$subcategorias = '<div class="menu-secondary-container">
									<ul class="menus menu-secondary sf-js-enabled">';
				
				$SqlA = "SELECT idcategoria FROM fixo_categorias
						WHERE nome = '".$categoria."'
						";
				$resultA = $this->Execute($SqlA);
				$rsA = mysql_fetch_array($resultA, MYSQL_ASSOC);
				
				$id = $rsA['idcategoria'];
				
				$Sql = "SELECT nome FROM fixo_subcategorias
						WHERE idcategoria = ".$id."
						";
				$result = $this->Execute($Sql);
				$num_rows = $this->Linha($result);
				#Monta no Html a Listagem
				if ($num_rows){
					while( $rs = mysql_fetch_array($result , MYSQL_ASSOC) )
					{
						$subcategorias .= '<li class="cat-item cat-item-1"><a href="'.UrlPadrao.'/categoria/'. $categoria . '/'. $rs["nome"] .'">'. str_replace("-", " ", $rs["nome"]) .'</a></li>';
					}
				}
				
				$subcategorias .= "</ul></div>";
				return $subcategorias;
					
			# Verifica se é as categorias da loja	
			}elseif($this->Pagina == "loja" && $this->BuscaCategoria($this->PaginaAux)){
				$categoria = $this->PaginaAux[1];
				$subcategorias = '<div class="menu-secondary-container">
									<ul class="menus menu-secondary sf-js-enabled">';
				
				$SqlA = "SELECT idcategoria FROM fixo_categorias
						WHERE nome = '".$categoria."'
						";
				$resultA = $this->Execute($SqlA);
				$rsA = mysql_fetch_array($resultA, MYSQL_ASSOC);
				
				$id = $rsA['idcategoria'];
				
				$Sql = "SELECT nome FROM fixo_subcategorias
						WHERE idcategoria = ".$id."
						";
				$result = $this->Execute($Sql);
				$num_rows = $this->Linha($result);
				#Monta no Html a Listagem
				if ($num_rows){
					while( $rs = mysql_fetch_array($result , MYSQL_ASSOC) )
					{
						$subcategorias .= '<li class="cat-item cat-item-1"><a href="'.UrlPadrao.'/loja/'.$this->PaginaAux[0].'/'. $categoria . '/'. $rs["nome"] .'">'. str_replace("-", " ", $rs["nome"]) .'</a></li>';
					}
				}
				
				$subcategorias .= "</ul></div>";
				return $subcategorias;
			}else{
				return "";
			}
		}
		
		#Função da Lateral01
		function Lateral01(){
			$Auxilio = $this->CarregaHtml('itens/lista-loja-itens');
			$Banco_Vazio = "Banco esta Vazio";
			#Query Busca Docs
			$Sql = "Select * from s_lojas";
			$result = $this->Execute($Sql);
			$num_rows = $this->Linha($result);
			#Monta no Html a Listagem
			if ($num_rows){
				while( $rs = mysql_fetch_array($result , MYSQL_ASSOC) )
				{
					$Linha = $Auxilio;
					$Linha = str_replace('<%ID%>',$rs['idloja'],$Linha);
					$Linha = str_replace('<%NOME%>',$rs['nome'],$Linha);
					$Linha = str_replace('<%URLPADRAO%>',UrlPadrao,$Linha);
					$Lojas .= $Linha;
				}
			}else{
			
			}
			return $Lojas;
		}
		
		#Função da Lateral 02
		function Lateral02(){
			$pesquisar = '<ul class="widget-container"><li id="search-2" class="widget widget_search"><h3 class="widgettitle">Pesquisar no Shopping</h3> 
							<div id="search" title="Type and hit enter">
							    <div onclick="location.href=\''.UrlPadrao.'/pesquisar/\' + document.getElementById(\'s\').value" style="margin-top: -2px; margin-right: -20px;  height: 20px; width: 20px; float: right;"></div>
						    	<form method="post" name="form1" id="searchform" action="javascript:location.href=\''.UrlPadrao.'/pesquisar/\' + document.getElementById(\'s\').value">
						        	<input type="text" value="Pesquisar no Shopping" name="pesquisar" id="s" onblur="if (this.value == \'\')  {this.value = \'Pesquisar no Shopping\';}" onfocus="if (this.value == \'Pesquisar no Shopping\') {this.value = \'\';}">
						    	</form>
							</div><!-- #search --></li></ul>';
			return $pesquisar;
		}
		
		#Função do Slider
		function Slider(){
			$slider = '
			<div class="fp-slider clearfix">    
				<div class="fp-slides-container clearfix">					        
					<div class="fp-slides" style="overflow: hidden;">	        
						<div class="fp-slides-items" style="position: absolute; top: 0px; left: 0px; display: block; z-index: 3; opacity: 1; width: 615px; height: 300px;">
							<!-- Início Slide -->
							<div class="fp-thumbnail"><a href="https://www.facebook.com/CorpoNota10?ref=hl" target="_blank" title=""><img src="http://www.corponota10.com.br/wp-content/themes/NewsLayer/images/face.jpg"></a></div>
								<div class="fp-content-wrap">
									<div class="fp-content">
										<h3 class="fp-title"><a href="https://www.facebook.com/CorpoNota10?ref=hl" title=""></a></h3>
										<p><a class="fp-more" href="https://www.facebook.com/CorpoNota10?ref=hl">More »</a></p>
									</div>
								</div>
					        </div>
							<!-- Fim Slide -->
							<!-- Início Slide 2 -->
					        <div class="fp-slides-items" style="position: absolute; top: 0px; left: -615px; display: none; z-index: 2; opacity: 1; width: 615px; height: 300px;">
					            <div class="fp-thumbnail"><a href="http://www.corponota10.com.br/?cat=1" title=""><img src="http://www.corponota10.com.br/wp-content/themes/NewsLayer/images/hiperj.jpg"></a></div>
									<div class="fp-content-wrap">
										<div class="fp-content">
					                        <h3 class="fp-title"><a href="http://www.corponota10.com.br/?cat=1" title=""></a></h3>
											<p><a class="fp-more" href="http://www.corponota10.com.br/?cat=1">More »</a></p>
										</div>
					                </div>
					            </div>          
					        </div>
							<!-- Fim Slide 2 -->
							<!-- Flechinhas -->
							<div class="fp-prev-next-wrap">
								<div class="fp-prev-next">
									<a href="#fp-next" class="fp-next"></a>
									<a href="#fp-prev" class="fp-prev"></a>
								</div>
							</div>
							<div class="fp-nav">
								<span class="fp-pager">&nbsp;</span>
							</div>
						</div>
					</div>
			';
			return $slider;
		}
	
		function Redireciona($link){
			echo "<script>
			window.open(".$link.");
		  </script>
		 ";
		}
		
		#Funcao que retorna o order
		function RetornaOrder($PaginaAux){
			$casa = array_search("order", $PaginaAux);
			if($casa){
				$order = $PaginaAux[($casa+1)];
			}else{
				$order = 'nome';
			}
			return $order;
		}
		
		#Função que retorna a página
		function RetornaPagina($PaginaAux){
			//Pega a casa que está a "pg"
			$casa = array_search("pg", $PaginaAux);
			if($casa){
				$pagina = $PaginaAux[($casa+1)];
			}else{
				$pagina = 1;
			}
			return $pagina;
		}
		
		#Função que retorna o total de registros do parâmetro passado
		function RetornaTotal($PaginaAux, $pagina){
			switch($pagina){
				case "pesquisar":
					$Sql = "SELECT P . *
							FROM s_produtos P
							INNER JOIN fixo_subcategorias S ON P.idsubcategoria = S.idsubcategoria
							INNER JOIN fixo_categorias C ON S.idcategoria = C.idcategoria
							WHERE (P.nome LIKE '%".$PaginaAux[0]."%' 
									OR C.nome LIKE '%".$PaginaAux[0]."%' 
									OR S.nome LIKE '%".$PaginaAux[0]."%'
								   )
							";
					$result = $this->Execute($Sql);
					$num_rows = $this->Linha($result);
					$total = ceil($num_rows/Limite);
					break;
				case "categoria":
					$SqlSub = "SELECT *
							FROM fixo_subcategorias
							WHERE nome = '".$PaginaAux[1]."'
							";
					$resultSub = $this->Execute($SqlSub);
					$num_rowsSub = $this->Linha($resultSub);
					if($num_rowsSub){
						$Sql = "SELECT P . *
								FROM s_produtos P
								INNER JOIN fixo_subcategorias S ON P.idsubcategoria = S.idsubcategoria
								WHERE S.nome = '".$PaginaAux[1]."'
								";
						$result = $this->Execute($Sql);
						$num_rows = $this->Linha($result);
						$total = ceil($num_rows/Limite);
					}else{
						$Sql = "SELECT P . *
								FROM s_produtos P
								INNER JOIN fixo_subcategorias S ON P.idsubcategoria = S.idsubcategoria
								INNER JOIN fixo_categorias C ON S.idcategoria = C.idcategoria
								WHERE C.nome = '".$PaginaAux[0]."'
								";
						$result = $this->Execute($Sql);
						$num_rows = $this->Linha($result);
						$total = ceil($num_rows/Limite);
					}
					break;
				case "loja":
					if ($this->BuscaCategoria($PaginaAux)){
						$cat = $PaginaAux[1];
					}
					
					if ($this->BuscaSubCategoria($PaginaAux)){
						$sub = $PaginaAux[2];
					}
					if($cat && !$sub){
						$where = "L.nome = '".$PaginaAux[0]."' AND C.nome = '".$cat."' ";
					}elseif($sub){
						$where = "L.nome = '".$PaginaAux[0]."' AND S.nome = '".$sub."' ";
					}else{
						$where = "L.nome = '".$PaginaAux[0]."'";
					}
					$Sql = "SELECT P . * , L.nome AS nome_loja, S.nome AS nome_sub, C.nome AS nome_cat
					FROM s_produtos P
					INNER JOIN s_lojas L ON P.idloja = L.idloja
					INNER JOIN fixo_subcategorias S ON P.idsubcategoria = S.idsubcategoria
					INNER JOIN fixo_categorias C ON S.idcategoria = C.idcategoria
					where ".$where."
					";
					$result = $this->Execute($Sql);
					$num_rows = $this->Linha($result);
					$total = ceil($num_rows/Limite);
					break;
			}
			return $total;
		}
		
		#Função que monta a Paginação da categoria
		function MontaPaginacao($numPagina, $PaginaAux, $pagina){
			#Pega o total de páginas
			$totalPag = $this->RetornaTotal($PaginaAux, $pagina);
			if($totalPag > 1){
				$Auxilio = $this->CarregaHtml('paginacao');
				switch($pagina){
					case "pesquisar":
						$url = "/" . $pagina . "/" . $PaginaAux[0];
						if($PaginaAux[1] == "order"){
							$url .= "/order/" . $PaginaAux[2];
						}
						break;
					case "categoria":
						$SqlSub = "SELECT *
								FROM fixo_subcategorias
								WHERE nome = '".$PaginaAux[1]."'
								";
						$resultSub = $this->Execute($SqlSub);
						$num_rowsSub = $this->Linha($resultSub);
						//Se for subcategoria
						if($num_rowsSub){
							$url = "/" . $pagina . "/" . $PaginaAux[0] . "/" . $PaginaAux[1];
							if($PaginaAux[2] == "order"){
								$url .= "/order/" . $PaginaAux[3];
							}
						}else{
							$url = "/" . $pagina . "/" . $PaginaAux[0];
							if($PaginaAux[1] == "order"){
								$url .= "/order/" . $PaginaAux[2];
							}
						}
						break;
					case "loja":
						$url .= "/" . $pagina . "/" . $PaginaAux[0];
						if($this->BuscaCategoria($PaginaAux)){
							$url .= "/".$PaginaAux[1];
						}
						if($this->BuscaSubCategoria($PaginaAux)){
							$url .= "/".$PaginaAux[2];
						}
						$casa = array_search("order", $PaginaAux);
						if($casa){
							$url .= "/order/" . $PaginaAux[($casa+1)];
						}
						break;
				}
				
				//Monta os links para o primeiro e anterior
				if($numPagina == 1){
					$anterior = "<li class='nolink'>< Anterior </li>";
					$primeira = "<li class='nolink'><< Primeira </li>";
				}else{
					$paginaAnterior = $numPagina - 1;
					$anterior = "<li><a href='".UrlPadrao.$url."/pg/".$paginaAnterior."'>< Anterior </a></li>";
					$primeira = "<li><a href='".UrlPadrao.$url."/pg/1'><< Primeira </a></li>";
				}
				
				//Monta paginação se estiver na última página
				if($numPagina == $totalPag){
					$paginacao .= "<li><a href='".UrlPadrao.$url."/pg/".($numPagina-2)."''>".($numPagina-2)."</a></li>";
					$paginacao .= "<li><a href='".UrlPadrao.$url."/pg/".($numPagina-1)."''>".($numPagina-1)."</a></li>";
					$paginacao .= "<li class='current'><a href='".UrlPadrao.$url."/pg/".$numPagina."''>".$numPagina."</a></li>";
				//Monta paginação se estiver na penúltima página
				}elseif($numPagina == ($totalPag-1)){
					$paginacao .= "<li><a href='".UrlPadrao.$url."/pg/".($numPagina-1)."''>".($numPagina-1)."</a></li>";
					$paginacao .= "<li class='current'><a href='".UrlPadrao.$url."/pg/".$numPagina."''>".$numPagina."</a></li>";
					$paginacao .= "<li><a href='".UrlPadrao.$url."/pg/".$totalPag."''>".$totalPag."</a></li>";
				}else{
					//Monta a paginação do meio
					$PagAte = $numPagina + QtdPaginas;
					for($i = $numPagina; $i < $PagAte ; $i++){
						if($i == $numPagina){
							$paginacao .= "<li class='current'><a href='".UrlPadrao.$url."/pg/".$i."''>".$i."</a></li>";
						}else{
							$paginacao .= "<li><a href='".UrlPadrao.$url."/pg/".$i."''>".$i."</a></li>";
						}
					}
				}
				
				//Monta os links para o último e próximo
				if($numPagina == $totalPag){
					$proxima = "<li class='nolink'>Próxima > </li>";
					$ultima = "<li class='nolink'>Última >> </li>";
				}else{
					$paginaProxima = $numPagina + 1;
					$proxima = "<li><a href='".UrlPadrao.$url."/pg/".$paginaProxima."'>Próxima > </a></li>";
					$ultima = "<li><a href='".UrlPadrao.$url."/pg/".$totalPag."'>Última >> </a></li>";
				}
				
				$Auxilio = str_replace('<%PRIMEIRA%>',$primeira,$Auxilio);
				$Auxilio = str_replace('<%ANTERIOR%>',$anterior,$Auxilio);
				$Auxilio = str_replace('<%PAGINACAO%>',$paginacao,$Auxilio);
				$Auxilio = str_replace('<%PROXIMA%>',$proxima,$Auxilio);
				$Auxilio = str_replace('<%ULTIMA%>',$ultima,$Auxilio);
				return $Auxilio;
			}else{
				return "";
			}
		}
		
		#Função que monta a ordenação (select)
		function MontaOrder(){
			if($this->Pagina == "produto"){
				return "";
			}else{
				$order = "
						<div class='produto-ordem'>
						Listar Por:
							<select name='ordenacao' onchange='ordena(this);'>
								<option selected value='-'>---</option>
								<option value='nome'>Nome</option>
								<option value='menorMaior'>Menor para Maior</option>
								<option value='maiorMenor'>Maior para Menor</option>
								<option value='clicados'>Mais Clicados</option>
							</select></div>
							";
				return $order;
			}
		}
		
	}
?>