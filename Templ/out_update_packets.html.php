<div class="table_container" style="padding-bottom: 40px;">
    <div class="filter_container" id="contenedor_filtro" style="margin-bottom: 20px;">
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

<h2>Actualizar datos de MercadoLibre</h2>

<div class="filter_container" id="contenedor_update1">
    
    <div style="align-self: center;"><label for="selection_check">Seleccionar todos los registros:</label><input type="checkbox" class="Checkbox" onclick="select_all()" name="selection_check" id="selection_check" value="true"></div>
    
    <button class="btn" onclick="constr_list_update()">Actualizar envíos Seleccionados</button>
    
</div>

<h2>Actualizar datos internos</h2>

<div class="filter_container" id="contenedor_update2">
    
    <form action="" method="post">
        <label for="new_list[status_logistica]">Estatus logística:</label>
        <select name="new_list[status_logistica]">
            <option value=0>Ingresado</option>
            <option value=1>Entregado</option>
            <option value=2>1era visita</option>
            <option value=3>2da visita</option>
            <option value=4>Devuelto logís.</option>
            <option value=5>Devuelto MELI</option>
            <option value=6>Entr. y Cobra.</option>
        </select>
        
        <input type="text" name="new_list[comment_logis]" placeholder="Puede ingresar comentario">
        <input type="text" id="values_list" name="values" style="display: none;">
        <button type="submit" id="submit_list" style="display: none;">Crear Lista</button>
    </form>
    <button class="btn" onclick="constr_list_logis()">Actualizar envíos Seleccionados</button>
    
</div>