<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $indonesianCities = [
            // Sumatra
            'Banda Aceh', 'Medan', 'Padang', 'Pekanbaru', 'Jambi', 
            'Palembang', 'Bengkulu', 'Bandar Lampung', 'Pangkal Pinang',
            'Tanjung Pinang', 'Sungai Penuh', 'Lubuklinggau', 'Bukittinggi',
            'Padang Panjang', 'Dumai', 'Payakumbuh', 'Pariaman', 'Sawahlunto',
            'Sibolga', 'Pematang Siantar', 'Tanjung Balai', 'Binjai',
            
            // Java
            'Jakarta', 'Bandung', 'Surabaya', 'Semarang', 'Yogyakarta',
            'Serang', 'Tangerang', 'Bekasi', 'Depok', 'Bogor', 'Cirebon',
            'Sukabumi', 'Tasikmalaya', 'Cimahi', 'Malang', 'Madiun', 'Kediri',
            'Mojokerto', 'Pasuruan', 'Probolinggo', 'Blitar', 'Magelang',
            'Surakarta', 'Pekalongan', 'Tegal', 'Cilegon',
            
            // Kalimantan
            'Pontianak', 'Palangkaraya', 'Banjarmasin', 'Samarinda',
            'Tanjung Selor', 'Balikpapan', 'Tarakan', 'Bontang', 'Singkawang',
            
            // Sulawesi
            'Makassar', 'Manado', 'Palu', 'Kendari', 'Gorontalo', 'Mamuju',
            'Bitung', 'Tomohon', 'Kotamobagu', 'Palopo', 'Parepare', 'Baubau',
            
            // Bali & Nusa Tenggara
            'Denpasar', 'Mataram', 'Kupang', 'Bima', 'Waingapu', 'Ende',
            'Maumere', 'Labuan Bajo', 'Ruteng', 'Kefamenanu',
            
            // Maluku & Papua
            'Ambon', 'Ternate', 'Tidore', 'Jayapura', 'Manokwari', 'Sorong',
            'Merauke', 'Timika', 'Nabire', 'Biak'
        ];

         // Create placeholder image
         $width = 800;
         $height = 600;
         $image = imagecreatetruecolor($width, $height);
         $bgColor = imagecolorallocate($image, 200, 200, 200);
         imagefill($image, 0, 0, $bgColor);
 
         foreach ($indonesianCities as $city) {
             $filename = 'cities/' . strtolower(str_replace(' ', '-', $city)) . '.jpg';
             
             // Save the image to storage
             ob_start();
             imagejpeg($image);
             $imageContent = ob_get_clean();
             Storage::disk('public')->put($filename, $imageContent);
 
             City::create([
                 'name' => $city,
                 'photo' => $filename
             ]);
         }
 
         imagedestroy($image);
    }
}
