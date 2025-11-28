<?php

session_start();

require __DIR__ . '/../app/Core/Database.php';
require __DIR__ . '/../app/Core/Router.php';
require __DIR__ . '/../app/Core/Security.php';
require __DIR__ . '/../app/Core/Model.php';
require __DIR__ . '/../app/Core/Helper.php';

require __DIR__ . '/../app/Models/User.php';
require __DIR__ . '/../app/Models/Bill.php';
require __DIR__ . '/../app/Models/Task.php';
require __DIR__ . '/../app/Models/Budget.php';
require __DIR__ . '/../app/Models/Gamification.php';
require __DIR__ . '/../app/Models/Notification.php';
require __DIR__ . '/../app/Models/Document.php';
require __DIR__ . '/../app/Models/AIConversation.php';
require __DIR__ . '/../app/Models/CryptoAsset.php';
require __DIR__ . '/../app/Models/Subscription.php';
require __DIR__ . '/../app/Models/Contact.php';
require __DIR__ . '/../app/Models/Project.php';

require __DIR__ . '/../app/Controllers/AuthController.php';
require __DIR__ . '/../app/Controllers/DashboardController.php';
require __DIR__ . '/../app/Controllers/BillController.php';
require __DIR__ . '/../app/Controllers/TaskController.php';
require __DIR__ . '/../app/Controllers/BudgetController.php';
require __DIR__ . '/../app/Controllers/SubscriptionController.php';
require __DIR__ . '/../app/Controllers/ContactController.php';
require __DIR__ . '/../app/Controllers/ProjectController.php';
require __DIR__ . '/../app/Controllers/AIAssistantController.php';
require __DIR__ . '/../app/Controllers/NotificationController.php';
require __DIR__ . '/../app/Controllers/DocumentController.php';
require __DIR__ . '/../app/Controllers/AdminController.php';

use App\Core\Router;

$router = new Router();

$router->get('/', function() {
    header('Location: /dashboard');
});

$authController = new \App\Controllers\AuthController();
$router->get('/login', [$authController, 'showLogin']);
$router->post('/login', [$authController, 'login']);
$router->get('/register', [$authController, 'showRegister']);
$router->post('/register', [$authController, 'register']);
$router->post('/logout', [$authController, 'logout']);
$router->get('/profile', [$authController, 'showProfile']);
$router->post('/profile', [$authController, 'updateProfile']);

$dashboardController = new \App\Controllers\DashboardController();
$router->get('/dashboard', [$dashboardController, 'index']);

$billController = new \App\Controllers\BillController();
$router->get('/bills', [$billController, 'index']);
$router->get('/bills/create', [$billController, 'create']);
$router->post('/bills/create', [$billController, 'create']);
$router->get('/bills/edit/{id}', [$billController, 'edit']);
$router->post('/bills/edit/{id}', [$billController, 'edit']);
$router->post('/bills/delete/{id}', [$billController, 'delete']);

$taskController = new \App\Controllers\TaskController();
$router->get('/tasks', [$taskController, 'index']);
$router->get('/tasks/create', [$taskController, 'create']);
$router->post('/tasks/create', [$taskController, 'create']);
$router->post('/tasks/complete/{id}', [$taskController, 'complete']);
$router->post('/tasks/delete/{id}', [$taskController, 'delete']);

$aiController = new \App\Controllers\AIAssistantController();
$router->get('/ai-assistant', [$aiController, 'index']);
$router->post('/ai-assistant/chat', [$aiController, 'chat']);

$notificationController = new \App\Controllers\NotificationController();
$router->get('/notifications', [$notificationController, 'index']);
$router->post('/notifications/read/{id}', [$notificationController, 'markRead']);
$router->post('/notifications/read-all', [$notificationController, 'markAllRead']);
$router->get('/api/notifications/unread', [$notificationController, 'getUnread']);

$documentController = new \App\Controllers\DocumentController();
$router->get('/documents', [$documentController, 'index']);
$router->post('/documents/upload', [$documentController, 'upload']);
$router->post('/documents/delete/{id}', [$documentController, 'delete']);

$budgetController = new \App\Controllers\BudgetController();
$router->get('/budgets', [$budgetController, 'index']);
$router->get('/budgets/create', [$budgetController, 'create']);
$router->post('/budgets/create', [$budgetController, 'create']);
$router->post('/budgets/delete/{id}', [$budgetController, 'delete']);

$subscriptionController = new \App\Controllers\SubscriptionController();
$router->get('/subscriptions', [$subscriptionController, 'index']);
$router->get('/subscriptions/create', [$subscriptionController, 'create']);
$router->post('/subscriptions/create', [$subscriptionController, 'create']);
$router->post('/subscriptions/delete/{id}', [$subscriptionController, 'delete']);

$contactController = new \App\Controllers\ContactController();
$router->get('/contacts', [$contactController, 'index']);
$router->get('/contacts/create', [$contactController, 'create']);
$router->post('/contacts/create', [$contactController, 'create']);
$router->post('/contacts/delete/{id}', [$contactController, 'delete']);

$projectController = new \App\Controllers\ProjectController();
$router->get('/projects', [$projectController, 'index']);
$router->get('/projects/create', [$projectController, 'create']);
$router->post('/projects/create', [$projectController, 'create']);
$router->get('/projects/view/{id}', [$projectController, 'view']);
$router->post('/projects/delete/{id}', [$projectController, 'delete']);

$adminController = new \App\Controllers\AdminController();
$router->get('/admin', [$adminController, 'index']);
$router->get('/admin/users', [$adminController, 'users']);
$router->post('/admin/users/delete/{id}', [$adminController, 'deleteUser']);
$router->get('/admin/logs', [$adminController, 'logs']);

$router->notFound(function() {
    http_response_code(404);
    echo '404 - Page Not Found';
});

$router->run();
