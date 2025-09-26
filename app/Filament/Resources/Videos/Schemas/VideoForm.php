<?php

namespace App\Filament\Resources\Videos\Schemas;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VideoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('User')
                    ->options(User::pluck('name', 'id'))
                    ->required(),
                FileUpload::make('person_image')
                    ->label('Person Image')
                    ->image()
                    ->directory('videos/person_images')
                    ->required(),
                Select::make('format')
                    ->label('Format')
                    ->options([
                        '16:9' => '16:9',
                        '9:16' => '9:16',
                        '1:1' => '1:1',
                    ])
                    ->required(),
                Textarea::make('speech')
                    ->label('Speech')
                    ->required(),
                Textarea::make('accent')
                    ->label('Accent')
                    ->required(),
                FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->directory('videos/logos'),
            ]);
    }
}
