<?php
require_once '../vendor/autoload.php';

use App\Entity\Factory\TaskFactory;
use App\Form\Factory\Factory;
use App\Token\Token;

define('FORM_CONFIG', "../config/form.config.php");
define('TASK_FORM', "../config/editTaskForm.config.php");

$taskConfig = include TASK_FORM;
$editedTask = NULL;

foreach ($_SESSION['tasks'] as $task)
{
    if($task->getId() == $id)
    {
        $editedTask = $task;
    }
}

$editedTask = TaskFactory::entityToArray($editedTask);

foreach ($taskConfig as $element => &$values)
{
    if($element !== 'hidden' && $element !== 'submit')
    {
        $values["attributes"]["value"] = $editedTask[$element];
    }
}

$formFactory = new Factory();
$formFactory->generate($taskConfig,
    (new Token($_SESSION['token']))
        ->hash()
        ->encode()
        ->getToken());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Task Form</title>
    <meta name="author" content="kabix09" />
    <meta http-equiv = "Content-Type" content = "text/html; charset = UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/js-snackbar.css">

    <script src="../js/js-snackbar.js"></script>
    <script>
        path = "<?=strtolower(explode('/',$_SERVER['SERVER_PROTOCOL'])[0])?>://<?=$_SERVER['SERVER_NAME']?>:<?=$_SERVER['SERVER_PORT']?>/src/JSON/variables.php?name=editErrors";
    </script>
    <script src="../js/formErrors.js"></script>
</head>
<body style="font-size: 18px;">
<main style="background-color: ivory;
                padding: 15px;
                border-radius: 20px;
                width: 26rem;
                position: fixed; top: 40%; left: 50%;
                transform: translate(-50%, -40%);
                box-sizing:border-box;
                -webkit-box-shadow: 5px 5px 15px 0px rgba(0,0,0,0.75);
                -moz-box-shadow: 5px 5px 15px 0px rgba(0,0,0,0.75);
                box-shadow: 5px 5px 15px 0px rgba(0,0,0,0.75);">
    <div style="text-align: center; margin: 10px 0 25px 0; font-size: 20px;">
            <span style="border-bottom:  2px solid #000000;">
                Edit Task
            </span>
    </div>

    <?= $formFactory->render(include FORM_CONFIG, FALSE, TRUE)?>
</main>
</body>
</html>