<?php

namespace App\Providers;

use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Notifications;
use Filament\Pages;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;

class FilamentServiceProvider extends ServiceProvider
{
    private string $defaultDateDisplayFormat = 'd/m/Y';

    private string $defaultDateTimeDisplayFormat = 'd/m/Y H:i:s';

    private string $defaultCurrency = 'brl';

    // Ref: https://github.com/filamentphp-br/satis/blob/main/app/Providers/FilamentServiceProvider.php -> boot
    public function boot(): void
    {
        $this->configureActions();
        $this->configureForms();
        $this->configureInfolists();
        $this->configurePages();
        $this->configureTables();
    }

    private function configureActions(): void
    {
        Actions\ActionGroup::configureUsing(function (Actions\ActionGroup $action) {
            return $action->icon('heroicon-o-ellipsis-horizontal');
        });

        Actions\Action::configureUsing(function (Actions\Action $action) {
            return $action->translateLabel()
                ->modalWidth(MaxWidth::Medium)
                ->closeModalByClickingAway(false);
        });

        Actions\StaticAction::configureUsing(function (Actions\StaticAction $staticAction) {
            return $staticAction->translateLabel();
        });

        Actions\CreateAction::configureUsing(function (Actions\CreateAction $action) {
            return $action->icon('heroicon-o-plus')
                ->createAnother(false);
        });

        Actions\EditAction::configureUsing(function (Actions\EditAction $action) {
            return $action->icon('heroicon-o-pencil');
        });

        Actions\DeleteAction::configureUsing(function (Actions\DeleteAction $action) {
            return $action->icon('heroicon-o-trash');
        });
    }

    private function configureForms(): void
    {
        Forms\Components\Field::configureUsing(function (Forms\Components\Field $field) {
            return $field->translateLabel();
        });

        Forms\Components\Actions\Action::configureUsing(function (Forms\Components\Actions\Action $action) {
            return $action->modalWidth(MaxWidth::Medium)
                ->closeModalByClickingAway(false);
        });

        Forms\Components\ToggleButtons::configureUsing(function (Forms\Components\ToggleButtons $component) {
            return $component->inline()
                ->grouped();
        });

        Forms\Components\Placeholder::configureUsing(function (Forms\Components\Placeholder $component) {
            return $component->columnSpanFull();
        });

        Forms\Components\TextInput::configureUsing(function (Forms\Components\TextInput $component) {
            return $component->minValue(0);
        });

        Forms\Components\Select::configureUsing(function (Forms\Components\Select $component) {
            return $component->native(false)
                ->selectablePlaceholder(function (Forms\Components\Select $component) {
                    return !$component->isRequired();
                })
                ->searchable(function (Forms\Components\Select $component) {
                    return $component->hasRelationship();
                })
                ->preload(function (Forms\Components\Select $component) {
                    return $component->isSearchable();
                });
        });

        Forms\Components\DateTimePicker::configureUsing(function (Forms\Components\DateTimePicker $component) {
            return $component->seconds(false)
                ->maxDate('9999-12-31T23:59');
        });

        Forms\Components\Repeater::configureUsing(function (Forms\Components\Repeater $component) {
            return $component->deleteAction(function (Forms\Components\Actions\Action $action) {
                return $action->requiresConfirmation();
            });
        });

        Forms\Components\Builder::configureUsing(function (Forms\Components\Builder $component) {
            return $component->deleteAction(function (Forms\Components\Actions\Action $action) {
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
        Infolists\Infolist::$defaultDateDisplayFormat = $this->defaultDateDisplayFormat;

        Infolists\Infolist::$defaultDateTimeDisplayFormat = $this->defaultDateTimeDisplayFormat;

        Infolists\Infolist::$defaultCurrency = $this->defaultCurrency;

        Infolists\Components\Entry::configureUsing(function (Infolists\Components\Entry $entry) {
            return $entry->translateLabel();
        });

        Infolists\Components\Actions\Action::configureUsing(function (Infolists\Components\Actions\Action $action) {
            return $action->modalWidth(MaxWidth::Medium)
                ->closeModalByClickingAway(false);
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
        Tables\Table::$defaultDateDisplayFormat = $this->defaultDateDisplayFormat;

        Tables\Table::$defaultDateTimeDisplayFormat = $this->defaultDateTimeDisplayFormat;

        Tables\Table::$defaultCurrency = $this->defaultCurrency;

        Tables\Columns\Column::configureUsing(function (Tables\Columns\Column $column) {
            return $column->translateLabel();
        });

        Tables\Table::configureUsing(function (Tables\Table $table) {
            return $table->filtersFormWidth('md')
                ->paginationPageOptions([5, 10, 25, 50]);
        });

        Tables\Columns\ImageColumn::configureUsing(function (Tables\Columns\ImageColumn $column) {
            return $column->extraImgAttributes(['loading' => 'lazy']);
        });

        Tables\Columns\TextColumn::configureUsing(function (Tables\Columns\TextColumn $column) {
            return $column->limit(50)
                ->wrap();
        });

        Tables\Actions\Action::configureUsing(function (Tables\Actions\Action $action) {
            return $action->modalWidth(MaxWidth::Medium)
                ->closeModalByClickingAway(false);
        });

        Tables\Actions\CreateAction::configureUsing(function (Tables\Actions\CreateAction $action) {
            return $action->icon('heroicon-o-plus')
                ->createAnother(false);
        });

        Tables\Actions\EditAction::configureUsing(function (Tables\Actions\EditAction $action) {
            return $action->icon('heroicon-o-pencil');
        });

        Tables\Actions\DeleteAction::configureUsing(function (Tables\Actions\DeleteAction $action) {
            return $action->icon('heroicon-o-trash');
        });

        Tables\Filters\SelectFilter::configureUsing(function (Tables\Filters\SelectFilter $filter) {
            return $filter->native(false);
        });
    }
}
