<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Insert CPF</title>
</head>
<body>
    <?php 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "cpfdata";
    $oricpf = "";

    //conexao
    $conn = mysqli_connect($servername, $username, $password, $database);

    if(!$conn){
        die("A conexão não foi estabelecida" . mysqli_connect_error());

    }
    //funcao para fazer os procedimentos de verificacao
    function cpfverification($stringcpf){
        $aux = 0;
        //loop para percorrer todas as posicoes (a string ja deve estar cortada)
        for($i = 0; $i < strlen($stringcpf); $i++){
            $test = $i + 2;  //a variavel test funciona como um iterador para fazer a multiplicacao iniciando do numero 2, ate o final da string
            $aux += (string) ((int)$stringcpf[$i]*$test);//a variavel aux  faz o somatorio da multiplacacao de cada posicao da string 
        }
        return $aux;
    }

    //Formando o cpf
    function concString($str1, $str2){
        return ($str1.$str2);
    }

    //Testando se o cpf é válido
    if (isset($_POST["cpf"])) {
        $cpf = $_POST["cpf"]; 
        
        if(!empty(trim($cpf))){
            if(strlen($cpf) === 11){

                //logica do primeiro digito verificador
                $verify1 = 0;
                $first9 = strrev(substr($cpf, 0,9));
                $num1 = cpfverification($first9);
                
                if($num1 % 11 < 2 ){
                    $verify1 = 0;
                }
                else if ($num1 % 11 >= 2){
                    $verify1 = 11 - ($num1 % 11);
                }

                echo "<p style='color: white;'>Numero verificador 1: $verify1</p>";


                //logica do segundo digito verificador
                $first10 = $verify1.$first9;
                $verify2 = 0;
                $num2 = cpfverification($first10);
            
               if($num2 % 11 < 2){
                $verify2 = 0;
                }
                else if ($num2 % 11 >= 2){
                    $verify2 = 11 - ($num2 % 11);
                }

                echo "<p style='color: white;'>Numero verificador 2: $verify2</p>";

                //concatenando o primeiro e segundo digito verificadores
                $oricpf = concString(substr($cpf, 0,9),(string) $verify1.$verify2);

                echo "<p style='color: white;'>CPF Validado! : $oricpf</p>";

                //se os cpfs forem iguais, pode ser inserido
                if($cpf === $oricpf){
                    $sql = "INSERT INTO users (cpf) VALUES ('$oricpf')"; // criando a query
                    //testando se a query foi executada com sucesso
                    if (mysqli_query($conn, $sql)) {
                        echo "<script>alert('Novo registro criado com sucesso!!!');</script>";
                    }
                    echo "<p style='color: white;'>CPF VÁLIDO.</p>"; 
                }else {
                    echo "<script>alert('CPF INVALIDO!!!');</script>";
                }
                
            }else{
                echo "<p style='color: white;'>Por favor, insira um CPF Valido.</p>";
            }
       
        }else {
         echo "<p style='color: white;'>Falha ao inserir o CPF com o Banco de Dados!.</p>";
        }
    }

    ?>
</body>
</html>