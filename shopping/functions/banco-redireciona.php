<?php
	class bancoredireciona extends banco{
		function BuscaLink($idproduto){
			$Sql = "SELECT link from s_produtos
					WHERE idproduto = '".$idproduto."'
					";
			$result = parent::Execute($Sql);
			$rs = mysql_fetch_array($result, MYSQL_ASSOC);
			$link = $rs['link'];
			return $link;
		}
	}
?>