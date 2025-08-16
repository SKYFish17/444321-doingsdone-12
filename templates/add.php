<section class="content__side">
  <h2 class="content__side-heading">Проекты</h2>

  <nav class="main-navigation">
    <ul class="main-navigation__list">
        <?php foreach( $projects as $project ) : ?>
          <li class="main-navigation__list-item">
            <a
              class="main-navigation__list-item-link"
              href="/?project_id=<?php echo $project['id'] ?>">
                <?php echo htmlspecialchars( $project['title'] ); ?>
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
    <h2 class="content__main-heading">Добавление задачи</h2>

    <form class="form" action="add.php" method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input
              class="form__input <?php echo $errors['name'] ? 'form__input--error' : '' ?>"
              type="text"
              name="name"
              id="name"
              value="<?php echo $form_fields_value['name']; ?>"
              placeholder="Введите название"
            >

            <?php if ($errors['name']) : ?>
              <p class="form__message"><?php echo $errors['name'] ?></p>
            <?php endif; ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select
              class="form__input form__input--select <?php echo $errors['project'] ? 'form__input--error' : '' ?>"
              name="project"
              id="project"
            >
                <?php foreach ($projects as $project) : ?>
                  <option
                    value="<?php echo $project['id'] ?>"
                    <?php echo $form_fields_value['project'] == $project['id'] ? 'selected' : ''?>
                  >
                      <?php echo $project['title'] ?>
                  </option>
                <?php endforeach; ?>
            </select>

            <?php if ($errors['project']) : ?>
              <p class="form__message"><?php echo $errors['project'] ?></p>
            <?php endif; ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>

            <input
              class="form__input form__input--date <?php echo $errors['date'] ? 'form__input--error' : '' ?>"
              type="text"
              name="date"
              id="date"
              value="<?php echo $form_fields_value['date']; ?>"
              placeholder="Введите дату в формате ГГГГ-ММ-ДД"
            >

            <?php if ($errors['date']) : ?>
              <p class="form__message"><?php echo $errors['date'] ?></p>
            <?php endif; ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="file" id="file" value="">

                <label class="button button--transparent" for="file">
                    <span>Выберите файл</span>
                </label>
            </div>

            <?php if ($errors['file']) : ?>
              <p class="form__message"><?php echo $errors['file'] ?></p>
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