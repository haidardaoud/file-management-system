<?php

// namespace App\Filament\Resources;

// use App\Filament\Resources\LogResource\Pages;
// use App\Filament\Resources\LogResource\RelationManagers;
// use App\Models\FileVersion;
// use App\Models\Log;
// use Filament\Forms;
// use Filament\Forms\Form;
// use Filament\Resources\Resource;
// use Filament\Tables;
// use Filament\Tables\Columns\BadgeColumn;
// use Filament\Tables\Columns\TextColumn;
// use Filament\Tables\Table;
// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\SoftDeletingScope;

// class LogResource extends Resource
// {
//     protected static ?string $model = Log::class;

//     protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
//     protected static ?string $navigationLabel = 'Logs';
//     protected static ?string $pluralLabel = 'Logs';
//     protected static ?string $singularLabel = 'Log';
//     protected static ?string $navigationGroup = 'System Management';

//     public static function form(Form $form): Form
//     {
//         return $form
//             ->schema([
//                 Forms\Components\TextInput::make('user.name')
//                     ->label('User')
//                     ->disabled(),
//                 Forms\Components\TextInput::make('file.name')
//                     ->label('File')
//                     ->disabled(),
//                 Forms\Components\Textarea::make('details')
//                     ->label('Details')
//                     ->disabled(),
//                 Forms\Components\TextInput::make('action')
//                     ->label('Action')
//                     ->disabled(),
//                 Forms\Components\DateTimePicker::make('created_at')
//                     ->label('Created At')
//                     ->disabled(),
//             ]);
//     }

//     // public static function table(Table $table): Table
//     // {
//     //     return $table
//     //         ->columns([
//     //             TextColumn::make('user.name')
//     //                 ->label('User')
//     //                 ->sortable()
//     //                 ->searchable(),
//     //             TextColumn::make('file.name')
//     //                 ->label('File')
//     //                 ->sortable()
//     //                 ->searchable(),
//     //             BadgeColumn::make('action')
//     //                 ->label('Action')
//     //                 ->colors([
//     //                     'success' => 'create',
//     //                     'warning' => 'update',
//     //                     'danger' => 'delete',
//     //                     'primary' => 'check-in',
//     //                     'secondary' => 'check-out',
//     //                 ]),
//     //             TextColumn::make('details')
//     //                 ->label('Details')
//     //                 ->limit(50)
//     //                 ->tooltip(fn ($record) => $record->details),
//     //             TextColumn::make('created_at')
//     //                 ->label('Date')
//     //                 ->dateTime()
//     //                 ->sortable(),
//     //         ])
//     //         ->filters([
//     //             // Add filters here if needed
//     //         ])
//     //         ->actions([
//     //             Tables\Actions\ViewAction::make(),
//     //         ])
//     //         ->bulkActions([
//     //             Tables\Actions\DeleteBulkAction::make(),
//     //         ]);
//     // }
//     public static function table(Table $table): Table
// {
//     return $table
//         ->columns([
//             TextColumn::make('user.name')->label('User')->sortable()->searchable(),
//             TextColumn::make('file.name')->label('File')->sortable()->searchable(),
//             BadgeColumn::make('action')->label('Action')->colors([
//                 'success' => 'create',
//                 'warning' => 'update',
//                 'danger' => 'delete',
//                 'primary' => 'check-in',
//                 'secondary' => 'check-out',
//             ]),
//             TextColumn::make('details')->label('Details')->limit(50)->tooltip(fn ($record) => $record->details),
//             TextColumn::make('created_at')->label('Date')->dateTime()->sortable(),
//             TextColumn::make('diff_file')
//     ->label('Diff File')
//     ->url(fn ($record) => $record && $record->details ? asset("storage/diffs/{$record->file_id}_diff_v{$record->file_version}.pdf") : null)
//     ->openUrlInNewTab()
//     ->label('View Diff')
//     ->hidden(fn ($record) => !$record || !$record->details)


//         ])
//         ->actions([
//             Tables\Actions\ViewAction::make(),
//             Tables\Actions\Action::make('viewPDFDiff')
//                 ->label('View Diff')
//                 ->action(function (Log $record) {
//                     $fileId = $record->file_id;
//                     $currentVersion = FileVersion::where('file_id', $fileId)->max('version_number');

//                     if (!$currentVersion) {
//                         throw new \Exception('No versions available for this file.');
//                     }

//                     // استدعاء خدمة المقارنة
//                     // $diffPath = \App\Services\PDFComparisonService::compareFileVersions($fileId, $currentVersion);

//                     // // تحديث التفاصيل في اللوج
//                     // $record->update(['details' => "Diff file available at: {$diffPath}"]);
//                 })
//                 ->requiresConfirmation()
//                 ->icon('heroicon-o-eye'),
//         ])
//         ->bulkActions([
//             Tables\Actions\DeleteBulkAction::make(),
//         ]);
// }


//     public static function getRelations(): array
//     {
//         return [
//             //
//         ];
//     }

//     public static function getPages(): array
//     {
//         return [
//             // 'index' => Pages\ListLogs::route('/'),
//             // 'create' => Pages\CreateLog::route('/create'),
//             // 'edit' => Pages\EditLog::route('/{record}/edit'),
//             'index' => Pages\ListLogs::route('/'),
//             'view' => Pages\ViewLog::route('/{record}'),
//         ];
//     }
// }




//---------------------------------------------------------------------------------------------------------------------------------

namespace App\Filament\Resources;

use App\Filament\Resources\LogResource\Pages;
use App\Models\FileVersion;
use App\Models\Log;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;

class LogResource extends Resource
{
        protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Logs';
    protected static ?string $pluralLabel = 'Logs';
    protected static ?string $singularLabel = 'Log';
    protected static ?string $navigationGroup = 'System Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user.name')
                    ->label('User')
                    ->disabled(),
                Forms\Components\TextInput::make('file.name')
                    ->label('File')
                    ->disabled(),
                Forms\Components\Textarea::make('details')
                    ->label('Details')
                    ->disabled(),
                Forms\Components\TextInput::make('action')
                    ->label('Action')
                    ->disabled(),
                Forms\Components\DateTimePicker::make('created_at')
                    ->label('Created At')
                    ->disabled(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                TextColumn::make('file.name')->label('File')->sortable()->searchable(),
                BadgeColumn::make('action')->label('Action')->colors([
                    'success' => 'create',
                    'warning' => 'update',
                    'danger' => 'delete',
                    'primary' => 'check-in',
                    'secondary' => 'check-out',
                ]),
                TextColumn::make('details')->label('Details')->limit(50)->tooltip(fn ($record) => $record->details),
                TextColumn::make('created_at')->label('Date')->dateTime()->sortable(),
                TextColumn::make('diff_file')
                    ->label('Diff File')
                    ->url(fn ($record) => $record && $record->details ? route('view.pdf.diff', $record->id) : null)
                    ->openUrlInNewTab()
                    ->label('View Diff')
                    ->hidden(fn ($record) => !$record || !$record->details)
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('viewPDFDiff')
                    ->label('Generate Diff')
                    ->action(function (Log $record) {
                        $fileId = $record->file_id;
                        $currentVersion = FileVersion::where('file_id', $fileId)->max('version_number');

                        if (!$currentVersion) {
                            throw new \Exception('No versions available for this file.');
                        }

                        // استدعاء خدمة المقارنة وإنشاء PDF
                        $diffContent = self::generateDiffContent($fileId, $currentVersion);
                        $pdfPath = storage_path("app/public/diffs/{$fileId}_diff_v{$currentVersion}.pdf");

                        PDF::loadHTML($diffContent)->save($pdfPath);

                        // تحديث التفاصيل في اللوج
                        $record->update(['details' => "Diff file generated: {$pdfPath}"]);
                    })
                    ->requiresConfirmation()
                    ->icon('heroicon-o-document'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    private static function generateDiffContent($fileId, $currentVersion)
    {
        // هنا يجب تنفيذ منطق المقارنة بين الإصدارات
        // هذا مثال بسيط، يجب استبداله بمنطق المقارنة الفعلي
        $oldContent = "Old content...";
        $newContent = "New content...";

        $diff = "Differences between versions:\n";
        $diff .= "Old: $oldContent\n";
        $diff .= "New: $newContent\n";

        return "<pre>$diff</pre>";
    }

        public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            // 'index' => Pages\ListLogs::route('/'),
            // 'create' => Pages\CreateLog::route('/create'),
            // 'edit' => Pages\EditLog::route('/{record}/edit'),
            'index' => Pages\ListLogs::route('/'),
            //'view' => Pages\ViewLog::route('/{record}'),
        ];
    }
}
