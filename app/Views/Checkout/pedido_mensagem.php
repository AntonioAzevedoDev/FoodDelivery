<h5>Pedido <?php echo esc($pedido->codigo)?> realizado com sucesso! </h5>

<p>Olá <strong><?php echo esc($pedido->usuario->nome);?></strong>, recebemos o seu pedido <strong><?php echo esc($pedido->codigo)?></strong></p>
<p>Estamos acelerando do lado de cá para que o seu pedido fique pronto rapidinho. Logo logo ele sairá para entrega.</p>

<p>Não se preocupe, quando isso acontecer, avisaremos você por mensagem, beleza ?</p>

<p>
    Enquanto isso, <a href="<?php echo site_url('conta');?>">Clique aqui para ver os seus pedidos</a>
</p>