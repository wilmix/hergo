<select class="btn btn-primary" id="almacen_filtro" name="almacen_filtro">
    <?php if ($grupsOfUser == 'Nacional') : ?>
        <?php $almacenSeleccionado = false; ?>
        <?php foreach ($almacen->result_array() as $fila): ?>
            <?php if ($fila['idalmacen'] == $id_Almacen_actual && !$almacenSeleccionado): ?>
                <option value=<?= $fila['idalmacen'] ?> selected="selected"><?= $fila['almacen'] ?></option>
                <?php $almacenSeleccionado = true; ?>
            <?php else: ?>
                <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
            <?php endif; ?>
        <?php endforeach ?>
        <option value="" <?php if (!$almacenSeleccionado) echo 'selected="selected"'; ?>>TODOS</option>
    <?php else : ?>
        <option value=""></option>
        <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
    <?php endif; ?>
</select>