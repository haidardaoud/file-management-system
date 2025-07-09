<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupResource\Pages;
use App\Filament\Resources\GroupResource\RelationManagers;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\FileUpload::make('image')
                ->label('Group Image')
                ->image()
                ->directory('public/groups')
                ->required(),
            Forms\Components\Textarea::make('description')
                ->required(),
                Forms\Components\Select::make('owner_id')
                ->label('Select Group Admin')
                ->options(User::pluck('name', 'id'))
                ->searchable()
                ->helperText('Select a user to assign as the group admin')
                ->required(),

        ]);

    }



    public static function table(Table $table): Table
    {

        return $table->columns([
            Tables\Columns\ImageColumn::make('image')
            ->label('Image') // Column label
            ->width(50) // Image width
            ->height(50) // Image height
            ->url(fn ($record) => asset('' . $record->image))
            ->disk('public'),
            // ->url(fn ($record) => Storage::url($record->image)),


            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('description'),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ])
        ->actions([
            Tables\Actions\ViewAction::make()->url(fn (Group $record) => route('view', ['id' => $record->id])),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
            Tables\Actions\Action::make('Manage Members')
                ->action(function (Group $record) {
                    return redirect()->route('group.members', ['id' => $record->id]);
                }),
        ]);
    }

    public static function view(Group $record)
    {
        return view('group.view', [
            'group' => $record,
            'files' => $record->files, // Assuming you have a relationship defined in your Group model
            'members' => $record->members, // Assuming you have a relationship defined for members
        ]);
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
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
        ];
    }
}
