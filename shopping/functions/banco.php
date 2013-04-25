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
					$Lojas .= $Linha;
				}
			}else{
			
			}
			return $Lojas;
		}
	}
?>