<?php include("../template/cabecera.php"); ?>
<?php

include("../config/bd.php");

$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$textNombre=(isset($_POST['textNombre']))?$_POST['textNombre']:"";
$txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";
echo "<h1>$textNombre</h1>";
switch($accion){
        case "Agregar":
            $sentenciaSQL= $conexion->prepare("INSERT INTO relojes (nombre,imagen) VALUES (:nombre,:imagen);");
            $sentenciaSQL->bindParam(':nombre',$textNombre);

            $fecha= new DateTime();
            $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES['txtImagen']['name']:"imagen.jpg";

            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            if($tmpImagen!=""){ 

                 move_uploaded_file($tmpImagen,"../../img/" . $nombreArchivo);
            }

            $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
            $sentenciaSQL->execute();

            header('Location:productos.php');
            break;
 
        case "Modificar":

            $sentenciaSQL= $conexion->prepare("UPDATE relojes SET nombre=:nombre WHERE id=:id");
            $sentenciaSQL->bindParam(':nombre',$textNombre);
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();

            if($txtImagen!=""){
                $fecha= new DateTime();
                $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES['txtImagen']['name']:"imagen.jpg";
                $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

                move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);

                $sentenciaSQL= $conexion->prepare("SELECT imagen FROM relojes WHERE id=:id");
                $sentenciaSQL->bindParam(':id',$txtID);
                $sentenciaSQL->execute();
                $reloj=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

                if(isset($reloj["imagen"])&&($reloj["imagen"]!="imagen.jpg") ){

                    if(file_exists("../../img/".$reloj["imagen"])){

                       unlink("../../img/".$reloj["imagen"]);

                    }
                }


 

                $sentenciaSQL= $conexion->prepare("UPDATE relojes SET imagen=:imagen WHERE id=:id");
                $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
                $sentenciaSQL->bindParam(':id',$txtID);
                $sentenciaSQL->execute();
            }
            header('Location:productos.php');
            break;  
        
        case "Cancelar":
            header('Location:productos.php');
            break;  


        case "Seleccionar":

            $sentenciaSQL= $conexion->prepare("SELECT * FROM relojes WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $reloj=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

            $textNombre=$reloj['nombre'];
            $txtImagen=$reloj['imagen'];

            break;  


        case "Borrar":

            $sentenciaSQL= $conexion->prepare("SELECT imagen FROM relojes WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $reloj=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if(isset($reloj["imagen"])&&($reloj["imagen"]!="imagen.jpg") ){

               if(file_exists("../../img/".$reloj["imagen"])){
                  unlink("../../img/".$reloj["imagen"]);
                }
            }






            $sentenciaSQL= $conexion->prepare("DELETE FROM relojes WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            header('Location:productos.php');

            break;  


}

$sentenciaSQL= $conexion->prepare("SELECT * FROM relojes");
$sentenciaSQL->execute();
$listaRelojes=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);


?>

<div class="col-md-5">


    <div class="card">
        <div class="card-header">
            Datos del Reloj
        </div>

        <div class="card-body">

        <form method="POST" enctype="multipart/form-data">

    <div class = "form-group">
    <label for="txtID">ID:</label>
    <input type="text" required readonly class="form-control" value="<?php echo $txtID; ?>" name="textID" id="txtID"  placeholder="ID">
    </div>

    <div class = "form-group">
    <label for="textNombre">Nombre:</label>
    <input type="text" required class="form-control" value="<?php echo $textNombre; ?>" name="textNombre" id="textNombre"  placeholder="Nombre">
    </div>

    <div class = "form-group">
    <label for="textNombre">Imagen:</label>

    <br/>

    <?php if($txtImagen!=""){?>

        <img class="img-thumbnail rounded" src="../../img/<?php echo $txtImagen;?>" width="50" srcset="">

      <?php    }?>

    <input type="file" class="form-control" name="txtImagen" id="txtImagen"  placeholder="Nombre del reloj">
    </div>

        <div class="btn-group" role="group" aria-label="">
            <button type="submit" name="accion" <?php echo ($accion=="Seleccionar")?"disabled":"";?> value="Agregar" class="btn btn-success">Agregar</button>
            <button type="submit" name="accion" <?php echo ($accion!=="Seleccionar")?"disabled":"";?> value="Modificar" class="btn btn-warning">Modificar</button>
            <button type="submit" name="accion" <?php echo ($accion!=="Seleccionar")?"disabled":"";?> value="Cancelar" class="btn btn-info">Cancelar</button>
        </div>

    
    </form>
  
        </div>
   
    </div>

    
    

</div>
<div class="col-md-7">

   <table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($listaRelojes as $reloj) {?>
        <tr>
            <td><?php echo $reloj['id'];?></td>
            <td><?php echo $reloj['nombre'];?></td>
            <td>

            <img class="img-thumbnail rounded" src="../../img/<?php echo $reloj['imagen'];?>" width="50" srcset="">

            </td>

            <td>

            <form method="post">

                <input type="hidden" name="txtID" id="txtID" value="<?php echo $reloj['id'];?>" />

                <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>

                <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>
            </form>

            </td>

        </tr>
        <?php } ?>
    </tbody>
   </table>

</div>    

<?php include("../template/pie.php"); ?>
