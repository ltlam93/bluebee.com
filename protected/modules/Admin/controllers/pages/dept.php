<?php

return array(
    "fields" => array(
        "dept_id" => array(
            "label" => "Department ID"
        ),
        "dept_name" => array(
            "label" => "Department Name"
        ),
        "dept_faculty" => array(
            "label" => "Faculty of Department"
        ),
        "dept_knowledge" => array(
            "label" => "User Name"
        ),
        "dept_behavior" => array(
            "label" => "Behavior",
            "type" => "_html"
        ),
        "dept_target" => array(
            "label" => "Target",
            "type" => "_html"
        ),
        "dept_out_standard" => array(
            "label" => "Out Standard",
            "type" => "_html"
        ),
        "dept_in_standart" => array(
            "label" => "In Standard",
            "type" => "_html"
        ),
        "dept_contact" => array(
            "label" => "Contact",
            "type" => "_html"
        ),
        "dept_language" => array(
            "label" => "Language",
        ),
        "dept_language" => array(
            "label" => "Language",
        ),
    ),
    "columns" => array(
        "user_id", "user_id_fb", "user_real_name", "user_avatar",
    ),
    "actions" => array(
        "_view" => true,
        "_edit" => array(
            "user_real_name", "username",
        ),
        "_delete" => true,
        "_new" => false,
        "_search" => array(
            "user_id", "username", "user_real_name"
        ),
        "_search_advanced" => array(
            "user_id", "username", "user_real_name",
        ),
        "_customButtons" => array()
    ),
    "default" => array(
        "orderBy" => "user_id",
        "orderType" => "asc",
        "page" => 1,
        "per_page" => 10,
        "search" => "",
        "search_advanced" => ""
    ),
    "tableAlias" => "user",
    "title" => "User Manager",
    "condition" => false,
    "limit_values" => array(10, 20, 30, 40),
    "model" => "User",
    "primary" => "user_id",
    "itemLabel" => "User",
    "additionalFiles" => array()
);
