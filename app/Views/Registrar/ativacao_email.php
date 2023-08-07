<h5><?php echo esc($usuario->nome)?>, agora falta muito pouco! </h5>

<p>Clique no link abaixo para ativar a sua conta e aproveirar as delícias que a Delicias da Auzi Delivery tem para oferecer.</p>

<p>
    <a href="<?php echo site_url('registrar/ativar/'.$usuario->token);?>">Ativar minha conta</a>
</p>