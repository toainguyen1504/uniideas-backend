<?php

// COMMON CONSTANTS

use App\Models\Idea;

if (!defined('NOTIFICATION_SUCCESS')) define('NOTIFICATION_SUCCESS', 'success');
if (!defined('NOTIFICATION_ERROR')) define('NOTIFICATION_ERROR', 'error');

//-- File patch for Idea --
if (!defined('FILE_PATH_COLLECTION')) define('FILE_PATH_COLLECTION', Idea::FILE_PATH_COLLECTION);
//-- end File patch for Idea --
