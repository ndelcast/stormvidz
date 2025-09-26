<?php

namespace App\Filament\App\Pages;

use BackedEnum;
use App\Models\Video;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Wizard;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Services\ImageGenerator\ImageGenerator;

class CreateVideo extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.app.pages.create-video';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationLabel = 'Create your video';
    protected static ?string $title = 'Create Video';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Details')->schema([
                        Select::make('format')
                            ->label('Format')
                            ->options([
                                '16:9' => '16:9',
                                '9:16' => '9:16',
                                '1:1'  => '1:1',
                            ])
                            ->required(),
                        Textarea::make('speech')->label('Speech')->required(),
                        Textarea::make('accent')->label('Accent')->required(),
                    ]),
                    Step::make('Media')->schema([
                        FileUpload::make('person_image')
                            ->label('Person Image')
                            ->image()
                            ->directory('videos/person_images')
                            ->required(),
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->directory('videos/logos'),
                    ]),
                ])->submitAction(new HtmlString('<button class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action" type="submit">Submit</button>'))
            ])->statePath('data');
    }

    public function submit(ImageGenerator $imageGenerator): void
    {

        foreach($this->data['person_image'] as $personImage) {
            $personImagePath = $personImage->store('images/persons');
        }

        foreach($this->data['logo'] as $logoImage) {
            $logoImagePath = $logoImage->store('images/logos');
        }

        $video = Video::create([
            'user_id' => auth()->user()->id,
            'person_image' => $personImagePath,
            'format' => $this->data['format'],
            'speech' => $this->data['speech'],
            'accent' => $this->data['accent'],
            'logo' => $logoImagePath,
        ]);
        
        $imageGenerator->generate('nano-banana', [
            'image' => storage_path('app/private/' . $personImagePath)
        ]);

    }
}
