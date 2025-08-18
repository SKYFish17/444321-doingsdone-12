<section class="content__side">
  <p class="content__side-info">Если еще нет аккаунта, зарегистрируйтесь на сайте</p>

  <a class="button button--transparent content__side-button" href="/register.php">Зарегистрироваться</a>
</section>

<main class="content__main">
  <h2 class="content__main-heading">Вход на сайт</h2>

  <form class="form" action="/auth.php" method="post" autocomplete="off">
    <div class="form__row">
      <label class="form__label" for="email">E-mail <sup>*</sup></label>

      <input class="form__input <?php echo $errors['email'] ? 'form__input--error' : '' ?>" type="text" name="email"
             id="email" value="<?php echo $form_fields_value['email'] ?? '' ?>"
             placeholder="Введите e-mail">

        <?php if ($errors['email']) : ?>
          <p class="form__message"><?php echo $errors['email'] ?></p>
        <?php endif; ?>
    </div>

    <div class="form__row">
      <label class="form__label" for="password">Пароль <sup>*</sup></label>

      <input class="form__input <?php echo $errors['password'] ? 'form__input--error' : '' ?>" type="password"
             name="password" id="password" value="<?php echo $form_fields_value['password'] ?? '' ?>"
             placeholder="Введите пароль">

        <?php if ($errors['password']) : ?>
          <p class="form__message"><?php echo $errors['password'] ?></p>
        <?php endif; ?>
    </div>

    <div class="form__row form__row--controls">
        <?php if ($errors) : ?>
          <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
        <?php endif; ?>

      <input class="button" type="submit" name="" value="Войти">
    </div>
  </form>
</main>