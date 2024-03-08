<select class="btn btn-primary" id="sucursal_filtro" name="sucursal_filtro">
    <?php if ($grupsOfUser == 'Nacional') : ?>
        <?php $sucursalSeleccionado = false; ?>
        <?php foreach ($sucursales as $fila): ?>
            <?php if ($fila['codigoSucursal'] == $sucursal_usuario && !$sucursalSeleccionado): ?>
                <option value=<?= $fila['codigoSucursal'] ?> selected="selected"><?= $fila['nombreSucursal'] ?></option>
                <?php $almacenSeleccionado = true; ?>
            <?php else: ?>
                <option value=<?= $fila['codigoSucursal'] ?> ><?= $fila['nombreSucursal'] ?></option>
            <?php endif; ?>
        <?php endforeach ?>
        <option value="" <?php if (!$almacenSeleccionado) echo 'selected="selected"'; ?>>TODAS SUCURSALES</option>
    <?php else : ?>
        <option value=""></option>
        <option value=<?= $sucursal_usuario ?> selected="selected"><?= $almacen_actual ?></option>
    <?php endif; ?>
</select>