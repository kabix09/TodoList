<?php
return $taskForm = [
    "title" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_TEXT,
        "label" => "Title",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'titleID',
            "maxLength" => 30,
            'placeholder' => "title",
            'required' => "",
            'value' => ""
        ]
        //"errors" => $_SESSION['formErrors']['title'] ?? NULL
    ],

    "content" => [
        "class" => 'App\Form\Elements\Textarea',
        "type" => App\Form\FormConstants::TYPE_TEXT,
        "label" => "Description",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'contentID',
            "maxLength" => 255,
            'placeholder' => "content",
            'required' => "",
            'value' => ""
        ]
        //"errors" => $_SESSION['formErrors']['content'] ?? NULL
    ],


    "start_date" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_DATE,
        "label" => "Start date",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'startDateID',
            "maxLength" => 35,
            'placeholder' => "startDate",
            'required' => "",
            'value' => (new \DateTime())->format('Y-m-d')
        ]
        //"errors" => $_SESSION['formErrors']['startDate'] ?? NULL
    ],

    "target_end_date" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_DATE,
        "label" => "End date",
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "id" => 'endDateID',
            "maxLength" => 35,
            'placeholder' => "endDate"
        ]
        //"errors" => $_SESSION['formErrors']['endDate'] ?? NULL
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

    "submit" => [
        "class" => 'App\Form\Generic',
        "type" => App\Form\FormConstants::TYPE_SUBMIT,
        "label" => '',
        "wrappers" => include "formWrapper.config.php",
        "attributes" => [
            "value" => 'Create',
            "style" => 'float: right;'
        ]
    ]
];