# TODO list 
Simple todo list created in pure PHP.

## Table of Contents
* [Genera Info](#general-info)
* [Technologies Used](#technologies)
* [Launch](#launch)
* [Features](#features)

## General info
The purpose of writing this project was to get create simple todo list containing task to do for clients from colleagues (or his family before going home ;) )
in **pure PHP** with using **OOP** trying to keep the **SOLID** rules, **design pattern** and clean code  

## Technologies
Project is created with:
* php 7.*
* MySQL + phpmyadmin
* css
* html

## Launch
To run this project install XAMPP or LAMP or other package for www server. Next move to the directory where you have composer.json and update dependencies:
```
$ composer install
```
Next run .sql scripts to prepare database and fix connection by setting your own parameters in `vendor\kabix09\connection-factory\src\database.ini`.
(I know it's snacking but I tried to use own other project as external module)

Now everything is prepared. You can open your browser and go to localhost website. 

Defaultly, application should run on `localhost:3000` address.


## Features
* Login and register module
* Tasks system
* Session module
#### To do
* Add a reminder about the due date of tasks 
* Add graphic calendar interface
* Clean code away!!