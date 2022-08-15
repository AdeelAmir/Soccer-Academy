<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LevelController;
use \App\Http\Controllers\CategoryController;
use App\Http\Controllers\PlayerPositionsController;
use App\Http\Controllers\MagicNumbersController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PlayerLocationsController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\TrainingRoomController;
use App\Http\Controllers\FaqsController;
use App\Http\Controllers\MembershipController;

//Command Routes
Route::get('clear-cache', function () {
    Artisan::call('storage:link');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    //Create storage link on hosting
    $exitCode = Artisan::call('storage:link', []);
    echo $exitCode; // 0 exit code for no errors.
});

Route::get('/password/update/{Email}/{OldPassword}/{NewPassword}', function ($Email, $OldPassword, $NewPassword) {
    $User = \Illuminate\Support\Facades\DB::table('users')
        ->where('email', '=', $Email)
        ->get();
    if (sizeof($User) > 0) {
        if (\Illuminate\Support\Facades\Hash::check($OldPassword, $User[0]->password)) {
            $NewPassword = bcrypt($NewPassword);
            \Illuminate\Support\Facades\DB::table('users')
                ->where('email', '=', $Email)
                ->update([
                    'password' => $NewPassword
                ]);
            echo 'Password updated';
        } else {
            echo 'Incorrect Old Password!';
        }
    } else {
        echo 'User not found';
    }
    exit();
});

/* Front Website Routes*/
Route::get('/', [HomeController::class, 'index'])->name('HomeRoute');
Route::get('/registration', [HomeController::class, 'lead'])->name('createLeadRoute');
/*Dashboard Routes*/
Auth::routes();
//Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('dashboard', [DashboardController::class, 'TraineeDashboard'])->name('dashboard');
Route::get('dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
Route::post('dashboard/profile/update', [DashboardController::class, 'update'])->name('profile.update');
Route::get('dashboard/registration/complete', [DashboardController::class, 'completeRegistration'])->name('dashboard.registration.complete');
Route::post('dashboard/stripe/order/create', [DashboardController::class, 'stripeOrderCreate'])->name('dashboard.stripe.order.create');
Route::get('dashboard/stripe/order/finish', [DashboardController::class, 'stripeOrderFinish'])->name('dashboard.stripe.order.finish');
Route::get('dashboard/registration/update', [DashboardController::class, 'updateRegistration'])->name('dashboard.registration.update');
Route::post('dashboard/registration/update/lead', [DashboardController::class, 'updateLeadRegistration'])->name('dashboard.registration.update.lead');
Route::get('dashboard/logout', [DashboardController::class, 'logout'])->name('dashboard.logout');

/*Memberships*/
Route::get('dashboard/memberships', [MembershipController::class, 'index'])->name('dashboard.memberships');
Route::get('dashboard/memberships/new', [MembershipController::class, 'newMembership'])->name('dashboard.memberships.new');
Route::post('dashboard/memberships/store', [MembershipController::class, 'storeMembership'])->name('dashboard.memberships.store');
Route::get('dashboard/memberships/finish', [MembershipController::class, 'finishMembership'])->name('dashboard.memberships.finish');

/* Dashboard Graphs Routes */
Route::post('dashboard/graph/earnings', [DashboardController::class, 'LoadEarningLineGraphData'])->name('dashboard.graph.earnings');
Route::post('dashboard/parent/expenses', [DashboardController::class, 'LoadParentExpenses'])->name('dashboard.parent.expenses');
Route::post('dashboard/parent/evaluationreport', [DashboardController::class, 'LoadParentEvaluationReport'])->name('dashboard.parent.evaluationreport');
Route::post('dashboard/player/evaluationreport', [DashboardController::class, 'LoadPlayerEvaluationReport'])->name('dashboard.player.evaluationreport');
Route::post('dashboard/coach/allplayer', [DashboardController::class, 'LoadCoachAllPlayer'])->name('dashboard.coach.allplayer');

/*Users*/
Route::get('users', [UsersController::class, 'index'])->name('users');
Route::get('users/add/{Role}', [UsersController::class, 'add'])->name('users.add');
Route::post('users/store', [UsersController::class, 'store'])->name('users.store');
Route::post('users/load', [UsersController::class, 'load'])->name('users.load');
Route::post('users/delete', [UsersController::class, 'delete'])->name('users.delete');
Route::post('users/ban', [UsersController::class, 'ban'])->name('users.ban');
Route::post('users/active', [UsersController::class, 'active'])->name('users.active');
Route::post('users/ban-active', [UsersController::class, 'BanActive'])->name('users.ban-active');
Route::get('users/edit/{id}', [UsersController::class, 'edit'])->name('users.edit');
Route::post('users/update', [UsersController::class, 'update'])->name('users.update');
Route::post('users/fetch/category', [UsersController::class, 'fetchCategory'])->name('users.fetch.category');
Route::post('users/fetch/parentaddressinfo', [UsersController::class, 'fetchParentAddressInfo'])->name('users.fetch.parentaddressinfo');
Route::get('users/power/{id}/{type}', [UsersController::class, 'powerType'])->name('users.power-type');
Route::post('users/power/user/update', [UsersController::class, 'userPowerUserUpdate'])->name('users.power.user.update');
Route::post('users/power/feature/update', [UsersController::class, 'userPowerFeatureUpdate'])->name('users.power.feature.update');
Route::post('users/activity/all', [UsersController::class, 'userActivitiesAll'])->name('users.activity.all');
Route::post('users/password/update', [UsersController::class, 'updatePassword'])->name('users.password.update');
Route::post('users/document/verify', [UsersController::class, 'updateDocumentVerificationStatus'])->name('users.document.verify');
Route::post('users/documents/status/reset', [HomeController::class, 'ResetUserDocumentStatus'])->name('users.documents.status.reset');

/* Configuration */
Route::get('configuration', [DashboardController::class, 'configuration'])->name('configuration');
// Configuration - Level
Route::get('configuration/levels', [LevelController::class, 'index'])->name('configuration.level');
Route::post('configuration/levels/load', [LevelController::class, 'load'])->name('configuration.level.load');
Route::post('configuration/levels/store', [LevelController::class, 'store'])->name('configuration.level.store');
Route::post('configuration/levels/delete', [LevelController::class, 'delete'])->name('configuration.level.delete');
Route::post('configuration/levels/update', [LevelController::class, 'update'])->name('configuration.level.update');
// Configuration - Category
Route::get('configuration/categories', [CategoryController::class, 'index'])->name('configuration.categories');
Route::post('configuration/categories/load', [CategoryController::class, 'load'])->name('configuration.categories.load');
Route::post('configuration/categories/store', [CategoryController::class, 'store'])->name('configuration.categories.store');
Route::post('configuration/categories/delete', [CategoryController::class, 'delete'])->name('configuration.categories.delete');
Route::post('configuration/categories/update', [CategoryController::class, 'update'])->name('configuration.categories.update');
Route::post('configuration/categories/update/status', [CategoryController::class, 'updateStatus'])->name('configuration.categories.update.status');
// Configuration - Player Position
Route::get('configuration/player-position', [PlayerPositionsController::class, 'index'])->name('configuration.player-position');
Route::post('configuration/player-position/load', [PlayerPositionsController::class, 'load'])->name('configuration.player-position.load');
Route::post('configuration/player-position/store', [PlayerPositionsController::class, 'store'])->name('configuration.player-position.store');
Route::post('configuration/player-position/delete', [PlayerPositionsController::class, 'delete'])->name('configuration.player-position.delete');
Route::post('configuration/player-position/update', [PlayerPositionsController::class, 'update'])->name('configuration.player-position.update');
// Configuration - Magic Numbers
Route::get('configuration/magic-numbers', [MagicNumbersController::class, 'index'])->name('configuration.magic-numbers');
Route::post('configuration/magic-numbers/update', [MagicNumbersController::class, 'update'])->name('configuration.magic-numbers.update');
/* Locations */
Route::get('locations', [PlayerLocationsController::class, 'index'])->name('locations');
Route::get('locations/add', [PlayerLocationsController::class, 'add'])->name('locations.add');
Route::post('locations/store', [PlayerLocationsController::class, 'store'])->name('locations.store');
Route::post('locations/load', [PlayerLocationsController::class, 'load'])->name('locations.load');
Route::post('locations/delete', [PlayerLocationsController::class, 'delete'])->name('locations.delete');
Route::get('locations/edit/{id}', [PlayerLocationsController::class, 'edit'])->name('locations.edit');
Route::post('locations/update', [PlayerLocationsController::class, 'update'])->name('locations.update');
Route::post('locations/update/status', [PlayerLocationsController::class, 'updateStatus'])->name('locations.update.status');
/* Classes */
Route::get('classes', [ClassesController::class, 'index'])->name('classes');
Route::get('classes/add', [ClassesController::class, 'add'])->name('classes.add');
Route::post('classes/store', [ClassesController::class, 'store'])->name('classes.store');
Route::post('classes/load', [ClassesController::class, 'load'])->name('classes.load');
Route::post('classes/delete', [ClassesController::class, 'delete'])->name('classes.delete');
Route::get('classes/edit/{id}', [ClassesController::class, 'edit'])->name('classes.edit');
Route::post('classes/update', [ClassesController::class, 'update'])->name('classes.update');
Route::post('classes/update/status', [ClassesController::class, 'updateStatus'])->name('classes.update.status');

Route::get('classes/attendance/{id}', [ClassesController::class, 'attendence'])->name('classes.attendence');
Route::post('classes/attendance', [ClassesController::class, 'getClassAttendence'])->name('classes.month.attendence');
Route::get('classes/attendance/add/{id}', [ClassesController::class, 'AddAttendence'])->name('classes.attendence.add');
Route::post('classes/attendance/edit', [ClassesController::class, 'EditAttendence'])->name('classes.attendence.edit');
Route::post('classes/attendance/update', [ClassesController::class, 'UpdateAttendence'])->name('classes.attendence.update');

Route::post('classes/assign/player', [ClassesController::class, 'GetClassPlayerList'])->name('classes.assign.player');
Route::post('classes/assign/player/update', [ClassesController::class, 'UpdateClassPlayers'])->name('classes.assign.player.update');
Route::post('classes/announcement/player', [ClassesController::class, 'addPlayerAnnouncement'])->name('classes.announcement.player');
// Evaluation
Route::get('classes/evaluation/{ClassId}', [ClassesController::class, 'openClassEvaluation'])->name('classes.evaluation');
Route::post('classes/evaluation/player', [ClassesController::class, 'GetEvaluationClassPlayerList'])->name('classes.evaluation.player');
Route::post('classes/players/load', [ClassesController::class, 'loadClassPlayers'])->name('classes.players.load');
Route::post('classes/evaluation/add', [ClassesController::class, 'addClassPlayerEvaluation'])->name('classes.evaluation.add');
Route::post('classes/evaluation/store', [ClassesController::class, 'storeClassPlayerEvaluation'])->name('classes.evaluation.store');
Route::get('classes/evaluation/edit/{EvaluationId}', [ClassesController::class, 'editClassPlayerEvaluation'])->name('classes.evaluation.edit');
Route::post('classes/evaluation/update', [ClassesController::class, 'updateClassPlayerEvaluation'])->name('classes.evaluation.update');
Route::post('classes/evaluation/delete', [ClassesController::class, 'deleteClassPlayerEvaluation'])->name('classes.evaluation.delete');
Route::get('classes/evaluation/pdf/{EvaluationId}', [ClassesController::class, 'classPlayerEvaluationPDF'])->name('classes.evaluation.pdf');
/* Billing */
Route::get('billing', [DashboardController::class, 'billing'])->name('billing');

// Billing - Packages
Route::get('billing/packages', [PackagesController::class, 'index'])->name('packages');
Route::get('billing/packages/add', [PackagesController::class, 'add'])->name('packages.add');
Route::post('billing/packages/store', [PackagesController::class, 'store'])->name('packages.store');
Route::post('billing/packages/load', [PackagesController::class, 'load'])->name('packages.load');
Route::post('billing/packages/delete', [PackagesController::class, 'delete'])->name('packages.delete');
Route::get('billing/packages/edit/{id}', [PackagesController::class, 'edit'])->name('packages.edit');
Route::post('billing/packages/update', [PackagesController::class, 'update'])->name('packages.update');

// Billing - Expenses
Route::get('billing/expenses', [ExpenseController::class, 'AdminAllExpense'])->name('billing.expenses');
Route::get('billing/expenses/add', [ExpenseController::class, 'AdminAddNewExpense'])->name('billing.expenses.add');
Route::post('billing/expenses/store', [ExpenseController::class, 'AdminExpenseStore'])->name('billing.expenses.store');
Route::post('billing/expenses/load', [ExpenseController::class, 'LoadAdminAllExpense'])->name('billing.expenses.load');
Route::post('billing/expenses/delete', [ExpenseController::class, 'AdminDeleteExpense'])->name('billing.expenses.delete');
Route::get('billing/expenses/edit/{id}', [ExpenseController::class, 'AdminEditExpense'])->name('billing.expenses.edit');
Route::post('billing/expenses/update', [ExpenseController::class, 'AdminUpdateExpense'])->name('billing.expenses.update');

// Billing - Invoices
Route::get('billing/invoices', [InvoiceController::class, 'AdminAllInvoices'])->name('billing.invoices');
Route::get('billing/invoices/add', [InvoiceController::class, 'AdminAddNewInvoice'])->name('billing.invoices.add');
Route::post('billing/invoices/store', [InvoiceController::class, 'AdminInvoiceStore'])->name('billing.invoices.store');
Route::post('billing/invoices/load', [InvoiceController::class, 'LoadAdminAllInvoices'])->name('billing.invoices.load');
Route::post('billing/invoices/delete', [InvoiceController::class, 'AdminDeleteInvoice'])->name('billing.invoices.delete');
Route::get('billing/invoices/edit/{id}', [InvoiceController::class, 'AdminEditInvoice'])->name('billing.invoices.edit');
Route::post('billing/invoices/update', [InvoiceController::class, 'AdminUpdateInvoice'])->name('billing.invoices.update');
Route::get('billing/invoices/pdf/{id}', [InvoiceController::class, 'AdminInvoicePDF'])->name('billing.invoices.pdf');
Route::get('billing/invoices/email/{id}', [HomeController::class, 'AdminInvoiceEmail'])->name('billing.invoices.email');
Route::get('billing/invoices/payment-page/{id}', [HomeController::class, 'InvoicePayment'])->name('billing.invoices.payment-page');
Route::post('billing/invoices/payment-page/stripe/setup', [HomeController::class, 'InvoiceStripeSetup'])->name('billing.invoices.payment-page.stripe.setup');
Route::post('billing/invoices/payment-page/stripe/create', [HomeController::class, 'InvoiceStripeCreate'])->name('billing.invoices.payment-page.stripe.create');
Route::get('invoices/process', [HomeController::class, 'InvoicePaymentProcess'])->name('billing.invoices.payment-page.process');
Route::get('billing/invoices/payment/{status}', [HomeController::class, 'InvoicePaymentFinish'])->name('billing.invoices.payment-page.complete');

// Billing - Transactions
Route::get('billing/transactions', [DashboardController::class, 'AdminAllTransactions'])->name('billing.transactions');
Route::post('billing/transactions/load', [DashboardController::class, 'AdminAllTransactionsLoad'])->name('billing.transactions.load');

// Billing - Subscriptions
Route::get('billing/memberships', [DashboardController::class, 'AdminAllSubscriptions'])->name('billing.subscriptions');
Route::post('billing/memberships/load', [DashboardController::class, 'AdminAllSubscriptionsLoad'])->name('billing.subscriptions.load');
Route::post('billing/memberships/activate', [DashboardController::class, 'AdminAllSubscriptionsActivate'])->name('billing.subscriptions.activate');
Route::post('billing/memberships/suspend', [DashboardController::class, 'AdminAllSubscriptionsSuspend'])->name('billing.subscriptions.suspend');
Route::get('billing/memberships/view/{Id}', [DashboardController::class, 'AdminAllSubscriptionsView'])->name('billing.subscriptions.view');
Route::post('billing/memberships/hold', [DashboardController::class, 'AdminAllSubscriptionsHold'])->name('billing.subscriptions.hold');
Route::post('billing/memberships/cancel', [DashboardController::class, 'AdminAllSubscriptionsCancel'])->name('billing.subscriptions.cancel');

// Billing - Coupons
Route::get('billing/coupons', [DashboardController::class, 'AdminAllCoupons'])->name('billing.coupons');
Route::post('billing/coupons/load', [DashboardController::class, 'AdminAllCouponsLoad'])->name('billing.coupons.load');
Route::get('billing/coupons/add', [DashboardController::class, 'AdminAllCouponsAdd'])->name('billing.coupons.add');
Route::post('billing/coupons/store', [DashboardController::class, 'AdminAllCouponsStore'])->name('billing.coupons.store');
Route::post('billing/coupons/delete', [DashboardController::class, 'AdminAllCouponsDelete'])->name('billing.coupons.delete');
Route::get('billing/coupons/edit/{Id}', [DashboardController::class, 'AdminAllCouponsEdit'])->name('billing.coupons.edit');
Route::post('billing/coupons/update', [DashboardController::class, 'AdminAllCouponsUpdate'])->name('billing.coupons.update');

// Announcement
Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements');
Route::get('announcement/add', [AnnouncementController::class, 'add'])->name('announcements.add');
Route::post('announcement/store', [AnnouncementController::class, 'store'])->name('announcements.store');
Route::post('announcements/all', [AnnouncementController::class, 'load'])->name('announcements.all');
Route::post('announcements/active', [AnnouncementController::class, 'active'])->name('announcements.active');
Route::post('announcements/deactive', [AnnouncementController::class, 'deactive'])->name('announcements.deactive');
Route::get('announcement/edit/{AnnouncementId}', [AnnouncementController::class, 'edit'])->name('announcements.edit');
Route::post('announcements/update', [AnnouncementController::class, 'update'])->name('announcements.update');
Route::post('announcements/delete', [AnnouncementController::class, 'delete'])->name('announcements.delete');
Route::get('announcement/details/{AnnouncementId}', [AnnouncementController::class, 'viewDetails'])->name('announcements.details');
Route::get('announcement/details/all', [AnnouncementController::class, 'loadAllAnnouncementDetails'])->name('announcements.details.all');
Route::post('announcement/read', [AnnouncementController::class, 'read'])->name('announcements.read');
// Broadcast
Route::post('broadcast/send', [BroadcastController::class, 'send'])->name('broadcasts.send');
Route::post('broadcast/all', [BroadcastController::class, 'getUserUnreadBroadcast'])->name('broadcasts.all');
Route::post('broadcast/status/update', [BroadcastController::class, 'updateReadStatus'])->name('broadcasts.status.update');
// Parent Expenses and Reports
Route::get('expenses', [ExpenseController::class, 'ParentExpense'])->name('parent.expenses');
Route::get('reports', [ClassesController::class, 'openParentReports'])->name('parent.reports');
// Leads
Route::get('leads', [LeadController::class, 'index'])->name('leads');
Route::get('leads/add', [LeadController::class, 'add'])->name('leads.add');
Route::post('leads/store', [LeadController::class, 'store'])->name('leads.store');
Route::post('leads/update', [LeadController::class, 'update'])->name('leads.update');
Route::post('leads/load', [LeadController::class, 'load'])->name('leads.load');
Route::post('leads/comments/load', [LeadController::class, 'loadLeadComments'])->name('leads.comments.load');
Route::post('leads/comments/store', [LeadController::class, 'saveComment'])->name('leads.comments.store');
Route::post('leads/delete', [LeadController::class, 'delete'])->name('leads.delete');
Route::get('leads/edit/{LeadId}', [LeadController::class, 'edit'])->name('leads.edit');
Route::post('leads/save', [LeadController::class, 'save'])->name('leads.save');
Route::post('leads/update/status', [LeadController::class, 'updateLeadStatus'])->name('leads.update.status');
Route::post('leads/freeclass/days', [HomeController::class, 'getFreeClassDays'])->name('leads.freeclass.days');
Route::post('leads/freeclass/timing', [HomeController::class, 'getFreeClassTiming'])->name('leads.freeclass.timing');
/* Common */
Route::post('/load/cities', [HomeController::class, 'LoadCities'])->name('common.load.cities');

// SITE ROUTES
Route::post('player/package/fetch', [HomeController::class, 'fetchPlayerPackage'])->name('player.package.fetch');
Route::post('stripe/setup', [HomeController::class, 'stripeSetup'])->name('stripe.setup');
Route::post('stripe/order/create', [HomeController::class, 'stripeOrderCreate'])->name('stripe.order.create');
Route::post('stripe/order/coupon/apply', [HomeController::class, 'stripeOrderCouponApply'])->name('stripe.order.coupon.apply');
Route::get('stripe/order/finish', [HomeController::class, 'stripeOrderFinish'])->name('stripe.order.finish');
Route::post('/email/unique', [HomeController::class, 'CheckUniqueEmail'])->name('email.unique');
Route::post('parent/information/store', [HomeController::class, 'store'])->name('parent.information.store');
Route::post('parent/information/update', [HomeController::class, 'update'])->name('parent.information.update');

/*Route::get('stripe/customer/payment-methods', [HomeController::class, 'GetStripeCustomerPaymentMethods'])->name('stripe.customer.payment-methods');*/
Route::get('stripe/capture/payment', [HomeController::class, 'StripeChargeASavedCard'])->name('stripe.capture.payment');

Route::get('stripe/product/create', [HomeController::class, 'stripeProductCreate'])->name('stripe.product.create');
Route::get('stripe/price/create', [HomeController::class, 'stripePriceCreate'])->name('stripe.price.create');
Route::get('stripe/subscription/create', [HomeController::class, 'stripeSubscriptionCreate'])->name('stripe.subscription.create');

Route::get('cron-job', [HomeController::class, 'ManageOrderInvoiceCronJob'])->name('cron-job');
Route::get('cron-job/invoices/email', [HomeController::class, 'SendInvoiceCronJob'])->name('cron-job.invoices.email');

//Training Room Routes
Route::get('training-room', [TrainingRoomController::class, 'index'])->name('trainingRoom');
Route::get('training-room/folders/{id}', [TrainingRoomController::class, 'OpenTrainingRoomFolders'])->name('training.folders');
Route::get('training-room/folder/add/{id}', [TrainingRoomController::class, 'AddTrainingRoomFolder']);
Route::post('training-room/folder/store', [TrainingRoomController::class, 'StoreTrainingRoomFolder'])->name('folder.store');
Route::post('training-room/folders/all', [TrainingRoomController::class, 'LoadAllTrainingRoomFolders'])->name('trainingRoom.load');
Route::post('training-room/folder/delete', [TrainingRoomController::class, 'DeleteTrainingRoomFolder'])->name('trainingFolder.delete');
Route::post('training-room/folder/copy', [TrainingRoomController::class, 'CopyTrainingRoomFolder'])->name('trainingRoomFolder.copy');
Route::post('training-room/folders/get', [TrainingRoomController::class, 'GetTrainingRoomFolders']);
Route::get('training-room/folder/order/up/{Id}/{Role}', [TrainingRoomController::class, 'TrainingRoomFolderOrderUp']);
Route::get('training-room/folder/order/down/{Id}/{Role}', [TrainingRoomController::class, 'TrainingRoomFolderOrderDown']);
Route::get('training-room/folder/edit/{folderId}/{RoleId}', [TrainingRoomController::class, 'EditTrainingRoomFolder']);
Route::post('training-room/folder/update', [TrainingRoomController::class, 'UpdateTrainingRoomFolder'])->name('trainingRoomFolder.update');
Route::get('training-room/folder/details/{id}/{RoleId}', [TrainingRoomController::class, 'OpenTrainingRoomDetails']);
//Training-Room-Details
Route::post('training-room/all',  [TrainingRoomController::class, 'LoadAllTrainingRoom'])->name('load.all');
Route::post('training-room/delete', [TrainingRoomController::class, 'TrainingRoomDelete'])->name('training.detail.delete');
Route::post('training-room/copy', [TrainingRoomController::class, 'TrainingRoomCopy']);
Route::get('training-room/order/up/{Id}/{FolderId}/{Role}', [TrainingRoomController::class, 'TrainingRoomOrderUp']);
Route::get('training-room/order/down/{Id}/{FolderId}/{Role}',  [TrainingRoomController::class, 'TrainingRoomOrderDown']);
//Video
Route::get('training-room/video/add/{FolderId}/{RoleId}', [TrainingRoomController::class, 'AddTrainingRoomVideo']);
Route::post('training-room/video/store', [TrainingRoomController::class, 'TrainingRoomVideoStore'])->name('trainingRoomVideo.store');
Route::get('training-room/video/edit/{VideoId}/{FolderId}/{RoleId}', [TrainingRoomController::class, 'EditTrainingRoomVideo']);
Route::post('training-room/video/update', [TrainingRoomController::class, 'TrainingRoomVideoUpdate']);
//Articles
Route::get('training-room/article/add/{FolderId}/{RoleId}', [TrainingRoomController::class, 'AddTrainingRoomArticle']);
Route::post('training-room/article/store', [TrainingRoomController::class, 'TrainingRoomArticleStore']);
Route::get('training-room/article/edit/{ArticleId}/{FolderId}/{RoleId}', [TrainingRoomController::class, 'EditTrainingRoomArticle']);
Route::post('training-room/article/update', [TrainingRoomController::class, 'TrainingRoomArticleUpdate']);
//Quiz
Route::get('training-room/quiz/add/{FolderId}/{RoleId}', [TrainingRoomController::class, 'AddTrainingRoomQuiz']);
Route::post('training-room/quiz/store',  [TrainingRoomController::class, 'TrainingRoomQuizStore']);
Route::get('training-room/quiz/edit/{ArticleId}/{FolderId}/{RoleId}', [TrainingRoomController::class, 'EditTrainingRoomQuiz']);
Route::post('training-room/quiz/update', [TrainingRoomController::class, 'TrainingRoomQuizUpdate']);
//FAQ'S
Route::get('training-room/faqs',  [FaqsController::class, 'index'])->name('faq');
Route::post('training-room/faqs/all', [FaqsController::class, 'load']);
Route::post('training-room/faqs/add', [FaqsController::class, 'store']);
Route::post('training-room/faqs/delete', [FaqsController::class, 'delete']);
Route::post('training-room/faqs/update', [FaqsController::class, 'update']);
Route::post('faq/details', [FaqsController::class, 'getFaqDetails']);
Route::get('training/faqs', [FaqsController::class, 'viewFaqs'])->name('view.faq');
Route::post('training/faqs/search', [FaqsController::class, 'search']);
//Training Room
Route::get('training', [DashboardController::class, 'Training'])->name('training');
Route::get('training/course/{CourseId}', [DashboardController::class, 'TrainingCourse']);
Route::post('training/course/search', [TrainingRoomController::class, 'SearchCourse']);
Route::post('training/assignment/complete', [TrainingRoomController::class, 'MarkAssignmentAsComplete']);
Route::post('training/faqs/search', [FaqsController::class, 'Search']);
