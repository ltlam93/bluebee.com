<?php

return array(
    "fields" => array(
        "doc_id" => array(
            "label" => "Doc ID"
        ),
        "doc_path" => array(
            "label" => "File",
            "type" => "_document",
            "modelFile" => "doc_path_file"
        ),
        "doc_url" => array(
            "label" => "Preview",
            "type" => "_image",
        ),
        "doc_name" => array(
            "label" => "Doc Name"
        ),
        "doc_description" => array(
            "label" => "Doc Description"
        ),
        "doc_author_name" => array(
            "label" => "User upload",
        ),
        "subject_dept" => array(
            "label" => "Department",
            "type" => "_dropdown",
            "_list" => array(
                "primary" => "dept_id",
                "displayAttr" => "dept_name",
                "src" => function() {
            $rows = Dept::model()->findAll();
            return $rows;
        }
            ),
        ),
        "subject_faculty" => array(
            "label" => "Faculty"
        ),
        "subject_type" => array(
            "label" => "Subject Type"
        ),
        "doc_dept_name" => array(
            "label" => "Department Name",
            "src" => "dept.dept_name",
        ),
    ),
    "columns" => array(
        "doc_id", "doc_url", "doc_name", "doc_description", "doc_author_name", "subject_faculty", "subject_type", "doc_dept_name", "doc_path",
    ),
    "actions" => array(
        "_view" => true,
        "_edit" => array(
            "doc_name", "doc_description", "doc_author_name", "subject_dept", "subject_faculty", "subject_type"
        ),
        "_delete" => true,
        "_new" => array(
            "type" => "popup",
            "attr" => array(
                "doc_name", "doc_description", "subject_faculty", "subject_type", "subject_dept", "doc_path"
            ),
            "extend" => array(
                "doc_author" => Util::param("ADMIN_ID")
            )
        ),
        "_search" => array(
            "doc_id", "doc_name",
        ),
        "_search_advanced" => array(
            "doc_author_name", "subject_dept", "subject_faculty", "doc_name",
        ),
        "_customButtons" => array()
    ),
    "default" => array(
        "orderBy" => "doc_id",
        "orderType" => "asc",
        "page" => 1,
        "per_page" => 10,
        "search" => "",
        "search_advanced" => ""
    ),
    "join" => array(
        "dept" => array(
            "table" => "select dept_id, dept_name from tbl_dept",
            "type" => "left join",
            "selected_properties" => array(
                "dept_name" => "doc_dept_name" //select tu dept join voi doc
            ),
            "on" => array(
                "dept_id" => "t.subject_dept"
            )
        ),
    ),
    // select dept.dept_name as doc_dept_name, t.* from ....... lefr join (select dept_id, dept_name from tbl_dept) dept ON dept.dept_id = t.subject_dept
    "tableAlias" => "document",
    "title" => "Document Manager",
    "condition" => false,
    "limit_values" => array(10, 20, 30, 40),
    "model" => "Doc",
    "primary" => "doc_id",
    "itemLabel" => "Document",
    "additionalFiles" => array(),
    "insertScenario" => "fromAdmin",
    "formUpload" => TRUE,
);
