<?php echo $this->extend('layout/principal_web'); ?>

<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>


<?php echo $this->section('estilos'); ?>

<!-- Aqui enviamos para o template principal os estilos -->

<link rel="stylesheet" href="<?php echo site_url("web/src/assets/css/produto.css") ?>"></link>

<?php echo $this->endSection(); ?>




<?php echo $this->section('conteudo'); ?>

<div class="container section" id="menu" data-aos="fade-up" style="margin-top: 3em">
    <div class="product-content product-wrap clearfix product-deatil center-block" style="max-width: 60%">
        <div class="row">

            <div class="col-md-12">
                
                <div class="alert alert-success" style="margin-top: 2em" role="alert">
                    <h4 class="alert-heading">Perfeito!</h4>
                    <p><?php echo $titulo ?></p>
                    <hr>
                    <p class="mb-0">Verifique sua caixa de entrada para ativar a sua conta</p>
                </div>

        </div>
    </div>

</div>

<?php echo $this->endSection(); ?>




<?php echo $this->section('scripts'); ?>
<script src="<?php echo site_url('admin/vendors/mask/jquery.mask.min.js'); ?>"></script>

<script src="<?php echo site_url('admin/vendors/mask/app.js'); ?>"></script>


<?php echo $this->endSection(); ?>


