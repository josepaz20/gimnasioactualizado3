<!DOCTYPE html>

<html>
    <head>
        <meta charset ="UTF-8">
        <title>Recordar Contraseña</title>
    </head>
    <body>
        <form action="inicio.php" method="POST">
            
            <input type="text" name="email" value="" placeholder="email" /> <br>
            <input type="submit" value="Recordar Contraseña" />
        </form>
        
        <?php
        
        try{
            if(isset($_POST [ 'email']) && !empty($_POST [ 'email']))
            {
                $pass =substr ( (microtime()),1,10);
                $email = $_POST [ 'email'];
                
                //Conexión con la base de datos
                
                $conn = new mysqli("127.0.0.1","root","","gimnasiogymjam_bd");
                
                //chech conection
                
                if($conn->connect_error)
                {
                    
                    die("Conexión Fallida: " .$conn->connect_error);
                
                }
                    
            $sql ="Update usuario Set password='$pass' where email='$email'";
            
            if($conn-> query($sql)==TRUE){
                echo "Contraseña Modificada Correctamente:";
            }else
            {
                echo" Error Modificando:" .$conn->error;
            }
            
            $to =$_POST [ 'email'];
            $from ="From:" ."MasterHouse";
            $subject ="Recordar Contraseña";
            $message ="El sistema le asigno la siguiente clave" .$pass;
            
            mail($to,$subject,$message,$from);
            echo 'Contraseña enviada satisfactoriamente a ' .$_POST [ 'email'];
        }
        else
            echo'Información Incompleta';
        }
        catch (Exception $e) {
            
            echo 'Excepción Capturada:',$e->getMessage(),"\n";

        }
        ?>
    </body>
</html>