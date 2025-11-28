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
require __DIR__ . '/../app/Models/Debt.php';
require __DIR__ . '/../app/Models/Asset.php';
require __DIR__ . '/../app/Models/Note.php';
require __DIR__ . '/../app/Models/Event.php';
require __DIR__ . '/../app/Models/Contract.php';
require __DIR__ . '/../app/Models/Pet.php';
require __DIR__ . '/../app/Models/ReadingList.php';
require __DIR__ . '/../app/Models/Travel.php';
require __DIR__ . '/../app/Models/Vehicle.php';
require __DIR__ . '/../app/Models/Password.php';
require __DIR__ . '/../app/Models/TimeTracking.php';
require __DIR__ . '/../app/Models/Relationship.php';

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
require __DIR__ . '/../app/Controllers/DebtController.php';
require __DIR__ . '/../app/Controllers/AssetController.php';
require __DIR__ . '/../app/Controllers/NoteController.php';
require __DIR__ . '/../app/Controllers/EventController.php';
require __DIR__ . '/../app/Controllers/ContractController.php';
require __DIR__ . '/../app/Controllers/PetController.php';
require __DIR__ . '/../app/Controllers/ReadingListController.php';
require __DIR__ . '/../app/Controllers/TravelController.php';
require __DIR__ . '/../app/Controllers/VehicleController.php';
require __DIR__ . '/../app/Controllers/PasswordController.php';
require __DIR__ . '/../app/Controllers/TimeTrackingController.php';

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

$debtController = new \App\Controllers\DebtController();
$router->get('/debts', [$debtController, 'index']);
$router->get('/debts/create', [$debtController, 'create']);
$router->post('/debts/create', [$debtController, 'create']);
$router->get('/debts/edit/{id}', [$debtController, 'edit']);
$router->post('/debts/edit/{id}', [$debtController, 'edit']);
$router->post('/debts/delete/{id}', [$debtController, 'delete']);

$assetController = new \App\Controllers\AssetController();
$router->get('/assets', [$assetController, 'index']);
$router->get('/assets/create', [$assetController, 'create']);
$router->post('/assets/create', [$assetController, 'create']);
$router->get('/assets/edit/{id}', [$assetController, 'edit']);
$router->post('/assets/edit/{id}', [$assetController, 'edit']);
$router->post('/assets/delete/{id}', [$assetController, 'delete']);

$noteController = new \App\Controllers\NoteController();
$router->get('/notes', [$noteController, 'index']);
$router->get('/notes/create', [$noteController, 'create']);
$router->post('/notes/create', [$noteController, 'create']);
$router->get('/notes/edit/{id}', [$noteController, 'edit']);
$router->post('/notes/edit/{id}', [$noteController, 'edit']);
$router->post('/notes/toggle-favorite/{id}', [$noteController, 'toggleFavorite']);
$router->post('/notes/delete/{id}', [$noteController, 'delete']);

$eventController = new \App\Controllers\EventController();
$router->get('/events', [$eventController, 'index']);
$router->get('/events/create', [$eventController, 'create']);
$router->post('/events/create', [$eventController, 'create']);
$router->get('/events/edit/{id}', [$eventController, 'edit']);
$router->post('/events/edit/{id}', [$eventController, 'edit']);
$router->post('/events/delete/{id}', [$eventController, 'delete']);

$contractController = new \App\Controllers\ContractController();
$router->get('/contracts', [$contractController, 'index']);
$router->get('/contracts/create', [$contractController, 'create']);
$router->post('/contracts/create', [$contractController, 'create']);
$router->get('/contracts/edit/{id}', [$contractController, 'edit']);
$router->post('/contracts/edit/{id}', [$contractController, 'edit']);
$router->post('/contracts/delete/{id}', [$contractController, 'delete']);

$petController = new \App\Controllers\PetController();
$router->get('/pets', [$petController, 'index']);
$router->get('/pets/create', [$petController, 'create']);
$router->post('/pets/create', [$petController, 'create']);
$router->get('/pets/edit/{id}', [$petController, 'edit']);
$router->post('/pets/edit/{id}', [$petController, 'edit']);
$router->post('/pets/delete/{id}', [$petController, 'delete']);

$readingController = new \App\Controllers\ReadingListController();
$router->get('/reading', [$readingController, 'index']);
$router->get('/reading/create', [$readingController, 'create']);
$router->post('/reading/create', [$readingController, 'create']);
$router->get('/reading/edit/{id}', [$readingController, 'edit']);
$router->post('/reading/edit/{id}', [$readingController, 'edit']);
$router->post('/reading/delete/{id}', [$readingController, 'delete']);

$travelController = new \App\Controllers\TravelController();
$router->get('/travel', [$travelController, 'index']);
$router->get('/travel/create', [$travelController, 'create']);
$router->post('/travel/create', [$travelController, 'create']);
$router->get('/travel/edit/{id}', [$travelController, 'edit']);
$router->post('/travel/edit/{id}', [$travelController, 'edit']);
$router->post('/travel/delete/{id}', [$travelController, 'delete']);

$vehicleController = new \App\Controllers\VehicleController();
$router->get('/vehicles', [$vehicleController, 'index']);
$router->get('/vehicles/create', [$vehicleController, 'create']);
$router->post('/vehicles/create', [$vehicleController, 'create']);
$router->get('/vehicles/edit/{id}', [$vehicleController, 'edit']);
$router->post('/vehicles/edit/{id}', [$vehicleController, 'edit']);
$router->post('/vehicles/delete/{id}', [$vehicleController, 'delete']);

$passwordController = new \App\Controllers\PasswordController();
$router->get('/passwords', [$passwordController, 'index']);
$router->get('/passwords/create', [$passwordController, 'create']);
$router->post('/passwords/create', [$passwordController, 'create']);
$router->get('/passwords/edit/{id}', [$passwordController, 'edit']);
$router->post('/passwords/edit/{id}', [$passwordController, 'edit']);
$router->get('/passwords/view/{id}', [$passwordController, 'view']);
$router->post('/passwords/delete/{id}', [$passwordController, 'delete']);

$timeController = new \App\Controllers\TimeTrackingController();
$router->get('/time-tracking', [$timeController, 'index']);
$router->post('/time-tracking/start', [$timeController, 'start']);
$router->post('/time-tracking/stop/{id}', [$timeController, 'stop']);
$router->post('/time-tracking/delete/{id}', [$timeController, 'delete']);
$router->get('/api/time-tracking/active', [$timeController, 'getActive']);

$router->notFound(function() {
    http_response_code(404);
    echo '404 - Page Not Found';
});

$router->run();
