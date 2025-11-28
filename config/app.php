<?php

return [
    'app_name' => 'Life Atlas',
    'app_url' => getenv('REPL_SLUG') ? 'https://' . getenv('REPL_SLUG') . '.' . getenv('REPL_OWNER') . '.repl.co' : 'http://localhost:5000',
    'timezone' => 'UTC',
    'session_lifetime' => 120,
    'upload_max_size' => 10485760,
    'allowed_file_types' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'txt'],
    'items_per_page' => 20,
    'openai_api_key' => getenv('OPENAI_API_KEY') ?: '',
    'openai_model' => 'gpt-3.5-turbo',
];
