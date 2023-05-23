<?php
function __v($toTranslate)
{
    return \General\Language::t($toTranslate, 'view');
}
function __e($toTranslate)
{
    return \General\Language::t($toTranslate, 'error');
}
function __($file)
{
    return \General\Language::dictionary($file);
}
function __lang()
{
    return \General\Language::get();
}
function Route($url)
{
    return \General\Route::url($url);
}
function html()
{
    $lang = __lang();
    $dir = ($lang === 'ar') ? 'rtl' : 'ltr';
    echo 'lang="' . $lang . '" dir="' . $dir . '"';
}
function react($url)
{
    return '/dashboard/' . $url . '/';
}

function error($message)
{
    return ['errors' => ['common' =>  __v($message)]];
}
function success($message = 'done', $stop = true)
{
    return ['errors' => ['common' => ['i' => __v($message), 'stop' => $stop]]];
}
function dataMap($data, $key = 'id')
{
    $dataMap = [];
    foreach ($data as $row) {
        $dataMap[$row->id] = $row;
    }
    return $dataMap;
}

function toArray($objs, $key)
{
    $array = [];
    foreach ($objs as $obj) {
        $array[] = $obj[$key];
    }
    return $array;
}
function redirectRt($url)
{
    return ['redirect' => '/dashboard/' . $url];
}