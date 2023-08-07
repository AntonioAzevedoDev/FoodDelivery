<?php echo $this->extend('Admin/layout/principal'); ?>

<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>

<?php echo $this->section('estilos'); ?>

<!-- Aqui enviamos para o template principal os estilos -->


<style>
    
    @media only screen and (max-width: 9000px){
        
        .dia_descricao{
            
            min-width: 169% !important;
            
        }
    }
    
</style>


<?php echo $this->endSection(); ?>




<?php echo $this->section('conteudo'); ?>

<!-- Aqui enviamos para o template principal os conteúdos -->
<div class="row">

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?php echo $titulo; ?></h4>

                <?php if (session()->has('errors_model')): ?>
                    <ul>
                        <?php foreach (session('errors_model') as $error): ?>
                            <li class="text-danger"><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php echo form_open("admin/expedientes/expedientes", ['class' => 'form-row']); ?>
                <div class="table-responsive">
                    <table class="table table-hover dia_descricao ">
                        <thead>
                            <tr>
                                <th>Dia</th>
                                <th>Abertura</th>
                                <th>Fechamento</th>
                                <th>Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($expedientes as $dia): ?>
                                <tr >

                                    <td class="form-group col-md-3">
                                        <input type="text" name="dia_descricao[]" class="form-control" value="<?php echo esc($dia->dia_descricao); ?>" readonly=""></input>
                                    </td>
                                    <td class="form-group col-md-3">
                                        <input type="time" name="abertura[]" class="form-control" value="<?php echo esc($dia->abertura); ?>" required=""></input>
                                    </td>
                                    <td class="form-group col-md-3">
                                        <input type="time" name="fechamento[]" class="form-control" value="<?php echo esc($dia->fechamento); ?>" required=""></input>
                                    </td>
                                    <td class="form-group col-md-3">
                                        <select class="form-control" name="situacao[]" required="">

                                            <option value="1" <?php echo ($dia->situacao == true ? 'selected' : '');?>> Aberto </option>
                                            <option value="0" <?php echo ($dia->situacao == false ? 'selected' : '');?>> Fechado </option>

                                        </select>
                                    </td>



                                </tr>
                            <?php endforeach; ?>




                        </tbody>

                    </table>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary mr-2 btn-sm">
                            <i class="mdi mdi-checkbox-marked-circle btn-icon-prepend"></i>
                            Salvar
                        </button>
                    </div>
                </div>
                <?php form_close() ?>
            </div>
        </div>
    </div>

</div>

<?php echo $this->endSection(); ?>




<?php echo $this->section('scripts'); ?>

<!-- Aqui enviamos para o template principal os scripts -->
<script src="<?php echo site_url('admin/vendors/auto-complete/jquery-ui.js'); ?>"></script>
<script>
    $(function () {

        $("#query").autocomplete({

            source: function (request, response) {
                $.ajax({

                    url: "<?php echo site_url('admin/bairros/procurar'); ?>",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function (data) {
                        if (data.length < 1) {
                            var data = [
                                {
                                    label: 'Bairro de Cascavel não encontrado',
                                    value: -1
                                }
                            ];
                        }
                        response(data); //Aqui temos valor no data
                    },

                }); // fim ajax
            },
            minLength: 1,
            select: function (event, ui) {
                if (ui.item.value == -1) {
                    $(this).val("");
                    return false;
                } else {

                    window.location.href = '<?php echo site_url('admin/bairros/show/'); ?>' + ui.item.id;

                }
            }
        }); //fim autocomplete

    });
</script>

<?php echo $this->endSection(); ?>