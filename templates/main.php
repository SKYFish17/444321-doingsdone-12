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
              class="main-navigation__list-item-count"><?php echo $project['tasks_num']; ?></span>
          </li>
        <?php endforeach; ?>
    </ul>
  </nav>

  <a class="button button--transparent button--plus content__side-button"
     href="/add-project.php">Добавить проект</a>
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
      <a href="/?date=all"
         class="tasks-switch__item <?php echo isset($_GET['date']) && $_GET['date'] === 'all' || !isset($_GET['date']) ? 'tasks-switch__item--active' : ''; ?>">Все
        задачи</a>
      <a href="/?date=tod"
         class="tasks-switch__item <?php echo isset($_GET['date']) && $_GET['date'] === 'tod' ? 'tasks-switch__item--active' : ''; ?>">Повестка
        дня</a>
      <a href="/?date=tom"
         class="tasks-switch__item <?php echo isset($_GET['date']) && $_GET['date'] === 'tom' ? 'tasks-switch__item--active' : ''; ?>">Завтра</a>
      <a href="/?date=expired"
         class="tasks-switch__item <?php echo isset($_GET['date']) && $_GET['date'] === 'expired' ? 'tasks-switch__item--active' : ''; ?>">Просроченные</a>
    </nav>

    <label class="checkbox">
      <input class="checkbox__input visually-hidden show_completed"
             type="checkbox"
          <?php echo ($show_complete_tasks) ? 'checked' : ''; ?>
      >
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
              <?php if ($show_complete_tasks == 0 && $task['status']) : continue; ?>
              <?php else : ?>
            <tr
              class="tasks__item task <?php echo $task['status'] ? 'task--completed' : ''; ?> <?php echo is_urgency_task($task['dt_deadline']) ? 'task--important' : ''; ?>"
              data-task-id>
              <td class="task__select">
                <label class="checkbox task__checkbox">
                  <input
                    class="checkbox__input visually-hidden"
                    type="checkbox"
                    value="<?php echo $task['id'] ?>"
                      <?php echo $task['status'] ? 'checked' : ''; ?>
                  >
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
