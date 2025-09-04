<?php

namespace App\Filament\Resources\CommentResource\Widgets;

use App\Filament\Resources\CommentResource;
use App\Models\Comment as ModelsComment;
use Dom\Comment;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestComments extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ModelsComment::whereDate('created_at', '>=', now()->subDays(14)->startOfDay())
            )
            ->columns([
                // ...
                TextColumn::make('user.name'),
                TextColumn::make('post.title'),
                TextColumn::make('comment')
                    ->limit(50)
                    ->tooltip(fn(ModelsComment $record): string => $record->comment),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->url(fn (ModelsComment $record): string => CommentResource::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-o-eye')
                    ->color('green')
                    ->label('View')
                    ->openUrlInNewTab(),

            ]);
    }
}
