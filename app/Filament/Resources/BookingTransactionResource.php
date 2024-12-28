<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\BookingTransaction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BookingTransactionResource\Pages;
use App\Filament\Resources\BookingTransactionResource\RelationManagers;
use Filament\Notifications\Notification;

class BookingTransactionResource extends Resource
{
    protected static ?string $model = BookingTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('booking_trx_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->prefix('IDR'),
                Forms\Components\TextInput::make('duration')
                    ->required()
                    ->numeric()
                    ->prefix('Days'),

                Forms\Components\DatePicker::make('started_at')
                ->required(),
                Forms\Components\DatePicker::make('ended_at')
                ->required(),

                Forms\Components\Select::make('is_paid')
                ->options([
                    true => 'Paid',
                    false => 'Unpaid'
                ])
                ->required(),

                Forms\Components\Select::make('office_space_id')
                ->relationship('officeSpace', 'name')
                ->searchable()
                ->preload()
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_trx_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('officeSpace.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ended_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_paid')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'PAID' : 'UNPAID')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                    ->label('Status')
                

            ])
            ->filters([
                SelectFilter::make('office_space_id')
                ->relationship('officeSpace', 'name')
                ->label('Office Space'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->action(function (BookingTransaction $record) {
                        $record->is_paid = true;
                        $record->save();

                        Notification::make()
                            ->title('Success')
                            ->success()
                            ->body('Booking Transaction has been approved')
                            ->send();

                            // Send WhatsApp notification
                            $messageBody = "✅ Booking Confirmation\n\n" .
                            "📋 Booking ID: {$record->booking_trx_id}\n" .
                            "👤 Name: {$record->name}\n" .
                            "📅 Start Date: {$record->started_at}\n" .
                            "⏱️ Duration: {$record->duration} days\n\n";
                            
                            // $messageBody .= "Hi {$record->name}, Terimakasih telah booking kantor di Rent Office pada {$record->started_at} dengan durasi {$record->duration} hari.\n\n" ;
                            $messageBody .= "Pesanan kantor {$record->officeSpace->name} anda dengan kode booking {$record->booking_trx_id} sudah terbayar penuh.\n";
                            $messageBody .= "Silahkan datang ke lokasi kantor {$record->officeSpace->name} untuk mulai menggunakan ruangan kerja tersebut.\n\n";
                            $messageBody .= "Jika Anda memiliki pertanyaan atau memerlukan bantuan lebih lanjut, jangan ragu untuk menghubungi kami melalui email atau telepon.\n\n";
                            $messageBody .= "Terima kasih telah memilih Rent Office sebagai tempat kerja Anda.\n";
                            $messageBody .= "Rent Office\n\n";

                            // Send WhatsApp message using try-catch
                            try {
                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => 'https://wablaz.com/api/create-message',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 30,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => array(
                                        'appkey' => 'bf4d7e1d-b58d-4aa2-9438-72a0f0507dfa',
                                        'authkey' => '4M7ywzSiNcVJ1JFyiePkcI2czQI5VqPPQYyfspBd8wLdFFc3eO',
                                        'to' => $record->phone_number,
                                        'message' => $messageBody,
                                        'sandbox' => 'false'
                                    ),
                                ));

                                $response = curl_exec($curl);
                                $error = curl_error($curl);
                                curl_close($curl);

                                if ($error) {
                                    \Log::error('WhatsApp API Error: ' . $error);
                                }
                            } catch (\Exception $e) {
                                \Log::error('WhatsApp Sending Error: ' . $e->getMessage());
                            }
                            
                    })
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (BookingTransaction $record) => $record->is_paid == false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListBookingTransactions::route('/'),
            'create' => Pages\CreateBookingTransaction::route('/create'),
            'edit' => Pages\EditBookingTransaction::route('/{record}/edit'),
        ];
    }
}
