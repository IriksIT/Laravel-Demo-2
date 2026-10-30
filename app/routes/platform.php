<?php

declare(strict_types=1);

use App\Orchid\Screens\Client\ClientEditScreen;
use App\Orchid\Screens\Client\ClientListScreen;
use App\Orchid\Screens\Invoice\InvoiceListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Project\ProjectEditScreen;
use App\Orchid\Screens\Project\ProjectListScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Task\TaskEditScreen;
use App\Orchid\Screens\Task\TaskListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

// Dashboard
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Klanten
Route::screen('clients/create', ClientEditScreen::class)
    ->name('platform.clients.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.clients')
        ->push('Nieuw', route('platform.clients.create')));

Route::screen('clients/{client}/edit', ClientEditScreen::class)
    ->name('platform.clients.edit')
    ->breadcrumbs(fn (Trail $trail, $client) => $trail
        ->parent('platform.clients')
        ->push($client->company, route('platform.clients.edit', $client)));

Route::screen('clients', ClientListScreen::class)
    ->name('platform.clients')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Klanten', route('platform.clients')));

// Projecten
Route::screen('projects/create', ProjectEditScreen::class)
    ->name('platform.projects.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.projects')
        ->push('Nieuw', route('platform.projects.create')));

Route::screen('projects/{project}/edit', ProjectEditScreen::class)
    ->name('platform.projects.edit')
    ->breadcrumbs(fn (Trail $trail, $project) => $trail
        ->parent('platform.projects')
        ->push($project->name, route('platform.projects.edit', $project)));

Route::screen('projects', ProjectListScreen::class)
    ->name('platform.projects')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Projecten', route('platform.projects')));

// Taken
Route::screen('tasks/create', TaskEditScreen::class)
    ->name('platform.tasks.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.tasks')
        ->push('Nieuw', route('platform.tasks.create')));

Route::screen('tasks/{task}/edit', TaskEditScreen::class)
    ->name('platform.tasks.edit')
    ->breadcrumbs(fn (Trail $trail, $task) => $trail
        ->parent('platform.tasks')
        ->push($task->title, route('platform.tasks.edit', $task)));

Route::screen('tasks', TaskListScreen::class)
    ->name('platform.tasks')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Taken', route('platform.tasks')));

// Facturen
Route::screen('invoices', InvoiceListScreen::class)
    ->name('platform.invoices')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Facturen', route('platform.invoices')));

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));
