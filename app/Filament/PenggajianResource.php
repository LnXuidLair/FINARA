<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggajianResource\Pages;
use App\Filament\Resources\PenggajianResource\RelationManagers;
use App\Models\Penggajian;
use App\Models\Pegawai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class PenggajianResource extends Resource
{
    protected static ?string $model = Penggajian::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Penggajian';
    protected static ?string $modelLabel = 'Penggajian';
    protected static ?string $navigationGroup = 'Manajemen Karyawan';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_pegawai')
                    ->label('Pegawai')
                    ->options(Pegawai::with('gajiJabatan')->get()->mapWithKeys(fn($pegawai) => [
                        $pegawai->id => $pegawai->nama . ' - ' . ($pegawai->gajiJabatan->jabatan ?? 'Tanpa Jabatan')
                    ]))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        if ($state) {
                            $pegawai = Pegawai::with('gajiJabatan')->find($state);
                            if ($pegawai && $pegawai->gajiJabatan) {
                                $set('gaji_perhari', $pegawai->gajiJabatan->gaji_perhari);
                                
                                // Hitung jumlah kehadiran jika periode sudah diisi
                                if ($get('periode')) {
                                    $jumlahKehadiran = $pegawai->hitungKehadiran($get('periode'));
                                    $set('jumlah_kehadiran', $jumlahKehadiran);
                                    $set('total_gaji', $jumlahKehadiran * $pegawai->gajiJabatan->gaji_perhari);
                                }
                            }
                        }
                    }),
                    
                Forms\Components\DatePicker::make('periode')
                    ->label('Periode')
                    ->displayFormat('Y-m')
                    ->native(false)
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $pegawaiId = $get('id_pegawai');
                        if ($state && $pegawaiId) {
                            $pegawai = Pegawai::with('gajiJabatan')->find($pegawaiId);
                            if ($pegawai) {
                                $jumlahKehadiran = $pegawai->hitungKehadiran($state);
                                $set('jumlah_kehadiran', $jumlahKehadiran);
                                if ($pegawai->gajiJabatan) {
                                    $set('gaji_perhari', $pegawai->gajiJabatan->gaji_perhari);
                                    $set('total_gaji', $jumlahKehadiran * $pegawai->gajiJabatan->gaji_perhari);
                                }
                            }
                        }
                    }),
                    
                Forms\Components\TextInput::make('gaji_perhari')
                    ->label('Gaji per Hari')
                    ->prefix('Rp ')
                    ->numeric()
                    ->required()
                    ->readOnly()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $kehadiran = $get('jumlah_kehadiran') ?: 0;
                        $set('total_gaji', $kehadiran * $state);
                    }),
                    
                Forms\Components\TextInput::make('jumlah_kehadiran')
                    ->label('Jumlah Kehadiran')
                    ->numeric()
                    ->required()
                    ->readOnly()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $gajiPerHari = $get('gaji_perhari') ?: 0;
                        $set('total_gaji', $state * $gajiPerHari);
                    }),
                    
                Forms\Components\TextInput::make('total_gaji')
                    ->label('Total Gaji')
                    ->prefix('Rp ')
                    ->numeric()
                    ->required()
                    ->readOnly(),
                    
                Forms\Components\Select::make('status_penggajian')
                    ->label('Status Penggajian')
                    ->options([
                        'belum_dibayar' => 'Belum Dibayar',
                        'sudah_dibayar' => 'Sudah Dibayar',
                    ])
                    ->default('belum_dibayar')
                    ->required(),
                    
                Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->default(now())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pegawai.nama')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('periode')
                    ->label('Periode')
                    ->date('F Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('jumlah_kehadiran')
                    ->label('Kehadiran')
                    ->numeric()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('gaji_perhari')
                    ->label('Gaji/Hari')
                    ->money('IDR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('total_gaji')
                    ->label('Total Gaji')
                    ->money('IDR')
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),
                    
                Tables\Columns\BadgeColumn::make('status_penggajian')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => 
                        $state === 'sudah_dibayar' ? 'Sudah Dibayar' : 'Belum Dibayar'
                    )
                    ->colors([
                        'success' => 'sudah_dibayar',
                        'warning' => 'belum_dibayar',
                    ])
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_penggajian')
                    ->label('Status Pembayaran')
                    ->options([
                        'belum_dibayar' => 'Belum Dibayar',
                        'sudah_dibayar' => 'Sudah Dibayar',
                    ]),
                Tables\Filters\SelectFilter::make('id_pegawai')
                    ->label('Pegawai')
                    ->relationship('pegawai', 'nama')
                    ->searchable(),
                Tables\Filters\Filter::make('periode')
                    ->form([
                        Forms\Components\DatePicker::make('periode')
                            ->label('Periode')
                            ->displayFormat('Y-m')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['periode'],
                                fn (Builder $query, $date): Builder => $query->where('periode', 'like', "%{$date}%"),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('cetak')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn (Penggajian $record): string => route('admin.penggajian.cetak', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // No relations needed for now
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenggajians::route('/'),
            'create' => Pages\CreatePenggajian::route('/create'),
            'edit' => Pages\EditPenggajian::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
