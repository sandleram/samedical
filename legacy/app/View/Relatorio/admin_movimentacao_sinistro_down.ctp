<table>
        <tr>
            <th style="vertical-align: middle;">ID Aluno</th>
            <th style="vertical-align: middle;">Nome</th>
            <th style="vertical-align: middle;">CPF</th>
            <th style="vertical-align: middle;">E-mail</th>
            <th style="vertical-align: middle;">Faculdade</th>
            <th style="vertical-align: middle;">Data de Cadastro</th>
        </tr>  

            <?php
            foreach ($rows as $row):
        ?>
            <tr>
                <td><?php echo $row['Aluno']['id']; ?></td>
                <td><?php echo utf8_decode($row['Usuario']['nome']);?></td>
                <td><?php echo $row['Usuario']['cpf'];?></td>
                <td><?php echo utf8_decode(strtolower($row['Usuario']['email']));?></td>
                <td><?php echo $row['Empresa']['nome'];?></td>
                <td ><?php echo utf8_decode($this->DateTime->dbToView($row['Usuario']['data_cadastro'])); ?></td>
            </tr>
        <?php endforeach; ?>
</table>
