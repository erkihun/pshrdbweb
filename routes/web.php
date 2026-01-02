<?php

use App\Http\Controllers\Admin\DocumentCategoryController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\AppointmentServiceController;
use App\Http\Controllers\Admin\AppointmentSlotController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DocumentRequestController as AdminDocumentRequestController;
use App\Http\Controllers\Admin\DocumentRequestTypeController;
use App\Http\Controllers\Admin\EditorUploadController;
use App\Http\Controllers\Admin\HomepageController as AdminHomepageController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\StaffController as AdminStaffController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\ServiceFeedbackController as AdminServiceFeedbackController;
use App\Http\Controllers\Admin\AnalysisController;
use App\Http\Controllers\Admin\ContactInfoController;
use App\Http\Controllers\Admin\AlertController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\Admin\TenderController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CharterServiceController;
use App\Http\Controllers\Admin\SignageDisplayController;
use App\Http\Controllers\Admin\SignageTemplateController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PublicPostController;
use App\Http\Controllers\Public\MouController as PublicMouController;
use App\Http\Controllers\Public\OrganizationContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SearchController as AdminSearchController;
use App\Http\Controllers\Admin\SmsSettingsController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\ServiceFeedbackController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Public\ContactController as PublicContactController;
use App\Http\Controllers\Public\CitizenCharterController;
use App\Http\Controllers\Public\SignageController;
use App\Http\Controllers\Public\TenderController as PublicTenderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\OfficialMessageController;
use App\Http\Controllers\Admin\HomeSlideController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\OrgStatController;
use App\Http\Controllers\Admin\OrgStatSnapshotController;
use App\Http\Controllers\PublicServantsController;
use App\Http\Controllers\PublicServantDashboardController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\MouController as AdminMouController;

Route::get('/', HomepageController::class)
    ->name('home')
    ->middleware('public.cache');
Route::get('official-message', [OfficialMessageController::class, 'edit'])
    ->name('admin.official-message.edit');

Route::put('official-message', [OfficialMessageController::class, 'update'])
    ->name('admin.official-message.update');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', fn () => redirect()->route('admin.profile'));
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('home-slides', HomeSlideController::class)->except(['show']);
        Route::get('profile', [ProfileController::class, 'overview'])->name('profile');
        Route::get('profile/update', [ProfileController::class, 'adminEdit'])->name('profile.update.form');
        Route::get('profile/password', [ProfileController::class, 'passwordForm'])->name('profile.password.form');
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('posts', PostController::class)->middleware('permission:manage posts');
        Route::post('editor/upload', [EditorUploadController::class, 'store'])->name('editor.upload');
        Route::resource('services', AdminServiceController::class)->middleware('permission:manage services');
        Route::resource('document-categories', DocumentCategoryController::class)->middleware('permission:manage documents');
        Route::resource('documents', AdminDocumentController::class)->middleware('permission:manage documents');
        Route::get('tenders', [TenderController::class, 'index'])->name('tenders.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update'])->middleware('permission:manage users');
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::put('permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
        Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show'])->middleware('permission:view audit logs');
        Route::get('homepage', [AdminHomepageController::class, 'edit'])->name('homepage.edit')->middleware('permission:manage homepage');
        Route::put('homepage', [AdminHomepageController::class, 'update'])->name('homepage.update')->middleware('permission:manage homepage');
        Route::get('sms-settings', [SmsSettingsController::class, 'edit'])->name('sms-settings.edit')->middleware('permission:manage sms');
        Route::put('sms-settings', [SmsSettingsController::class, 'update'])->name('sms-settings.update')->middleware('permission:manage sms');
        Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit')->middleware('permission:manage settings');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update')->middleware('permission:manage settings');
        Route::get('pages', [AdminPageController::class, 'index'])->name('pages.index')->middleware('permission:manage pages');
        Route::get('pages/create', [AdminPageController::class, 'create'])->name('pages.create')->middleware('permission:manage pages');
        Route::post('pages', [AdminPageController::class, 'store'])->name('pages.store')->middleware('permission:manage pages');
        Route::match(['get', 'post'], 'media', [MediaController::class, 'index'])->name('media.index');
        Route::get('pages/{key}/edit', [AdminPageController::class, 'edit'])->name('pages.edit')->middleware('permission:manage pages');
        Route::put('pages/{key}', [AdminPageController::class, 'update'])->name('pages.update')->middleware('permission:manage pages');
        Route::resource('departments', DepartmentController::class)->except(['show'])->middleware('permission:manage staff');
        Route::resource('staff', AdminStaffController::class)->except(['show'])->middleware('permission:manage staff');
        Route::resource('tickets', AdminTicketController::class)->only(['index', 'show', 'update', 'destroy'])->middleware('permission:manage tickets');
        Route::resource('service-requests', \App\Http\Controllers\Admin\ServiceRequestController::class)->only(['index', 'show', 'update'])->middleware('permission:manage service requests');
        Route::resource('service-feedback', AdminServiceFeedbackController::class)->only(['index', 'show', 'update', 'destroy'])->middleware('permission:manage service feedback');
        Route::resource('chats', AdminChatController::class)->only(['index', 'show', 'update'])->middleware('permission:manage chat');
        Route::post('chats/{chatSession}/messages', [AdminChatController::class, 'storeMessage'])->name('admin.chats.messages.store')->middleware('permission:manage chat');
        Route::resource('document-request-types', DocumentRequestTypeController::class)->except(['show'])->middleware('permission:manage document requests');
        Route::resource('document-requests', AdminDocumentRequestController::class)->only(['index', 'show', 'update'])->middleware('permission:manage document requests');
        Route::get('document-requests/{documentRequest}/attachment', [AdminDocumentRequestController::class, 'download'])->name('admin.document-requests.attachment')->middleware('permission:manage document requests');
        Route::resource('appointment-services', AppointmentServiceController::class)->middleware('permission:manage appointments');
        Route::resource('appointment-slots', AppointmentSlotController::class)->except(['show'])->middleware('permission:manage appointments');
        Route::resource('appointments', AdminAppointmentController::class)->only(['index', 'show', 'update'])->middleware('permission:manage appointments');
        Route::get('exports', [ExportController::class, 'index'])->name('exports.index');
        Route::get('alerts', [AlertController::class, 'index'])->name('alerts.index');
        Route::resource('tenders', TenderController::class)->except(['show'])->middleware('permission:manage tenders');
        Route::resource('organizations', OrganizationController::class);
        Route::resource('partners', PartnerController::class);
        Route::resource('mous', AdminMouController::class);
        Route::get('organizations/{organization}/stats', [OrgStatController::class, 'index'])->name('organizations.stats.index');
        Route::post('organizations/{organization}/stats', [OrgStatController::class, 'store'])->name('organizations.stats.store');
        Route::get('organizations/{organization}/stats/{stat}/edit', [OrgStatController::class, 'edit'])->name('organizations.stats.edit');
        Route::put('organizations/{organization}/stats/{stat}', [OrgStatController::class, 'update'])->name('organizations.stats.update');
        Route::delete('organizations/{organization}/stats/{stat}', [OrgStatController::class, 'destroy'])->name('organizations.stats.destroy');
        Route::post('organizations/{organization}/snapshots', [OrgStatSnapshotController::class, 'store'])->name('organizations.snapshots.store');
        Route::get('organizations/{organization}/snapshots/{snapshot}', [OrgStatSnapshotController::class, 'show'])->name('organizations.snapshots.show');
        Route::get('organizations/{organization}/charts', [App\Http\Controllers\Admin\OrganizationChartsController::class, 'index'])->name('organizations.charts');
        Route::get('subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
        Route::resource('contact-info', ContactInfoController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
            ->middleware('permission:manage settings');
        Route::resource('charter-services', CharterServiceController::class)->middleware('permission:manage services');
        Route::middleware('ensure.manage.signage')
            ->prefix('signage')
            ->name('signage.')
            ->group(function () {
                Route::resource('templates', SignageTemplateController::class);
                Route::resource('displays', SignageDisplayController::class);
            });
        Route::get('analysis', [AnalysisController::class, 'index'])->name('analysis.index');
        Route::get('search', [AdminSearchController::class, 'index'])->name('search');
    });

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');
Route::post('/services/{slug}/feedback', [ServiceFeedbackController::class, 'store'])->name('services.feedback.store')->middleware('throttle:service_feedback');
Route::get('/downloads', [DownloadController::class, 'index'])->name('downloads.index');
Route::get('/downloads/{slug}', [DownloadController::class, 'show'])->name('downloads.show');
Route::get('/downloads/{slug}/file', [DownloadController::class, 'file'])->name('downloads.file');
Route::get('/document-requests', [DocumentRequestController::class, 'index'])->name('document-requests.index');
Route::get('/document-requests/{slug}', [DocumentRequestController::class, 'show'])->name('document-requests.show');
Route::post('/document-requests/{slug}', [DocumentRequestController::class, 'store'])->name('document-requests.store')->middleware('throttle:document_request_submit');
Route::get('/document-requests/track', [DocumentRequestController::class, 'trackForm'])->name('document-requests.track.form');
Route::post('/document-requests/track', [DocumentRequestController::class, 'trackSubmit'])->name('document-requests.track.submit')->middleware('throttle:document_request_track');
Route::post('/newsletter/subscribe', [NewsletterSubscriptionController::class, 'store'])->name('newsletter.subscribe');
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
Route::get('/appointments/track', [AppointmentController::class, 'trackForm'])->name('appointments.track.form');
Route::post('/appointments/track', [AppointmentController::class, 'trackSubmit'])->name('appointments.track.submit')->middleware('throttle:appointment_track');
Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
Route::get('/news', [PublicPostController::class, 'newsIndex'])->name('news.index');
Route::get('/news/{slug}', [PublicPostController::class, 'newsShow'])->name('news.show');
Route::get('/announcements', [PublicPostController::class, 'announcementsIndex'])->name('announcements.index');
Route::get('/announcements/{slug}', [PublicPostController::class, 'announcementsShow'])->name('announcements.show');
Route::get('/mous', [PublicMouController::class, 'index'])->name('public.mous.index');
Route::get('/mous/{identifier}', [PublicMouController::class, 'show'])->name('public.mous.show');
Route::get('/tenders', [PublicTenderController::class, 'index'])->name('tenders.index');
Route::get('/tenders/{tender:slug}', [PublicTenderController::class, 'show'])->name('tenders.show');
Route::get('/about', [PageController::class, 'show'])
    ->defaults('key', 'about')
    ->name('pages.about')
    ->middleware('public.cache');
Route::get('/organization/mission-vision-values', [PageController::class, 'show'])->defaults('key', 'mission_vision_values')->name('pages.mission');
Route::get('/organization/leadership', [PageController::class, 'show'])->defaults('key', 'leadership')->name('pages.leadership');
Route::get('/organization/structure', [PageController::class, 'show'])->defaults('key', 'structure')->name('pages.structure');
Route::get('/organization/history', [PageController::class, 'show'])->defaults('key', 'history')->name('pages.history');
Route::get('/organization/contact', [OrganizationContactController::class, 'index'])
    ->name('organization.contact')
    ->middleware('public.cache');
Route::get('/pages/{key}', [PageController::class, 'show'])->name('pages.show');
Route::get('/leadership', [\App\Http\Controllers\StaffController::class, 'leadership'])->name('staff.leadership');
Route::get('/staff', [\App\Http\Controllers\StaffController::class, 'index'])
    ->name('staff.index')
    ->middleware('public.cache');
Route::get('/staff/{staff}', [\App\Http\Controllers\StaffController::class, 'show'])->name('staff.show');
Route::get('/contact', [PublicContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store')->middleware('throttle:contact');
Route::get('/request-service', [ServiceRequestController::class, 'create'])->name('service-requests.create');
Route::post('/request-service', [ServiceRequestController::class, 'store'])->name('service-requests.store')->middleware('throttle:service_request_submit');
Route::get('/track', [ServiceRequestController::class, 'trackForm'])->name('service-requests.track.form');
Route::post('/track', [ServiceRequestController::class, 'trackSubmit'])->name('service-requests.track.submit')->middleware('throttle:service_request_track');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::post('/chat/start', [ChatController::class, 'start'])->name('chat.start')->middleware('throttle:chat_start');
Route::post('/chat/{chatSession}/message', [ChatController::class, 'message'])->name('chat.message')->middleware('throttle:chat_message');
Route::get('/chat/status', [ChatController::class, 'status'])->name('chat.status');
Route::get('/appointments/{appointmentService}', [AppointmentController::class, 'show'])->name('appointments.show');
Route::post('/appointments/{appointmentService}/book', [AppointmentController::class, 'book'])->name('appointments.book')->middleware('throttle:appointment_book');
Route::post('/appointments/{reference}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel')->middleware('throttle:appointment_cancel');
Route::get('/public-servants', [PublicServantsController::class, 'index'])->name('public-servants.index');
Route::get('/public-servants-dashboard', [PublicServantDashboardController::class, 'index'])->name('public-servants.dashboard');
Route::get('/citizen-charter', [CitizenCharterController::class, 'index'])
    ->name('citizen-charter.index')
    ->middleware('public.cache');
Route::get('/citizen-charter/{department:slug}', [CitizenCharterController::class, 'department'])->name('citizen-charter.department');
Route::get('/citizen-charter/{department:slug}/{service:slug}', [CitizenCharterController::class, 'service'])->name('citizen-charter.service');
Route::get('/signage/{signage_display:slug}', [SignageController::class, 'show'])->name('signage.show');
Route::get('/health', function () {
    try {
        DB::connection()->getPdo()->query('select 1');
        return response()->json(['status' => 'ok', 'db' => 'ok']);
    } catch (\Throwable $e) {
        return response()->json(['status' => 'error', 'db' => 'error'], 500);
    }
})->name('health');
Route::post('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

require __DIR__.'/auth.php';
