<select   class="btn btn-primary" id="almacen_filtro" name="almacen_filtro">
    <?php if ($grupsOfUser == 'Nacional') : ?>
        <?php foreach ($almacen->result_array() as $fila): ?>
            <option value=<?= $fila['idalmacen'] ?> ><?= $fila['almacen'] ?></option>
        <?php endforeach ?>
            <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
            <option value="">TODOS</option>
    <?php else : ?>
        <option value=<?= $id_Almacen_actual ?> selected="selected"><?= $almacen_actual ?></option>
    <?php endif; ?>
</select>