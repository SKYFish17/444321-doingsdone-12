<section class="content__side">
  <h2 class="content__side-heading">Проекты</h2>

  <nav class="main-navigation">
    <ul class="main-navigation__list">
        <?php foreach ($projects as $project) : ?>
          <li
            class="main-navigation__list-item <?php echo $active_project['id'] == $project['id'] ? 'main-navigation__list-item--active' : '' ?>">
            <a
              class="main-navigation__list-item-link"
              href="/?project_id=<?php echo $project['id'] ?>">
                <?php echo htmlspecialchars($project['title']); ?>
            </a>
            <span
              class="main-navigation__list-item-count"><?php echo get_project_number_of_tasks($tasks, $project); ?></span>
          </li>
        <?php endforeach; ?>
    </ul>
  </nav>

  <a class="button button--transparent button--plus content__side-button"
     href="pages/form-project.html" target="project_add">Добавить проект</a>
</section>

<main class="content__main">
  <h2 class="content__main-heading">Список задач</h2>

  <form class="search-form" action="/index.php" method="GET" autocomplete="off">
    <input
      class="search-form__input"
      type="text" name="search"
      value="<?php echo $is_search ? trim($_GET['search']) : ''; ?>"
      placeholder="Поиск по задачам"
    >

    <input class="search-form__submit" type="submit" name="" value="Искать">
  </form>

  <div class="tasks-controls">
    <nav class="tasks-switch">
      <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
      <a href="/" class="tasks-switch__item">Повестка дня</a>
      <a href="/" class="tasks-switch__item">Завтра</a>
      <a href="/" class="tasks-switch__item">Просроченные</a>
    </nav>

    <label class="checkbox">
      <input class="checkbox__input visually-hidden show_completed"
             type="checkbox" <?php echo ($show_complete_tasks === 1) ? 'checked' : ''; ?>>
      <span class="checkbox__text">Показывать выполненные</span>
    </label>
  </div>

  <table class="tasks">
      <?php
      if ($is_search) {
          $tasks = $search_result;
      } else {
          $tasks = $active_project ? $tasks_by_project : $tasks;
      }
      ?>
      <?php if (count($tasks) === 0 && $is_search) : ?>
        <tr>
          <td>Ничего не найдено по вашему запросу
          <td>
        <tr>
      <?php else : ?>
          <?php foreach ($tasks as $task) : ?>
              <?php if ($show_complete_tasks === 0 && $task['status']) : continue; ?>
              <?php else : ?>
              <tr
                class="tasks__item task <?php echo $task['status'] ? 'task--completed' : ''; ?> <?php echo is_urgency_task($task['dt_deadline']) ? 'task--important' : ''; ?>">
                <td class="task__select">
                  <label class="checkbox task__checkbox">
                    <input class="checkbox__input visually-hidden" type="checkbox" checked>
                    <span class="checkbox__text"><?php echo htmlspecialchars($task['title']); ?></span>
                  </label>
                </td>
                <td class="task__date"><?php echo htmlspecialchars($task['dt_deadline']); ?></td>

                <td class="task__controls">
                    <?php if ($task['file_path']) : ?>
                      <a href="<?php echo $task['file_path'] ?>" class="task_download-file" target="_blank">
                        <img src="/img/download-link.png" width="14" height="16" alt="Загрузить файл">
                      </a>
                    <?php endif; ?>
                </td>
              </tr>
              <?php endif; ?>
          <?php endforeach; ?>
      <?php endif; ?>
  </table>
</main>
