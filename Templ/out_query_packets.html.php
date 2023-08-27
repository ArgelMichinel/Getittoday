<div class="table_container" style="padding-bottom: 40px;">
    <div class="filter_container" id="contenedor_filtro">
        <?php
        include __DIR__ . '/../Templ/filtros.html.php';
        ?>
    </div>
    
    <?php
        include __DIR__ . '/../Templ/Select_columna.html.php';
    ?>
    
    <?php
        include __DIR__ . '/../Templ/tabla_query.html.php';
    ?>
    
</div>

<h2>Crear Lista</h2>

<div class="list_container">
    
    <div style="align-self: center;"><label for="selection_check">Seleccionar todos los registros:</label><input type="checkbox" class="Checkbox" onclick="select_all()" name="selection_check" id="selection_check" value="true"></div>
    
    <form action="" method="post">
        <label for="new_list[name]">Nombre de la lista:</label>
        <input type="text" id="name_list" name="new_list[name]" placeholder="Nombre de la lista" required>
        <input type="text" id="values_list" name="new_list[values]" style="display: none;">
        <button type="submit" id="submit_list" style="display: none;">Crear Lista</button>
    </form>
    <button class="btn" onclick="constr_list()">Crear Lista</button>
    
</div>
