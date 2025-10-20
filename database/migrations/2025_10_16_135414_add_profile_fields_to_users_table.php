public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('nidn')->nullable()->after('email');
        $table->string('prodi')->nullable()->after('nidn');
        $table->string('profile_photo_path')->nullable()->after('prodi'); // simpan path foto
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['nidn', 'prodi', 'profile_photo_path']);
    });
}
