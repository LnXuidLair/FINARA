use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Buat user pegawai
$user = User::create([
    'username' => 'pegawai1',
    'password' => Hash::make('pegawai123'),
    'role' => 'pegawai'
]);

echo "User pegawai berhasil dibuat!\n";
echo "Username: pegawai1\n";
echo "Password: pegawai123\n";

// Cek user
$pegawai = User::where('username', 'pegawai1')->first();
if ($pegawai) {
    echo "User berhasil ditemukan dengan ID: " . $pegawai->id . "\n";
} else {
    echo "User tidak ditemukan\n";
}
