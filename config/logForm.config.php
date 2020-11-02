<?php
return $logForm = [
    "nick" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_TEXT,
        "label" => "Username",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'nickID',
            "maxLength" => 30,
            'placeholder' => "nickname",
            'required' => "",
            'value' => ""
        ]
        //"errors" => $_SESSION['formErrors']['nick'] ?? NULL
    ],

    "password" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_PASSWORD,
        "label" => "Password",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'passwordID',
            "maxLength" => 35,
            'placeholder' => "password",
            'required' => "",
            'value' => ""
        ]
        //"errors" => $_SESSION['formErrors']['password'] ?? NULL
    ],

    "hidden" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_HIDDEN,
        "label" => '',
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "value" => ''
        ]
    ],

    "recaptchaResponse" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_HIDDEN,
        "label" => '',
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'recaptchaResponse',
            "value" => ''
        ]
    ],

    "submit" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_SUBMIT,
        "label" => '',
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "value" => 'LogIn',
            "style" => 'float: right; margin-right: 10px;'
        ]
    ]
];