<?php include("template/cabecera.php");?>
<?php
include("administrador/config/bd.php");
$sentenciaSQL= $conexion->prepare("SELECT * FROM relojes");
$sentenciaSQL->execute();
$listaRelojes=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<?php foreach($listaRelojes as $reloj) { ?>

<div class="col-md-3">   
<div class="card">
<img class="card-img-top" src="<?php echo $reloj['imagen']; ?>" alt="">
<div class="card-body">
        <h4 class="card-title"><?php echo $reloj['nombre']; ?></h4>
        <p class="card-text">A tu estilo</p>
        <a name="" id="" class="btn btn-primary" href="" role="button"> Ver más</a>
</div>
</div>   
</div>

<?php } ?>


<?php include("template/pie.php");?> 