<?php
if (isset($_POST['id'])) {
    $actividadId = $_POST['id'];

    include 'db_conexion.php'; // Incluye tu archivo de conexión a la base de datos

    // Consulta para obtener los detalles de la actividad
    $query = "SELECT * FROM articulos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $actividadId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <h2><?php echo htmlspecialchars($row['titulo']); ?></h2>
        <p><?php echo htmlspecialchars($row['contenido']); ?></p>
        <img src="php/uploads/<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['titulo']); ?>" class="img-fluid" />
        <?php
    } else {
        echo "No se encontró la actividad.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID de actividad no proporcionado.";
}
?>
