<?php
require_once __DIR__ . '/../index.php';

use App\Module\FormActions\Task\SendTask;
use App\Module\Access\TaskActions\Send;
use App\Repository\TaskRepository;

define("FILTER_VALIDATE", ROOT_PATH . './config/filter_validate.config.php');
define("FILTER_SANITIZE", ROOT_PATH . './config/filter_sanitize.config.php');
define("TASK_ASSIGNMENTS", ROOT_PATH . './config/taskAssignments.config.php');

// check guest permission to use the site
$sendAccess = new Send($session, $connection);
$sendAccess->checkAccess();
$sendAccess->core();

// catch task
$taskRepository = new TaskRepository($connection);
$task = $taskRepository->fetchById($_GET['id']);

// edit task form logic: valid & send
$sendTask = new SendTask($session, $connection);
$sendTask->generateToken();
$sendTask->setTemplatePath(ROOT_PATH . './templates/task/form/sendTask.php');
$sendTask->setRecaptchaKey((include(RECAPTCHA))["secretKey"]);
$sendTask->setTask($task);
$sendTask->core();


