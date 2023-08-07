<?php echo $this->extend('layout/principal_web'); ?>

<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>


<?php echo $this->section('estilos'); ?>

<!-- Aqui enviamos para o template principal os estilos -->

<link rel="stylesheet" href="<?php echo site_url("web/src/assets/css/produto.css") ?>"></link>

<?php echo $this->endSection(); ?>




<?php echo $this->section('conteudo'); ?>

<div class="container section" id="menu" data-aos="fade-up" style="margin-top: 3em">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <!-- product -->
        <div class="product-content product-wrap clearfix product-deatil">
            <div class="row">
                <div class="col-md-12 col-xs-12">

                    <h2 class="section-title pull-left"><?php echo esc($titulo) ?></h2>

                </div>

                <div class="col-md-6">

                    <ul class="list-group">
                        <?php $total = 0; ?>
                        <?php foreach ($carrinho as $produto): ?>
                            <?php $subtotal = $produto['preco'] * $produto['quantidade'] ?>
                            <?php $total += $subtotal; ?>    

                            <li class="list-group-item">
                                <div>
                                    <h4><?php echo ellipsize($produto['nome'], 60) ?></h4>
                                    <p class="text-muted">Quantidade: <?php echo $produto['quantidade'] ?></p>
                                    <p class="text-muted">Preço: R$ <?php echo $produto['preco'] ?></p>
                                    <p class="text-muted">Subtotal: R$ <?php echo number_format($subtotal, 2) ?></p>
                                </div>
                            </li>

                        <?php endforeach; ?>


                        <li class="list-group-item">
                            <span>Total de produtos:</span>
                            <strong><?php echo 'R$&nbsp' . number_format($total, 2) ?></strong>
                        </li>

                        <li class="list-group-item">
                            <span>Taxa de entrega:</span>
                            <strong id="valor_entrega" class="text-danger">Obrigatório *</strong>
                        </li>

                        <li class="list-group-item">
                            <span>Valor do pedido:</span>
                            <strong id="total" ><?php echo 'R$&nbsp' . number_format($total, 2) ?></strong>
                        </li>

                        <li class="list-group-item">
                            <span>Endereço de entrega:</span>
                            <strong id="endereco" class="text-danger">Obrigatório *</strong>
                        </li>
                    </ul>
                    <a href="<?php echo site_url("/") ?>" class="btn btn-food">Acho que quero mais delícias</a>
                    <a href="<?php echo site_url("carrinho") ?>" class="btn btn-primary" style=" font-family: 'Montserrat-Bold'">Ver o que tenho no carrinho</a>


                </div>

                <div class="col-md-6">

                    <?php echo form_open('checkout/processar', ['id' => 'form-checkout']); ?>
                    <?php if (session()->has('errors_model')): ?>
                        <ul style="list-style:decimal;margin-left: -2em">
                            <?php foreach (session('errors_model') as $error): ?>
                                <li class="text-danger"><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <p style="font-size: 18px; font-weight: bold">Escolha a forma de pagamento na entrega</p>

                    <div class="form-row">
                        <?php foreach ($formas as $forma): ?>
                            <div class="radio">
                                <label style="font-size: 16px">
                                    <input type="radio" name="forma" data-forma="<?php echo $forma->id ?>" style="margin-top: 2px" class="forma" id="forma">
                                    <?php echo esc($forma->nome) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        <div id="pix_data" class="hidden" >
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <div>
                                        <strong>Dados do PIX</strong>
                                        <p style="margin-top: 0.5em">CPF: <strong>760.510.723.49</strong></p>
                                        <p>Nome: <strong>Maria Auzilene Dantas da Silva</strong></p>
                                    </div>
                                </li>
                            </ul>

                        </div>
                        <hr>

                        <div id="troco" class="hidden">

                            <div class="form-group col-md-12" style="padding-left: 0">
                                <label>Troco para</label>
                                <input type="text" id="troco_para" name="checkout[troco_para]" class="form-control money" placeholder="Troco para">

                                <label>
                                    <input type="checkbox" id="sem_troco" name="checkout[sem_troco]">
                                    Não preciso de troco
                                </label>
                                <hr>
                            </div>

                        </div>

                        <div class="form-group col-md-12" style="padding-left: 0">
                            <label>Consulte a taxa de entrega</label>
                            <input type="text" name="cep" class="form-control cep" placeholder="Informe seu CEP" value="">
                            <div id="cep"></div>

                        </div> 
                        <div id="bairros_form" name="bairros_form" style="display: none" class="form-row" style="margin-top: 2em">

                            <label>Informe o seu Bairro</label>
                            <select class="form-control js-example-basic-single" name="bairro">
                                <option value="">
                                    Escolha...
                                </option>
                                <?php foreach ($bairros as $bairro): ?>
                                    <option value="<?php echo $bairro->id ?>"> <?php echo esc($bairro->nome) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div id="bairro"></div>

                        </div>
                        <div class="form-group col-md-9" style="padding-left: 0">
                            <label>Rua *</label>
                            <input id="rua" type="text" name="checkout[rua]" class="form-control"  value="" required="" readonly="">
                        </div> 
                        <div class="form-group col-md-3" style="padding-left: 0">
                            <label>Numero *</label>
                            <input type="text" name="checkout[numero]" class="form-control" value="" required="">
                        </div> 
                        <div class="form-group col-md-12" style="padding-left: 0">
                            <label>Ponto de referência *</label>
                            <input type="text" name="checkout[referencia]" class="form-control" value="" required="">
                        </div> 

                        <div class="form-group col-md-12" >

                            <input type="hidden" id="forma_id" name="checkout[forma_id]" placeholder="checkout[forma_id]">
                            <input type="hidden" id="bairro_slug" name="checkout[bairro_slug]" placeholder="checkout[bairro_slug]">

                        </div>
                        <div class="form-group col-md-12" style="padding-left: 0">

                            <input type="submit" id="btn-checkout" class="btn btn-food btn-block" value="Antes consulte a taxa de entrega">


                        </div>



                    </div>

                    <?php echo form_close() ?>

                </div>

            </div>
        </div>
        <!-- end product -->
    </div>

</div>

<?php echo $this->endSection(); ?>




<?php echo $this->section('scripts'); ?>
<script src="<?php echo site_url('admin/vendors/mask/jquery.mask.min.js'); ?>"></script>

<script src="<?php echo site_url('admin/vendors/mask/app.js'); ?>"></script>

<script>

    $("#btn-checkout").prop('disabled', true);

    $(".forma").on('click', function () {

        var forma_id = $(this).attr('data-forma');

        $("#forma_id").val(forma_id);

        if (forma_id == 1) {

            $("#troco").removeClass('hidden');

        } else {
            $("#troco").addClass('hidden');
        }

    });

    $(".forma").on('click', function () {

        var forma_id = $(this).attr('data-forma');

        $("#forma_id").val(forma_id);

        if (forma_id == 4) {

            $("#pix_data").removeClass('hidden');

        } else {
            $("#pix_data").addClass('hidden');
        }

    });

    $("#sem_troco").on('click', function () {


        if (this.checked) {

            $("#troco_para").prop('disabled', true);
            $("#troco_para").val('Não preciso de troco pois tenho o valor certinho');

            $("#troco_para").attr('placeholder', 'Não preciso de troco pois tenho o valor certinho')
        } else {
            $("#troco_para").prop('disabled', false);
            $("#troco_para").attr('placeholder', 'Enviar troco para')
            $("#troco_para").val('');

        }

    });


    $("[name=cep]").focusout(function () {
        var valido = false;
        var cep = $(this).val();

        if ($("[name=cep]").val() !== '62850-000') {
            $("#cep").html('<span class="text-danger small">Cep inválido: Só atendemos a região de Cascavel - CE</span>');
        } else {
            var cep = $(this).val();
            $("#bairros_form").show();
            $("#cep").html('');
            valido = true;

            var cep = $(this).val();
            if (cep.length === 9 && valido === true) {
                $.ajax({

                    beforeSend: function () {

                    },
                    success: function (response) {

                        if (!response.erro) {


                            $("#bairro").show();

                        } else {

                            $("#cep").html(response.erro);

                        }

                    },
                    error: function () {

                        alert('Não foi possível consultar a taxa de entrega. Por favor entre em contato com a nossa equipe e informe o erro: CONSULTA-ERRO-TAXA-ENTREGA');
                    },
                });
            }
        }

    });

</script>
<script>

    $("[name=bairro]").focusout(function () {

        var cep = $(".cep").val();
        var bairro = $(this).val();

        if (cep.length === 9) {
            $.ajax({

                type: 'get',
                url: '<?php echo site_url('checkout/consultacep') ?>',
                dataType: 'json',
                data: {
                    cep: cep,
                    bairro: bairro
                },
                beforeSend: function () {

                    $("#bairro").html('Consultando...');
                    $("[name=cep]").val('');
                    $("[name=bairro]").val('');

                    $("#btn-checkout").prop('disabled', true);
                    $("#btn-checkout").val('Consultando a taxa de entrega...');
                },
                success: function (response) {

                    if (!response.erro) {


                        $("#bairro").html('');

                        $("#valor_entrega").html(response.valor_entrega);
                        $("#bairro_slug").val(response.bairro_slug);

                        $("#cep").html(response.bairro);
                        $("#bairros_form").hide();

                        $("#btn-checkout").prop('disabled', false);
                        $("#btn-checkout").val('Processar pedido');

                        if (response.logradouro) {
                            $("#rua").val(response.logradouro);
                        } else {
                            $("#rua").prop('readonly', false);
                        }

                        $("#endereco").html(response.endereco);

                        $("#total").html(response.total);

                        $("#cep").html(response.bairro);

                    } else {

                        $("#cep").html(response.erro);
                        $("#bairro").html(response.erro);
                        $("#btn-checkout").prop('disabled', true);
                        $("#btn-checkout").val('Antes consulte a taxa de entrega');

                    }



                },
                error: function () {

                    alert('Não foi possível consultar a taxa de entrega. Por favor entre em contato com a nossa equipe e informe o erro: CONSULTA-ERRO-TAXA-ENTREGA-CHECKOUT');

                    $("#btn-checkout").prop('disabled', true);
                    $("#btn-checkout").val('Antes consulte a taxa de entrega');
                },
            });
        }

    });
    
    $("form").submit(function(){
    
        $(this).find(":submit").attr('disabled', 'disabled');
        $("#btn-checkout").val('Processando o seu pedido...');
    });

    $(window).keydown(function(event){
        
        if(event.keyCode == 13){
            event.preventDefault();
            return false;
        }
        
    });
</script>

<?php echo $this->endSection(); ?>


