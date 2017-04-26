<div class="col-sm-2 right_cab">
    <div class="levels">Уровни</div>
    <table class="table">
        <tbody class="new_style">
        <?php $max = 2; ?>
            <?php for ($level = 1; $level < 22; $level++) { ?>
                <tr>
                    <td><?= $level ?> - </td>
                    <td><?= $max ?> - </td>
                    <td><?= $counts[$level] ?: 0 ?></td>
                    <?php $max *= 2 ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
