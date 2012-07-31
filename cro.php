<?php 
//Só para deixar a pagina com charset utf8
header("Content-Type: text/html; charset=utf-8");

//Recebendo o numero do Rastreio de Objeto
$numeroObjeto = 'SW377489455BR';

//Incluindo o phpQuery para manipular melhor o html
include('phpQuery-onefile.php');


//Função simples do cUrl
function simple_curl($url,$post=array(),$get=array()){
	$url = explode('?',$url,2);
	// return $url;
	if(count($url)===2){
		$temp_get = array();
		parse_str($url[1],$temp_get);
		$get = array_merge($get,$temp_get);
	}

	$ch = 
		curl_init($url[0]."?".http_build_query($get));
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	return curl_exec ($ch);
}

//Enviando a informação por cURL, usando a função simple_curl
$html = simple_curl('http://m.correios.com.br/movel/buscaObjetos.do',array(
	'numero'=>$numeroObjeto,
	'metodo'=>'buscar'
));


phpQuery::newDocumentHTML($html, $charset = 'utf-8');

//Informações que vão ser fixas no topo da página.
$informacaoTopo = array(
	'numero_do_objeto'=> trim(pq('.caixacampocinza .subsecao:eq(0)')->html()),
	'situacao_entrega' => trim(pq('.caixacentral .respostadestaque:eq(0)')->html()),
	'horario_entrega' => trim(pq('.caixacentral .orientacao:eq(0)')->html())
);

echo '<ul>';
	echo '<li> Numero do Objeto: '.$informacaoTopo['numero_do_objeto'].'</li>';
	echo '<li> Situação: '.$informacaoTopo['situacao_entrega'].'</li>';
	echo '<li> Horário de Entrega: '.$informacaoTopo['horario_entrega'].'</li>';
echo '</ul>';

//Com o phpQuery, vamos pegar todas as classes .caixacampo
$divPai = pq('.caixacampo');

//Separar todos os dados necessários, com ajuda do phpQuery
foreach ($divPai as $divFilho) {
	$dados = 
	array(

		'data_situacao' =>trim(pq('.caixacampo .resposta:contains("Data: ") + .respostadestaque:eq('.$x.')')->html()),
		'local_situacao' =>trim(pq('.caixacampo .resposta:contains("Local: ") + .respostadestaque:eq('.$x.')')->html()),
		'status_situacao' =>trim(pq('.caixacampo .resposta:contains("Situação: ") + .respostadestaque:eq('.$x.')')->html())

	);

	echo "<ul style='border:1px solid red'>";
		echo '<li> Situação: '.$dados['data_situacao'].'</li>';
		echo '<li> Data: '.$dados['local_situacao'].'</li>';
		echo '<li> Local: '.$dados['status_situacao'].'</li>';	
	echo "</ul>";

	$x = $x+1;
}

?>
