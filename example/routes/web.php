<?php

Route::view('/', 'welcome');

Route::resource('comments', 'CommentController');
