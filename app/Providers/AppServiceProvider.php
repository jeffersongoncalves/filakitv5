<?php

namespace App\Providers;

use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\AppPanelProvider;
use App\Providers\Filament\GuestPanelProvider;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Notifications;
use Filament\Pages;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentView;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;

use function view;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (config('filakit.admin_panel_enabled', false)) {
            $this->app->register(AdminPanelProvider::class);
        }
        if (config('filakit.app_panel_enabled', false)) {
            $this->app->register(AppPanelProvider::class);
        }
        if (config('filakit.guest_panel_enabled', false)) {
            $this->app->register(GuestPanelProvider::class);
        }
        if (config('filakit.favicon.enabled')) {
            FilamentView::registerRenderHook(PanelsRenderHook::HEAD_START, fn (): View => view('components.favicon'));
        }
        FilamentView::registerRenderHook(PanelsRenderHook::HEAD_START, fn (): View => view('components.js-md5'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureSchema();
        $this->configureForms();
        $this->configureInfolists();
        $this->configurePages();
        $this->configureTables();
    }

    private function configureActions(): void
    {
        Actions\ActionGroup::configureUsing(function (Actions\ActionGroup $action) {
            return $action->icon(Heroicon::EllipsisVertical);
        });

        Actions\Action::configureUsing(function (Actions\Action $action) {
            return $action
                ->translateLabel()
                ->modalWidth(Width::Medium)
                ->closeModalByClickingAway(false);
        });

        Actions\CreateAction::configureUsing(function (Actions\CreateAction $action) {
            return $action
                ->icon(Heroicon::Plus)
                ->hiddenLabel()
                ->createAnother(false);
        });

        Actions\EditAction::configureUsing(function (Actions\EditAction $action) {
            return $action
                ->icon(Heroicon::PencilSquare)
                ->hiddenLabel()
                ->button();
        });

        Actions\DeleteAction::configureUsing(function (Actions\DeleteAction $action) {
            return $action
                ->icon(Heroicon::Trash)
                ->hiddenLabel()
                ->button();
        });

        Actions\ViewAction::configureUsing(function (Actions\ViewAction $action) {
            return $action
                ->icon(Heroicon::Eye)
                ->hiddenLabel()
                ->button();
        });
    }

    private function configureSchema(): void
    {
        Schema::configureUsing(function (Schema $schema) {
            return $schema
                ->defaultCurrency(config('filakit.defaultCurrency'))
                ->defaultDateDisplayFormat(config('filakit.defaultDateDisplayFormat'))
                ->defaultIsoDateDisplayFormat(config('filakit.defaultIsoDateDisplayFormat'))
                ->defaultDateTimeDisplayFormat(config('filakit.defaultDateTimeDisplayFormat'))
                ->defaultIsoDateTimeDisplayFormat(config('filakit.defaultIsoDateTimeDisplayFormat'))
                ->defaultNumberLocale(config('filakit.defaultNumberLocale'))
                ->defaultTimeDisplayFormat(config('filakit.defaultTimeDisplayFormat'))
                ->defaultIsoTimeDisplayFormat(config('filakit.defaultIsoTimeDisplayFormat'));
        });
    }

    private function configureForms(): void
    {
        Forms\Components\Field::configureUsing(function (Forms\Components\Field $field) {
            return $field->translateLabel();
        });

        Forms\Components\ToggleButtons::configureUsing(function (Forms\Components\ToggleButtons $component) {
            return $component
                ->inline()
                ->grouped();
        });

        Forms\Components\TextInput::configureUsing(function (Forms\Components\TextInput $component) {
            return $component->minValue(0);
        });

        Forms\Components\Select::configureUsing(function (Forms\Components\Select $component) {
            return $component
                ->native(false)
                ->selectablePlaceholder(function (Forms\Components\Select $component) {
                    return ! $component->isRequired();
                })
                ->searchable(function (Forms\Components\Select $component) {
                    return $component->hasRelationship();
                })
                ->preload(function (Forms\Components\Select $component) {
                    return $component->isSearchable();
                });
        });

        Forms\Components\DateTimePicker::configureUsing(function (Forms\Components\DateTimePicker $component) {
            return $component
                ->seconds(false)
                ->maxDate('9999-12-31T23:59');
        });

        Forms\Components\Repeater::configureUsing(function (Forms\Components\Repeater $component) {
            return $component->deleteAction(function (Actions\Action $action) {
                return $action->requiresConfirmation();
            });
        });

        Forms\Components\Builder::configureUsing(function (Forms\Components\Builder $component) {
            return $component->deleteAction(function (Actions\Action $action) {
                return $action->requiresConfirmation();
            });
        });

        Forms\Components\FileUpload::configureUsing(function (Forms\Components\FileUpload $component) {
            return $component->moveFiles();
        });

        Forms\Components\RichEditor::configureUsing(function (Forms\Components\RichEditor $component) {
            return $component->disableToolbarButtons(['blockquote']);
        });

        Forms\Components\Textarea::configureUsing(function (Forms\Components\Textarea $component) {
            return $component->rows(4);
        });
    }

    private function configureInfolists(): void
    {
        Infolists\Components\Entry::configureUsing(function (Infolists\Components\Entry $entry) {
            return $entry->translateLabel();
        });
    }

    private function configurePages(): void
    {
        Pages\Page::$reportValidationErrorUsing = function (ValidationException $exception) {
            Notifications\Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
        };

        Pages\Page::$formActionsAreSticky = true;
    }

    private function configureTables(): void
    {
        Tables\Table::configureUsing(function (Tables\Table $table) {
            return $table
                ->defaultCurrency(config('filakit.defaultCurrency'))
                ->defaultDateDisplayFormat(config('filakit.defaultDateDisplayFormat'))
                ->defaultIsoDateDisplayFormat(config('filakit.defaultIsoDateDisplayFormat'))
                ->defaultDateTimeDisplayFormat(config('filakit.defaultDateTimeDisplayFormat'))
                ->defaultIsoDateTimeDisplayFormat(config('filakit.defaultIsoDateTimeDisplayFormat'))
                ->defaultNumberLocale(config('filakit.defaultNumberLocale'))
                ->defaultTimeDisplayFormat(config('filakit.defaultTimeDisplayFormat'))
                ->defaultIsoTimeDisplayFormat(config('filakit.defaultIsoTimeDisplayFormat'));
        });
        Tables\Columns\Column::configureUsing(function (Tables\Columns\Column $column) {
            return $column->translateLabel();
        });

        Tables\Table::configureUsing(function (Tables\Table $table) {
            return $table
                ->filtersFormWidth('md')
                ->paginationPageOptions([5, 10, 25, 50]);
        });

        Tables\Columns\ImageColumn::configureUsing(function (Tables\Columns\ImageColumn $column) {
            return $column->extraImgAttributes(['loading' => 'lazy']);
        });

        Tables\Filters\SelectFilter::configureUsing(function (Tables\Filters\SelectFilter $filter) {
            return $filter->native(false);
        });
    }
}
