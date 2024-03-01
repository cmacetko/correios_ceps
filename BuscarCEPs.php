<?
######################################################
######################################################

function GerarAccessToken()
{

    $ch 						    = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.correios.com.br/token/v1/autentica/cartaopostagem");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array("numero" => "[CartaoDePostagem]")));
    curl_setopt($ch, CURLOPT_USERPWD, "[Usuario]:[Senha]");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "accept: application/json",
    "content-type: application/json"
    ));
    $response 					    = curl_exec($ch);		
    $response2                      = json_decode($response, true);
    $httpcode	                    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err 		                    = curl_error($ch);
    curl_close($ch);

    if( $httpcode == 201 )
    {

        return $response2["token"];

    }else{

        echo "ERRO";
        echo "<br>";
        echo "httpcode: " . $httpcode;
        echo "<br>";
        echo "response: " . $response;
        echo "<br>";
        echo "err: " . $err;
        exit();

    }

}

function BuscarCEPs($QtdPorPagina, $PaginaAtual)
{

    $ch 						    = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.correios.com.br/cep/v2/enderecos?size=" . $QtdPorPagina . "&page=" . $PaginaAtual . "&sort=cep,asc");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "accept: application/json",
    "content-type: application/json",
    "Authorization: Bearer " . GerarAccessToken()
    ));
    $response 					    = curl_exec($ch);		
    $response2                      = json_decode($response, true);
    $httpcode	                    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err 		                    = curl_error($ch);
    curl_close($ch);

    if( $httpcode == 200 )
    {

        return $response2;

    }else{

        echo "ERRO";
        echo "<br>";
        echo "httpcode: " . $httpcode;
        echo "<br>";
        echo "response: " . $response;
        echo "<br>";
        echo "err: " . $err;
        exit();

    }

}

######################################################
######################################################

$QtdPorPagina					= 100;
$PaginaAtual                    = intval($_REQUEST["PaginaAtual"]);

# --------------------------

echo "QtdPorPagina: " . $QtdPorPagina;
echo "<br>";
echo "PaginaAtual: " . $PaginaAtual;

# --------------------------

echo "<br>";
echo "<br>";

# --------------------------

$RetEnv                         = BuscarCEPs($QtdPorPagina, $PaginaAtual);

# --------------------------

echo "QtdRegistros: " . $RetEnv["page"]["totalElements"];
echo "<br>";
echo "QtdPaginas: " . $RetEnv["page"]["totalPages"];

# --------------------------

echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

######################################################
######################################################

foreach( $RetEnv["itens"] as $ObjItem )
{

    ######################################################
    ######################################################

    /*
    Aqui você pode tratar cada cep, e adicionar em uma base de dados
    */
    
    var_dump($ObjItem);
    echo "<br>";
    echo "<br>";

    ######################################################
    ######################################################

}

######################################################
######################################################

if( count($RetEnv["itens"]) > 0 )
{

	?>
	<script language="javascript">
	window.location = '<?=$_SERVER["PHP_SELF"] . "?PaginaAtual=" . ( $PaginaAtual  + 1 )?>';
	</script>
	<?

}else{

    var_dump($RetEnv);
    
}

######################################################
######################################################
?>