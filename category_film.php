<?php include "top.php"; ?>
    <!--
    <div class="alert alert-success">¡Ejemplo mensaje de éxito!</div>
    <div class="alert alert-error">¡Ejemplo mensaje de error!</div>
    -->
    <nav>
        <p><a href="film.php">Volver</a></p>
    </nav>
<?php
if ($error == null) {
  if (isset($_GET['film'])) {
?>
    <section id="films">
      <h2>Categorías de la pelicula: Nombre de la película</h2>
      <form action="category_film.php" action="post">
        <ul>
          <?php
          $stmt = $link->stmt_init();

          $stmt->prepare('SELECT film.film_id, category.name FROM film, film_category, category
          WHERE film.film_id = film_category.film_id AND category.category_id = film_category.category_id ORDER BY category.name ASC');
          
          $stmt->execute();

          $result = $stmt->get_result();
          if ($result->num_rows > 0) {                            
            while ($category = $result->fetch_array()) {
              if ($_GET['film'] == $category['film_id']) {
                $checked = 'checked';
              }
          ?>
            <li>
              <label>
                <input type="checkbox" name="" id="" <?=$checked?>>
                <?=$category['name']?>
              </label>
            </li>
          <?php
            }
            $result->free();
          }                                 
          $stmt->close();
          ?>
        </ul>
        <p>
          <input type="submit" value="Actualizar">
        </p>
      </form>
    <section>
<?php
  }
}
?>
<?php include "bottom.php"; ?>