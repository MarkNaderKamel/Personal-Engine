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
require __DIR__ . '/../app/Models/JobApplication.php';
require __DIR__ . '/../app/Models/Resume.php';
require __DIR__ . '/../app/Models/Goal.php';
require __DIR__ . '/../app/Models/Birthday.php';
require __DIR__ . '/../app/Models/Habit.php';
require __DIR__ . '/../app/Models/SocialLink.php';
require __DIR__ . '/../app/Models/Transaction.php';
require __DIR__ . '/../app/Models/WellnessLog.php';
require __DIR__ . '/../app/Models/InventoryItem.php';
require __DIR__ . '/../app/Models/PantryItem.php';
require __DIR__ . '/../app/Models/Recipe.php';

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
require __DIR__ . '/../app/Controllers/CryptoController.php';
require __DIR__ . '/../app/Controllers/RelationshipController.php';
require __DIR__ . '/../app/Controllers/AnalyticsController.php';
require __DIR__ . '/../app/Controllers/JobApplicationController.php';
require __DIR__ . '/../app/Controllers/ResumeController.php';
require __DIR__ . '/../app/Controllers/GoalController.php';
require __DIR__ . '/../app/Controllers/BirthdayController.php';
require __DIR__ . '/../app/Controllers/HabitController.php';
require __DIR__ . '/../app/Controllers/SocialLinkController.php';
require __DIR__ . '/../app/Controllers/TransactionController.php';

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
$router->get('/forgot-password', [$authController, 'showForgotPassword']);
$router->post('/forgot-password', [$authController, 'forgotPassword']);
$router->get('/reset-password', [$authController, 'showResetPassword']);
$router->post('/reset-password', [$authController, 'resetPassword']);
$router->post('/change-password', [$authController, 'changePassword']);

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
$router->get('/tasks/edit/{id}', [$taskController, 'edit']);
$router->post('/tasks/edit/{id}', [$taskController, 'edit']);
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
$router->get('/documents/download/{id}', [$documentController, 'download']);
$router->post('/documents/delete/{id}', [$documentController, 'delete']);

$budgetController = new \App\Controllers\BudgetController();
$router->get('/budgets', [$budgetController, 'index']);
$router->get('/budgets/create', [$budgetController, 'create']);
$router->post('/budgets/create', [$budgetController, 'create']);
$router->get('/budgets/edit/{id}', [$budgetController, 'edit']);
$router->post('/budgets/edit/{id}', [$budgetController, 'edit']);
$router->post('/budgets/add-expense/{id}', [$budgetController, 'addExpense']);
$router->post('/budgets/delete/{id}', [$budgetController, 'delete']);

$transactionController = new \App\Controllers\TransactionController();
$router->get('/transactions', [$transactionController, 'index']);
$router->get('/transactions/create', [$transactionController, 'create']);
$router->post('/transactions/create', [$transactionController, 'create']);
$router->get('/transactions/edit/{id}', [$transactionController, 'edit']);
$router->post('/transactions/edit/{id}', [$transactionController, 'edit']);
$router->post('/transactions/delete/{id}', [$transactionController, 'delete']);
$router->get('/transactions/report', [$transactionController, 'report']);
$router->get('/transactions/export', [$transactionController, 'exportCsv']);

$subscriptionController = new \App\Controllers\SubscriptionController();
$router->get('/subscriptions', [$subscriptionController, 'index']);
$router->get('/subscriptions/create', [$subscriptionController, 'create']);
$router->post('/subscriptions/create', [$subscriptionController, 'create']);
$router->get('/subscriptions/edit/{id}', [$subscriptionController, 'edit']);
$router->post('/subscriptions/edit/{id}', [$subscriptionController, 'edit']);
$router->post('/subscriptions/toggle-status/{id}', [$subscriptionController, 'toggleStatus']);
$router->post('/subscriptions/delete/{id}', [$subscriptionController, 'delete']);

$contactController = new \App\Controllers\ContactController();
$router->get('/contacts', [$contactController, 'index']);
$router->get('/contacts/create', [$contactController, 'create']);
$router->post('/contacts/create', [$contactController, 'create']);
$router->get('/contacts/view/{id}', [$contactController, 'view']);
$router->get('/contacts/edit/{id}', [$contactController, 'edit']);
$router->post('/contacts/edit/{id}', [$contactController, 'edit']);
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

$cryptoController = new \App\Controllers\CryptoController();
$router->get('/crypto', [$cryptoController, 'index']);
$router->get('/crypto/create', [$cryptoController, 'create']);
$router->post('/crypto/create', [$cryptoController, 'create']);
$router->get('/crypto/edit/{id}', [$cryptoController, 'edit']);
$router->post('/crypto/edit/{id}', [$cryptoController, 'edit']);
$router->post('/crypto/delete/{id}', [$cryptoController, 'delete']);

$relationshipController = new \App\Controllers\RelationshipController();
$router->get('/relationships', [$relationshipController, 'index']);
$router->get('/relationships/create', [$relationshipController, 'create']);
$router->post('/relationships/create', [$relationshipController, 'create']);
$router->get('/relationships/edit/{id}', [$relationshipController, 'edit']);
$router->post('/relationships/edit/{id}', [$relationshipController, 'edit']);
$router->post('/relationships/delete/{id}', [$relationshipController, 'delete']);

$analyticsController = new \App\Controllers\AnalyticsController();
$router->get('/analytics', [$analyticsController, 'index']);

$jobController = new \App\Controllers\JobApplicationController();
$router->get('/jobs', [$jobController, 'index']);
$router->get('/jobs/create', [$jobController, 'create']);
$router->post('/jobs/create', [$jobController, 'create']);
$router->get('/jobs/edit/{id}', [$jobController, 'edit']);
$router->post('/jobs/edit/{id}', [$jobController, 'edit']);
$router->get('/jobs/view/{id}', [$jobController, 'view']);
$router->post('/jobs/status/{id}', [$jobController, 'updateStatus']);
$router->post('/jobs/delete/{id}', [$jobController, 'delete']);

$resumeController = new \App\Controllers\ResumeController();
$router->get('/resume', [$resumeController, 'index']);
$router->get('/resume/create', [$resumeController, 'createResume']);
$router->post('/resume/create', [$resumeController, 'createResume']);
$router->get('/resume/add-experience', [$resumeController, 'addExperience']);
$router->post('/resume/add-experience', [$resumeController, 'addExperience']);
$router->get('/resume/edit-experience/{id}', [$resumeController, 'editExperience']);
$router->post('/resume/edit-experience/{id}', [$resumeController, 'editExperience']);
$router->post('/resume/delete-experience/{id}', [$resumeController, 'deleteExperience']);
$router->get('/resume/add-education', [$resumeController, 'addEducation']);
$router->post('/resume/add-education', [$resumeController, 'addEducation']);
$router->post('/resume/delete-education/{id}', [$resumeController, 'deleteEducation']);
$router->get('/resume/add-skill', [$resumeController, 'addSkill']);
$router->post('/resume/add-skill', [$resumeController, 'addSkill']);
$router->post('/resume/delete-skill/{id}', [$resumeController, 'deleteSkill']);
$router->get('/resume/add-certification', [$resumeController, 'addCertification']);
$router->post('/resume/add-certification', [$resumeController, 'addCertification']);
$router->post('/resume/delete-certification/{id}', [$resumeController, 'deleteCertification']);
$router->post('/resume/delete-resume/{id}', [$resumeController, 'deleteResume']);
$router->post('/resume/set-default/{id}', [$resumeController, 'setDefault']);

$goalController = new \App\Controllers\GoalController();
$router->get('/goals', [$goalController, 'index']);
$router->get('/goals/create', [$goalController, 'create']);
$router->post('/goals/create', [$goalController, 'create']);
$router->get('/goals/edit/{id}', [$goalController, 'edit']);
$router->post('/goals/edit/{id}', [$goalController, 'edit']);
$router->get('/goals/view/{id}', [$goalController, 'view']);
$router->post('/goals/progress/{id}', [$goalController, 'updateProgress']);
$router->get('/goals/milestone/{id}', [$goalController, 'addMilestone']);
$router->post('/goals/milestone/{id}', [$goalController, 'addMilestone']);
$router->post('/goals/complete-milestone/{id}', [$goalController, 'completeMilestone']);
$router->post('/goals/delete/{id}', [$goalController, 'delete']);

$birthdayController = new \App\Controllers\BirthdayController();
$router->get('/birthdays', [$birthdayController, 'index']);
$router->get('/birthdays/create', [$birthdayController, 'create']);
$router->post('/birthdays/create', [$birthdayController, 'create']);
$router->get('/birthdays/edit/{id}', [$birthdayController, 'edit']);
$router->post('/birthdays/edit/{id}', [$birthdayController, 'edit']);
$router->post('/birthdays/delete/{id}', [$birthdayController, 'delete']);

$habitController = new \App\Controllers\HabitController();
$router->get('/habits', [$habitController, 'index']);
$router->get('/habits/create', [$habitController, 'create']);
$router->post('/habits/create', [$habitController, 'create']);
$router->get('/habits/edit/{id}', [$habitController, 'edit']);
$router->post('/habits/edit/{id}', [$habitController, 'edit']);
$router->post('/habits/log/{id}', [$habitController, 'logHabit']);
$router->post('/habits/toggle/{id}', [$habitController, 'toggle']);
$router->post('/habits/delete/{id}', [$habitController, 'delete']);

$socialController = new \App\Controllers\SocialLinkController();
$router->get('/social-links', [$socialController, 'index']);
$router->get('/social-links/create', [$socialController, 'create']);
$router->post('/social-links/create', [$socialController, 'create']);
$router->get('/social-links/edit/{id}', [$socialController, 'edit']);
$router->post('/social-links/edit/{id}', [$socialController, 'edit']);
$router->post('/social-links/delete/{id}', [$socialController, 'delete']);

require __DIR__ . '/../app/Controllers/WeatherController.php';
require __DIR__ . '/../app/Controllers/NewsController.php';

$weatherController = new \App\Controllers\WeatherController();
$router->get('/weather', [$weatherController, 'index']);

$newsController = new \App\Controllers\NewsController();
$router->get('/news', [$newsController, 'index']);

require __DIR__ . '/../app/Controllers/WellnessController.php';
require __DIR__ . '/../app/Controllers/InventoryController.php';
require __DIR__ . '/../app/Controllers/PantryController.php';
require __DIR__ . '/../app/Controllers/RecipeController.php';

$wellnessController = new \App\Controllers\WellnessController();
$router->get('/wellness', [$wellnessController, 'index']);
$router->get('/wellness/log', [$wellnessController, 'log']);
$router->post('/wellness/log', [$wellnessController, 'log']);
$router->get('/wellness/history', [$wellnessController, 'history']);
$router->get('/api/wellness/chart', [$wellnessController, 'getChartData']);
$router->post('/wellness/delete/{id}', [$wellnessController, 'delete']);

$inventoryController = new \App\Controllers\InventoryController();
$router->get('/inventory', [$inventoryController, 'index']);
$router->get('/inventory/create', [$inventoryController, 'create']);
$router->post('/inventory/create', [$inventoryController, 'create']);
$router->get('/inventory/edit/{id}', [$inventoryController, 'edit']);
$router->post('/inventory/edit/{id}', [$inventoryController, 'edit']);
$router->get('/inventory/view/{id}', [$inventoryController, 'view']);
$router->post('/inventory/delete/{id}', [$inventoryController, 'delete']);
$router->get('/inventory/search', [$inventoryController, 'search']);
$router->get('/inventory/export', [$inventoryController, 'exportCsv']);

$pantryController = new \App\Controllers\PantryController();
$router->get('/pantry', [$pantryController, 'index']);
$router->get('/pantry/create', [$pantryController, 'create']);
$router->post('/pantry/create', [$pantryController, 'create']);
$router->get('/pantry/edit/{id}', [$pantryController, 'edit']);
$router->post('/pantry/edit/{id}', [$pantryController, 'edit']);
$router->post('/pantry/delete/{id}', [$pantryController, 'delete']);
$router->post('/pantry/adjust/{id}', [$pantryController, 'adjustQuantity']);
$router->get('/pantry/search', [$pantryController, 'search']);

$recipeController = new \App\Controllers\RecipeController();
$router->get('/recipes', [$recipeController, 'index']);
$router->get('/recipes/create', [$recipeController, 'create']);
$router->post('/recipes/create', [$recipeController, 'create']);
$router->get('/recipes/{id}', [$recipeController, 'view']);
$router->get('/recipes/edit/{id}', [$recipeController, 'edit']);
$router->post('/recipes/edit/{id}', [$recipeController, 'edit']);
$router->post('/recipes/delete/{id}', [$recipeController, 'delete']);
$router->post('/recipes/favorite/{id}', [$recipeController, 'toggleFavorite']);
$router->post('/recipes/cook/{id}', [$recipeController, 'cook']);

require __DIR__ . '/../app/Controllers/ForecastController.php';

$forecastController = new \App\Controllers\ForecastController();
$router->get('/forecast', [$forecastController, 'index']);
$router->get('/forecast/scenarios', [$forecastController, 'getScenarios']);
$router->get('/forecast/goal', [$forecastController, 'goalProjection']);

$router->notFound(function() {
    http_response_code(404);
    include __DIR__ . '/../app/Views/errors/404.php';
});

$router->run();
