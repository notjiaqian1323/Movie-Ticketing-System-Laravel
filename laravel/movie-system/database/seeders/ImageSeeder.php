<?php
//Name: HO YI VON
//Student ID : 23WMR14542
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define the source and destination base paths
        $sourcePath = database_path('seeders/images');
        $destinationPath = storage_path('app/public');

        // List all subfolders you want to copy
        $folders = ['movies', 'logos', 'icons'];

        foreach ($folders as $folder) {
            $this->copyDirectory(
                $sourcePath . '/' . $folder,
                $destinationPath . '/' . $folder
            );
        }
    }

    /**
     * A helper function to copy a directory recursively.
     */
    private function copyDirectory(string $source, string $destination)
    {
        if (!File::exists($source)) {
            $this->command->warn('Source directory ' . $source . ' does not exist.');
            return;
        }

        File::ensureDirectoryExists($destination);

        $files = File::allFiles($source);

        foreach ($files as $file) {
            $relativePath = $file->getRelativePathname();
            $destinationFile = $destination . '/' . $relativePath;

            if (!File::exists($destinationFile)) {
                File::copy($file->getPathname(), $destinationFile);
                $this->command->info('Copied: ' . $relativePath);
            }
        }
    }
}
