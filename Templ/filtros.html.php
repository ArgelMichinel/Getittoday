    <form action="" method="post">
        
        <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
    
                    <table>
                        <tr>
                            <th style="width=auto"><input type="checkbox" class="Checkbox-filter" onclick="activ_filter()" name="new_query[incl_client]" value="true">Cliente: </th>
                            <th style="display:none"><select name="new_query[client]">
                                    <?php
                                        for ($i=0; $i < count($clients); $i++) {
                                            echo ('<option value="'. $clients[$i]['user_id'] . '">' . $clients[$i]['Nombre'] . '</option>' . PHP_EOL);
                                        }
                                    ?>
                                </select>
                            </th>
                        </tr>
                        <!*******************>
                        <tr>
                            <th style="width=auto;"><input type="checkbox" class="Checkbox-filter" onclick="activ_filter()" name="new_query[incl_cadete]" value="true">Cadete: </th>
                            <th style="display:none">
                                <input list="Cadete_names" name="dummy_cadete" onchange="cambio_cadete()" id="dummy_cadete">
                                <datalist id="Cadete_names">
                                    <option value="">(Sin Asignar)</option>
                                    <?php
                                        for ($i=0; $i < count($cadetes); $i++) {
                                            echo ('<option value="' . $cadetes[$i]['nombre'] . ' ' . $cadetes[$i]['apellido'] . ' - '. $cadetes[$i]['num_cadete'] . '">' . PHP_EOL);
                                        }
                                    ?>
                                </datalist>
                                <input type='text' style="display: none;" name="new_query[cadete]" id="cadete_definitivo">
                            </th>
                        </tr>
                        <!*******************>
                        <tr>
                            <th style="width=auto"><input type="checkbox" class="Checkbox-filter" onclick="activ_filter()" name="new_query[incl_zona]" value="true">Zona: </th>
                            <th style="display:none">
                                <select name="new_query[zona]">
                                    <option value='Zona 1'>CABA</option>
                                    <option value='Zona 2'>GBA1</option>
                                    <option value='Zona 3'>GBA2</option>
                                    <option value='Zona 4'>Sin coincidencia</option>
                                </select>
                            </th>
                        </tr>
                        <!*******************>
                        <tr>
                            <th style="width=auto;"><input type="checkbox" class="Checkbox-filter" name="new_query[incl_comercial]" value="true">Dom. Comercial </th>
                        </tr>
                        <!*******************>
                    </table>
                </div>
                    
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-7" style="padding-right: 0;">
                    
                   <table>
                        <tr>
                            <th style="width=auto;"><input type="checkbox" class="Checkbox-filter" onclick="activ_filter()" name="new_query[incl_date]" value="true">Fecha: </th>
                            <th style="display:none">
                                <label for="begin_date">Inicio =></label>
                                <input type="date" id="begin_date" name="new_query[begin_date]">
                                <label for="end_date">Final =></label>
                                <input type="date" id="end_date" name="new_query[end_date]">
                            </th>
                        </tr>
                        <!*******************>
                        <tr>
                            <th style="width=auto;"><input type="checkbox" class="Checkbox-filter" name="new_query[incl_hoy]" value="true">Dia de hoy </th>
                        </tr>
                        <!*******************>
                        <tr>
                            <th style="width=auto;"><input type="checkbox" class="Checkbox-filter" name="new_query[incl_ayer]" value="true">Día de ayer </th>
                        </tr>
                        <!*******************>
                    </table>
                    
                </div>
            
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2" style="padding-right: 0;">
                    
                   <button type="submit" class="btn btn2">Filtrar</button>
                    
                </div>
                
            </div>
        
    </form>