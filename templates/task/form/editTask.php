<?php
require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'init.php';

use App\Module\Access;
use App\Entity\Mapper\TaskMapper;
use App\Service\Config\{Config, Constants};
use App\Service\Form\Factory\Factory;
use App\Service\Session\Session;
use App\Service\Token\Token;

$taskConfig = Config::init()::action(Constants::EDIT_TASK)::module(Constants::FORM)::get();
$editedTask = NULL;

foreach ($this->session['user']->getTaskCollection() as $task)
{
    if($task->getId() == $_GET['id'])
    {
        $editedTask = $task;
    }
}

$editedTask = TaskMapper::convertEntityToArray($editedTask);

// split time fields
$ex1 = explode(" ", $editedTask['start_date']);
$editedTask['start_date'] = $ex1[0];
$editedTask['start_time'] = substr($ex1[1], 0 , -3);

if(is_null($editedTask['target_end_date']))
{
    $editedTask['target_end_date'] = NULL;
    $editedTask['end_time'] = NULL;
}else{
    $ex2 = explode(" ", $editedTask['target_end_date']);
    $editedTask['target_end_date'] = $ex2[0];
    $editedTask['end_time'] = substr($ex2[1], 0 , -3);
}



foreach ($taskConfig as $element => &$values)
{
    if($element !== 'hidden' && $element !== 'recaptchaResponse' && $element !== 'submit')
    {
        $values["attributes"]["value"] = $editedTask[$element];
    }
}

$formFactory = new Factory();
$formFactory->generate($taskConfig,
                        (new Token($this->session['token']))
                            ->hash()
                            ->binToHex()
                            ->getToken());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Task Form</title>
    <meta name="author" content="kabix09" />
    <meta http-equiv = "Content-Type" content = "text/html; charset = UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/style/form.css>
    <link rel="stylesheet" href=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/style/js-snackbar.css>

    <script src=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/js/js-snackbar.js></script>
    <script>
        path = "<?=strtolower(explode('/',$_SERVER['SERVER_PROTOCOL'])[0])?>://<?=$_SERVER['SERVER_NAME']?>:<?=$_SERVER['SERVER_PORT']?>/public/endpoints/variables.php?name=editErrors";
    </script>
    <script src=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/js/formErrors.js></script>

    <?php include_once (SITE_ROOT . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR . "recaptchaScript.php"); ?>
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

    <?= $formFactory->render(Config::init()::module(Constants::FORM_TEMPLATE)::get(), FALSE, TRUE)?>
</main>
</body>
</html>
