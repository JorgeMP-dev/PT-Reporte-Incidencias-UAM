<?php
session_start();
require '../../../lib/var/varlib.php';
require '../../../'.$_RUTADB;

if(!isset($_SESSION['usuario'])){
    header("Location: ../../../login.php");
    exit;
}

if(!isset($_SESSION['permisos']['Generador CodigosQR'])){
    header("Location: ../../../login.php");
    exit;
}

$idAula = $_GET['aula'];

$sql = "SELECT nombre FROM aula WHERE idaula = ?";
$stmtAula = $conn->prepare($sql);
$stmtAula->bind_param("i", $idAula);
$stmtAula->execute();
$resultAula = $stmtAula->get_result();
$aulaData = $resultAula->fetch_assoc();

$nombreAula = $aulaData['nombre'] ?? 'Sala';

$sql = "SELECT idequipo, nombre, codigoInventario
        FROM equipo
        WHERE idaula = ?
        ORDER BY nombre";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idAula);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php echo $_ENCABEZADOSHTML ;?>
<?php echo $_DEPENDENCIASLIB ;?>
<style>
body{
    font-family: Arial;
}
.contenedor{
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
.qr-card{
    border: 1px solid #000;
    padding: 10px;
    text-align: center;
    page-break-inside: avoid;
}
.qr-card img{
    width: 140px;
}
@media print{
    button{
        display:none;
    }
}
</style>
</head>
<body>    
    </br>
    <div class="contenedor">
        <h2>Códigos QR - <?= htmlspecialchars($nombreAula) ?></h2>
        <button onclick="window.print()">
            <i class="bi bi-printer"></i>Imprimir
        </button>
    </div>
    </br>
    <div class="contenedor">
        <?php while($row = $result->fetch_assoc()) { 
        $rutaQR = "https://192.168.1.108/ReporteIncidenciasUAM/apps/reporteIncidencias/index.php?equipo=".$row['idequipo'];
        ?>
            <div class="qr-card">
                <strong><?= htmlspecialchars($row['nombre']) ?></strong><br><br>
                <img src="generarQR.php?data=<?= $rutaQR ?>">
                <br>
                <small><?= htmlspecialchars($row['codigoInventario']) ?></small>
            </div>
        <?php } ?>
    </div>
</body>
</html>