

<div class="form-row">


    <div class="form-group col-md-4">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" value="<?php echo old('nome', esc($usuario->nome)); ?>" >
    </div>
    <div class="form-group col-md-2">
        <label for="cpf">Cpf</label>
        <input type="text" class="form-control cpf" name="cpf" id="cpf" value="<?php echo old('cpf', esc($usuario->cpf)); ?>" >
    </div>
    <div class="form-group col-md-3">
        <label for="telefone">Telefone</label>
        <input type="text" class="form-control sp_celphones" name="telefone" id="telefone" value="<?php echo old('telefone', esc($usuario->telefone)) ?>" >
    </div>
    <div class="form-group col-md-3">
        <label for="email">Email</label>
        <input type="text" class="form-control" name="email" id="email" value="<?php echo old('email', esc($usuario->email)) ?>" >
    </div>

</div>

<div class="form-row">
    <div class="form-group col-md-3">
        <label for="password">Senha</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <div class="form-group col-md-3">
        <label for="password_confirmation">Confirmação de senha</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
    </div>


</div>

<div class="form-check form-check-flat form-check-primary mb-2">
    <label for="ativo" class="form-check-label">

        <input type="hidden" value="0" name="ativo">

        <input type="checkbox" class="form-check-input" id="ativo" name="ativo" value="1" <?php if (old('ativo', $usuario->ativo)): ?> checked="" <?php endif; ?>>
        Ativo
    </label>
</div>

<div class="form-check form-check-flat form-check-primary mb-4">
    <label for="is_admin" class="form-check-label">

        <input type="hidden" value="0" name="is_admin">

        <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin" value="1" <?php if (old('is_admin', $usuario->is_admin)): ?> checked="" <?php endif; ?>>
        Administrador
    </label>
</div>


<button type="submit" class="btn btn-primary mr-2 btn-sm">
    <i class="mdi mdi-checkbox-marked-circle btn-icon-prepend"></i>
    Salvar
</button>

