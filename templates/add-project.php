<section class="content__side">
  <h2 class="content__side-heading">Проекты</h2>

  <nav class="main-navigation">
    <ul class="main-navigation__list">
        <?php foreach ($projects as $project) : ?>
          <li class="main-navigation__list-item">
            <a
              class="main-navigation__list-item-link"
              href="/?project_id=<?php echo $project['id'] ?>">
                <?php echo htmlspecialchars($project['title']); ?>
            </a>
            <span class="main-navigation__list-item-count"><?php echo $project['tasks_num']; ?></span>
          </li>
        <?php endforeach; ?>
    </ul>
  </nav>

  <a class="button button--transparent button--plus content__side-button"
     href="/add-project.php" target="project_add">Добавить проект</a>
</section>

<main class="content__main">
  <h2 class="content__main-heading">Добавление проекта</h2>

  <form class="form" action="/add-project.php" method="post" autocomplete="off">
    <div class="form__row">
      <label class="form__label" for="project_name">Название <sup>*</sup></label>

      <input
        class="form__input <?php echo $errors['name'] ? 'form__input--error' : '' ?>"
        type="text"
        name="name"
        id="project_name"
        value="<?php echo $form_fields_value['name'] ?? '' ?>"
        placeholder="Введите название проекта"
      >

        <?php if ($errors['name']) : ?>
          <p class="form__message"><?php echo $errors['name'] ?></p>
        <?php endif; ?>
    </div>

    <div class="form__row form__row--controls">
        <?php if ($errors) : ?>
          <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
        <?php endif; ?>

      <input class="button" type="submit" name="" value="Добавить">
    </div>
  </form>
</main>