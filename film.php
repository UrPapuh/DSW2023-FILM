<?php include "top.php"; ?>
<?php
if ($error == null) {
    if ((isset($_GET['category']) && isset($_GET['action'])) && $_GET['action'] == 'delete') {
        $stmt = $link->stmt_init();
        $stmt->prepare('DELETE FROM category WHERE category_id = ?');
        $stmt->bind_param("i", $_GET['category']);
        try {
            if ($stmt->execute() === true) {
                $delete = true;
            }
        } catch (\Throwable $error) {
            $error = "¡No fue posible eliminar la categoria!";
        }
        $stmt->close();
    }
?>
    <section id="films">
        <h2>Peliculas</h2>
        <form action="film.php" method="get">
          <fieldset>
            <legend>Categorías</legend>
            <select name="category" id="">
                <option selected disabled>Elige una categoría</option>
                <?php
                $stmt = $link->stmt_init();

                $stmt->prepare('SELECT * FROM category ORDER BY name ASC');
                $stmt->execute();

                $result = $stmt->get_result();
                                                            
                if ($result->num_rows > 0) {                            
                    while ($category = $result->fetch_array()) {
                ?>
                        <option value="<?=$category['category_id']?>"><?=$category['name']?></option>
                <?php
                    }
                    $result->free();
                } else {
                    $error = '¡No hay categorias!';
                }                                          
                $stmt->close();
                ?>      
            </select>
            <input type="submit" name="action" value="search">
            <input type="submit" name="action" value="delete">
          </fieldset>
        </form>
        <nav>
            <fieldset>
                <legend>Acciones</legend>           
                <a href="create.php">
                    <button>Crear Categoria</button>
                </a>                    
            </fieldset>
        </nav>
        <?php
        if (isset($_GET['category'])) {  
            if (isset($_GET['action']) && $_GET['action'] == 'search') {
                $category_id = $_GET['category'];
                $stmt = $link->stmt_init();

                $stmt->prepare('SELECT film.film_id, film.title, film.release_year, film.length FROM film, film_category
                WHERE film.film_id = film_category.film_id AND film_category.category_id = ? ORDER BY film.title ASC');
                
                $stmt->bind_param("i", $category_id);

                $stmt->execute();

                $result = $stmt->get_result();

                if ($result->num_rows > 0) { 
            ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Año</th>
                                <th>Duración</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php            
                            while ($film = $result->fetch_array()) {
                            ?>
                                <tr>
                                    <td><?=$film['title']?></td>
                                    <td class="center"><?=$film['release_year']?></td>
                                    <td class="center"><?=$film['length']?></td>
                                    <td class="actions">                            
                                        <a class="button" href="category_film.php?film=<?=$film['film_id']?>">
                                            <button>Cambiar categorías</button>
                                        </a>               
                                    </td>
                                </tr>
                            <?php
                            }
                            $result->free();
                            ?>
                        </tbody>
                    </table>
        <?php
                } else {
                    $error = 'No hay peliculas para esta categoria';
                }                                          
                $stmt->close();
            }
        } else {
            $error = 'Seleccione una categoria';
        }
        ?>
    </section>
<?php
    if (isset($_GET['action']) && isset($error)) {
        echo "<div class=\"alert alert-error\">$error</div>";
    }
    if (isset($delete) && $delete) {
        echo "<div class=\"alert alert-success\">¡Categoria borrada satisfactoriamente!</div>";
    }
} else {
    echo "<div class=\"alert alert-error\">$error</div>";
}
?>
<?php include "bottom.php"; ?>