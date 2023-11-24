<?php include "top.php"; ?>
<section id="create">
    <h2>Nueva categoría</h2>
    <nav>
        <p><a href="film.php">Volver</a></p>
    </nav>
<?php
if (!empty($_GET)) {
    if ($error == null) {
        $name = $_GET['name'];
        $stmt = $link->stmt_init();
        
        $stmt->prepare("INSERT INTO category (category_id, name, last_update) VALUES (NULL, ?, CURRENT_TIMESTAMP)");

        $name = empty($name) ? "" : $name;

        if (empty($name)) $error = "¡Nombre de categoria no válido!";

        if (empty($error)) {
            $stmt->bind_param("s", $name);
            if ($stmt->execute()) {
                $created = true;
            } else {
                $error = "¡La categoria no pudo ser creada!";
            }
        }
        $stmt->close();
    }
    if (!empty($error)) {
        echo "<div class=\"alert alert-error\">$error</div>";
    } else if (isset($created) && $created) {
        echo "<div class=\"alert alert-success\">¡Categoria creada satisfactoriamente!</div>";
    }
} else {
?>
    <form action="" autocomplete="off">
        <fieldset>
            <legend>Datos de la categoría</legend>
            <label for="name">Nombre</label>
            <input type="text" name="name" id="name" required>
            <p></p>
            <input type="reset" value="Limpiar">            
            <input type="submit" value="Crear">
        </fieldset>
    </form>
<?php
}
?>
</section>
<?php include "bottom.php"; ?>