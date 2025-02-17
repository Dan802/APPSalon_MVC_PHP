<h1 class="nombre-pagina">Inicio de Sesión</h1>
<p class="descripcion-pagina">!Nos alegra volverte a ver¡ <br>Por favor Inicia Sesión</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form action="/" class="formulario" method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email"
        id="email"
        placeholder="Tu Email"
        name="email"
        value="<?php echo s($auth->email); ?>"
        />
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input type="password"
        id="password"
        placeholder="Tu Password"
        name="password"/>
    </div>

    <input type="submit" class="boton" value="Iniciar Sesión">

</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>