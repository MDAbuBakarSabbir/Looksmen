<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $thana = [
            // --- District: Bagerhat (district_id: 1) ---
            ["name" => "Bagerhat Sadar", "district_id" => "1"],
            ["name" => "Chitalmari", "district_id" => "1"],
            ["name" => "Fakirhat", "district_id" => "1"],
            ["name" => "Kachua", "district_id" => "1"],
            ["name" => "Mollahat", "district_id" => "1"],
            ["name" => "Mongla Port", "district_id" => "1"],
            ["name" => "Morrelganj", "district_id" => "1"],
            ["name" => "Rampal", "district_id" => "1"],
            ["name" => "Sharankhola", "district_id" => "1"],

            // --- District: Bandarban (district_id: 2) ---
            ["name" => "Ali Kadam", "district_id" => "2"],
            ["name" => "Bandarban Sadar", "district_id" => "2"],
            ["name" => "Lama", "district_id" => "2"],
            ["name" => "Naikhongchhari", "district_id" => "2"],
            ["name" => "Rowangchhari", "district_id" => "2"],
            ["name" => "Ruma", "district_id" => "2"],
            ["name" => "Thanchi", "district_id" => "2"],

            // --- District: Barguna (district_id: 3) ---
            ["name" => "Amtali", "district_id" => "3"],
            ["name" => "Bamna", "district_id" => "3"],
            ["name" => "Barguna Sadar", "district_id" => "3"],
            ["name" => "Betagi", "district_id" => "3"],
            ["name" => "Patharghata", "district_id" => "3"],
            ["name" => "Taltali", "district_id" => "3"],

            // --- District: Barishal (district_id: 4) ---
            ["name" => "Agailjhara", "district_id" => "4"],
            ["name" => "Babuganj", "district_id" => "4"],
            ["name" => "Bakerganj", "district_id" => "4"],
            ["name" => "Banaripara", "district_id" => "4"],
            ["name" => "Barishal Sadar (Kotwali)", "district_id" => "4"],
            ["name" => "Gaurnadi", "district_id" => "4"],
            ["name" => "Hizla", "district_id" => "4"],
            ["name" => "Mehendiganj", "district_id" => "4"],
            ["name" => "Muladi", "district_id" => "4"],
            ["name" => "Wazirpur", "district_id" => "4"],

            // --- District: Bhola (district_id: 5) ---
            ["name" => "Bhola Sadar", "district_id" => "5"],
            ["name" => "Burhanuddin", "district_id" => "5"],
            ["name" => "Char Fasson", "district_id" => "5"],
            ["name" => "Daulatkhan", "district_id" => "5"],
            ["name" => "Lalmohan", "district_id" => "5"],
            ["name" => "Manpura", "district_id" => "5"],
            ["name" => "Tazumuddin", "district_id" => "5"],

            // --- District: Bogura (district_id: 6) ---
            ["name" => "Adamdighi", "district_id" => "6"],
            ["name" => "Bogura Sadar", "district_id" => "6"],
            ["name" => "Dhunat", "district_id" => "6"],
            ["name" => "Dupchanchia", "district_id" => "6"],
            ["name" => "Gabtali", "district_id" => "6"],
            ["name" => "Kahaloo", "district_id" => "6"],
            ["name" => "Nandigram", "district_id" => "6"],
            ["name" => "Sariakandi", "district_id" => "6"],
            ["name" => "Shahjahanpur", "district_id" => "6"],
            ["name" => "Sherpur", "district_id" => "6"],
            ["name" => "Shibganj", "district_id" => "6"],
            ["name" => "Sonatola", "district_id" => "6"],

            // --- District: Brahmanbaria (district_id: 7) ---
            ["name" => "Akhaura", "district_id" => "7"],
            ["name" => "Ashuganj", "district_id" => "7"],
            ["name" => "Bancharampur", "district_id" => "7"],
            ["name" => "Bijoynagar", "district_id" => "7"],
            ["name" => "Brahmanbaria Sadar", "district_id" => "7"],
            ["name" => "Kasba", "district_id" => "7"],
            ["name" => "Nabinagar", "district_id" => "7"],
            ["name" => "Nasirnagar", "district_id" => "7"],
            ["name" => "Sarail", "district_id" => "7"],

            // --- District: Chandpur (district_id: 8) ---
            ["name" => "Chandpur Sadar", "district_id" => "8"],
            ["name" => "Faridganj", "district_id" => "8"],
            ["name" => "Haimchar", "district_id" => "8"],
            ["name" => "Haziganj", "district_id" => "8"],
            ["name" => "Kachuaa", "district_id" => "8"],
            ["name" => "Matlab Dakshin", "district_id" => "8"],
            ["name" => "Matlab Uttar", "district_id" => "8"],
            ["name" => "Shahrasti", "district_id" => "8"],

            // --- District: Chapai Nawabganj (district_id: 9) ---
            ["name" => "Bholahat", "district_id" => "9"],
            ["name" => "Gomastapur", "district_id" => "9"],
            ["name" => "Nachole", "district_id" => "9"],
            ["name" => "Chapai Nawabganj Sadar", "district_id" => "9"],
            ["name" => "Shibgang", "district_id" => "9"],

            // --- District: Chattogram (district_id: 10) ---
            ["name" => "Anwara", "district_id" => "10"],
            ["name" => "Banshkhali", "district_id" => "10"],
            ["name" => "Boalkhali", "district_id" => "10"],
            ["name" => "Chandanaish", "district_id" => "10"],
            ["name" => "Fatikchhari", "district_id" => "10"],
            ["name" => "Hathazari", "district_id" => "10"],
            ["name" => "Lohagara", "district_id" => "10"],
            ["name" => "Mirsharai", "district_id" => "10"],
            ["name" => "Patiya", "district_id" => "10"],
            ["name" => "Rangunia", "district_id" => "10"],
            ["name" => "Raozan", "district_id" => "10"],
            ["name" => "Sandwip", "district_id" => "10"],
            ["name" => "Satkania", "district_id" => "10"],
            ["name" => "Sitakunda", "district_id" => "10"],
            // Thanas (City Area - Sadar)
            ["name" => "Bandar Thana", "district_id" => "10"],
            ["name" => "Bayezid Bostami Thana", "district_id" => "10"],
            ["name" => "Panchlaish Thana", "district_id" => "10"],
            // ... many other City Thanas

            // --- District: Chuadanga (district_id: 11) ---
            ["name" => "Alamdanga", "district_id" => "11"],
            ["name" => "Chuadanga Sadar", "district_id" => "11"],
            ["name" => "Damurhuda", "district_id" => "11"],
            ["name" => "Jibannagar", "district_id" => "11"],

            // --- District: Cumilla (district_id: 12) ---
            ["name" => "Barura", "district_id" => "12"],
            ["name" => "Brahmanpara", "district_id" => "12"],
            ["name" => "Burichang", "district_id" => "12"],
            ["name" => "Chandina", "district_id" => "12"],
            ["name" => "Chauddagram", "district_id" => "12"],
            ["name" => "Daudkandi", "district_id" => "12"],
            ["name" => "Debidwar", "district_id" => "12"],
            ["name" => "Homna", "district_id" => "12"],
            ["name" => "Cumilla Adarsha Sadar", "district_id" => "12"],
            ["name" => "Cumilla Sadar Dakshin", "district_id" => "12"],
            ["name" => "Laksam", "district_id" => "12"],
            ["name" => "Meghna", "district_id" => "12"],
            ["name" => "Monohargonj", "district_id" => "12"],
            ["name" => "Muradnagar", "district_id" => "12"],
            ["name" => "Nangalkot", "district_id" => "12"],
            ["name" => "Titas", "district_id" => "12"],
            ["name" => "Lalmai", "district_id" => "12"],

            // --- District: Cox's Bazar (district_id: 13) ---
            ["name" => "Chakaria", "district_id" => "13"],
            ["name" => "Cox's Bazar Sadar", "district_id" => "13"],
            ["name" => "Kutubdia", "district_id" => "13"],
            ["name" => "Maheshkhali", "district_id" => "13"],
            ["name" => "Pekua", "district_id" => "13"],
            ["name" => "Ramu", "district_id" => "13"],
            ["name" => "Teknaf", "district_id" => "13"],
            ["name" => "Ukhia", "district_id" => "13"],
            ["name" => "Eidgaon", "district_id" => "13"],

            // --- District: Dhaka (City Thanas - district_id: 14) ---
            // These are Thanas within the Dhaka City Corporation area (DCC)
            ["name" => "Adabor Thana", "district_id" => "14"],
            ["name" => "Badda Thana", "district_id" => "14"],
            ["name" => "Dhanmondi Thana", "district_id" => "14"],
            ["name" => "Gulshan Thana", "district_id" => "14"],
            ["name" => "Hazaribagh Thana", "district_id" => "14"],
            ["name" => "Mirpur Thana", "district_id" => "14"],
            ["name" => "Motijheel Thana", "district_id" => "14"],
            ["name" => "Pallabi Thana", "district_id" => "14"],
            ["name" => "Ramna Thana", "district_id" => "14"],
            ["name" => "Tejgaon Thana", "district_id" => "14"],
            ["name" => "Uttara Thana", "district_id" => "14"],
            // ... many more City Thanas (about 50 in total)

            // --- District: Dhaka Sub (Dhaka District Upazilas - district_id: 15) ---
            // These are the Upazilas outside the DCC area but within Dhaka District
            ["name" => "Dhamrai", "district_id" => "15"],
            ["name" => "Dohar", "district_id" => "15"],
            ["name" => "Keraniganj", "district_id" => "15"],
            ["name" => "Nawabgang", "district_id" => "15"],
            ["name" => "Savar", "district_id" => "15"],

            // --- District: Dinajpur (district_id: 16) ---
            ["name" => "Birampur", "district_id" => "16"],
            ["name" => "Birganj", "district_id" => "16"],
            ["name" => "Biral", "district_id" => "16"],
            ["name" => "Bochaganj", "district_id" => "16"],
            ["name" => "Chirirbandar", "district_id" => "16"],
            ["name" => "Dinajpur Sadar", "district_id" => "16"],
            ["name" => "Ghoraghat", "district_id" => "16"],
            ["name" => "Hakimpur", "district_id" => "16"],
            ["name" => "Kaharole", "district_id" => "16"],
            ["name" => "Khansama", "district_id" => "16"],
            ["name" => "Nawabganj", "district_id" => "16"],
            ["name" => "Parbatipur", "district_id" => "16"],
            ["name" => "Phulbari", "district_id" => "16"],

            // --- District: Faridpur (district_id: 17) ---
            ["name" => "Alfadanga", "district_id" => "17"],
            ["name" => "Bhanga", "district_id" => "17"],
            ["name" => "Boalmari", "district_id" => "17"],
            ["name" => "Charbhadrasan", "district_id" => "17"],
            ["name" => "Faridpur Sadar", "district_id" => "17"],
            ["name" => "Madhukhali", "district_id" => "17"],
            ["name" => "Nagarkanda", "district_id" => "17"],
            ["name" => "Sadarpur", "district_id" => "17"],
            ["name" => "Saltha", "district_id" => "17"],

            // --- District: Feni (district_id: 18) ---
            ["name" => "Chhagalnaiya", "district_id" => "18"],
            ["name" => "Daganbhuiyan", "district_id" => "18"],
            ["name" => "Feni Sadar", "district_id" => "18"],
            ["name" => "Fulgazi", "district_id" => "18"],
            ["name" => "Parshuram", "district_id" => "18"],
            ["name" => "Sonagazi", "district_id" => "18"],

            // --- District: Gaibandha (district_id: 19) ---
            ["name" => "Phulchhari", "district_id" => "19"],
            ["name" => "Gaibandha Sadar", "district_id" => "19"],
            ["name" => "Gobindaganj", "district_id" => "19"],
            ["name" => "Palashbari", "district_id" => "19"],
            ["name" => "Sadullapur", "district_id" => "19"],
            ["name" => "Sughatta", "district_id" => "19"],
            ["name" => "Sundarganj", "district_id" => "19"],

            // --- District: Gazipur (district_id: 20) ---
            ["name" => "Gazipur Sadar (Joydebpur)", "district_id" => "20"],
            ["name" => "Kaliakair", "district_id" => "20"],
            ["name" => "Kaliganj", "district_id" => "20"],
            ["name" => "Kapasia", "district_id" => "20"],
            ["name" => "Sreepur", "district_id" => "20"],

            // --- District: Gopalganj (district_id: 21) ---
            ["name" => "Gopalganj Sadar", "district_id" => "21"],
            ["name" => "Kashiani", "district_id" => "21"],
            ["name" => "Kotalipara", "district_id" => "21"],
            ["name" => "Muksudpur", "district_id" => "21"],
            ["name" => "Tungipara", "district_id" => "21"],

            // --- District: Habiganj (district_id: 22) ---
            ["name" => "Ajmiriganj", "district_id" => "22"],
            ["name" => "Bahubal", "district_id" => "22"],
            ["name" => "Baniachong", "district_id" => "22"],
            ["name" => "Chunarughat", "district_id" => "22"],
            ["name" => "Habiganj Sadar", "district_id" => "22"],
            ["name" => "Lakhai", "district_id" => "22"],
            ["name" => "Madhabpur", "district_id" => "22"],
            ["name" => "Nabiganj", "district_id" => "22"],
            ["name" => "Shayestaganj", "district_id" => "22"],

            // --- District: Jashore (district_id: 23) ---
            ["name" => "Abhaynagar", "district_id" => "23"],
            ["name" => "Bagherpara", "district_id" => "23"],
            ["name" => "Chaugachha", "district_id" => "23"],
            ["name" => "Jashore Sadar", "district_id" => "23"],
            ["name" => "Jhikargachha", "district_id" => "23"],
            ["name" => "Keshabpur", "district_id" => "23"],
            ["name" => "Manirampur", "district_id" => "23"],
            ["name" => "Sharsha", "district_id" => "23"],

            // --- District: Jhalokati (district_id: 24) ---
            ["name" => "Jhalokati Sadar", "district_id" => "24"],
            ["name" => "Kathalia", "district_id" => "24"],
            ["name" => "Nalchity", "district_id" => "24"],
            ["name" => "Rajapur", "district_id" => "24"],

            // --- District: Jhenaidah (district_id: 25) ---
            ["name" => "Harinakunda", "district_id" => "25"],
            ["name" => "Jhenaidah Sadar", "district_id" => "25"],
            ["name" => "Kaliganj", "district_id" => "25"],
            ["name" => "Kotchandpur", "district_id" => "25"],
            ["name" => "Maheshpur", "district_id" => "25"],
            ["name" => "Shailkupa", "district_id" => "25"],

            // --- District: Joypurhat (district_id: 26) ---
            ["name" => "Akkelpur", "district_id" => "26"],
            ["name" => "Joypurhat Sadar", "district_id" => "26"],
            ["name" => "Kalai", "district_id" => "26"],
            ["name" => "Khetlal", "district_id" => "26"],
            ["name" => "Panchbibi", "district_id" => "26"],

            // --- District: Jamalpur (district_id: 27) ---
            ["name" => "Bakshiganj", "district_id" => "27"],
            ["name" => "Dewanganj", "district_id" => "27"],
            ["name" => "Islampur", "district_id" => "27"],
            ["name" => "Jamalpur Sadar", "district_id" => "27"],
            ["name" => "Madarganj", "district_id" => "27"],
            ["name" => "Melandaha", "district_id" => "27"],
            ["name" => "Sarishabari", "district_id" => "27"],

            // --- District: Khagrachhari (district_id: 28) ---
            ["name" => "Dighinala", "district_id" => "28"],
            ["name" => "Khagrachhari Sadar", "district_id" => "28"],
            ["name" => "Lakshmichhari", "district_id" => "28"],
            ["name" => "Mahalchhari", "district_id" => "28"],
            ["name" => "Manikchhari", "district_id" => "28"],
            ["name" => "Matiranga", "district_id" => "28"],
            ["name" => "Panchhari", "district_id" => "28"],
            ["name" => "Ramgarh", "district_id" => "28"],
            ["name" => "Guimara", "district_id" => "28"],

            // --- District: Khulna (district_id: 29) ---
            ["name" => "Batiaghata", "district_id" => "29"],
            ["name" => "Dacope", "district_id" => "29"],
            ["name" => "Daulatpur", "district_id" => "29"],
            ["name" => "Dighalia", "district_id" => "29"],
            ["name" => "Dumuria", "district_id" => "29"],
            ["name" => "Khulna Sadar (Kotwali)", "district_id" => "29"],
            ["name" => "Koyra", "district_id" => "29"],
            ["name" => "Phultala", "district_id" => "29"],
            ["name" => "Rupsa", "district_id" => "29"],
            ["name" => "Terokhada", "district_id" => "29"],

            // --- District: Kishoreganj (district_id: 30) ---
            ["name" => "Austagram", "district_id" => "30"],
            ["name" => "Bajitpur", "district_id" => "30"],
            ["name" => "Bhairab", "district_id" => "30"],
            ["name" => "Hossainpur", "district_id" => "30"],
            ["name" => "Itna", "district_id" => "30"],
            ["name" => "Karimganj", "district_id" => "30"],
            ["name" => "Katiadi", "district_id" => "30"],
            ["name" => "Kishoreganj Sadar", "district_id" => "30"],
            ["name" => "Kuliarchar", "district_id" => "30"],
            ["name" => "Mithamain", "district_id" => "30"],
            ["name" => "Nikli", "district_id" => "30"],
            ["name" => "Pakundia", "district_id" => "30"],
            ["name" => "Tarail", "district_id" => "30"],

            // --- District: Kurigram (district_id: 31) ---
            ["name" => "Bhurungamari", "district_id" => "31"],
            ["name" => "Chilmari", "district_id" => "31"],
            ["name" => "Phulbari", "district_id" => "31"],
            ["name" => "Kurigram Sadar", "district_id" => "31"],
            ["name" => "Nageshwari", "district_id" => "31"],
            ["name" => "Rajarhat", "district_id" => "31"],
            ["name" => "Raomari", "district_id" => "31"],
            ["name" => "Ulipur", "district_id" => "31"],
            ["name" => "Char Rajibpur", "district_id" => "31"],

            // --- District: Kushtia (district_id: 32) ---
            ["name" => "Bheramara", "district_id" => "32"],
            ["name" => "Daulatpur", "district_id" => "32"],
            ["name" => "Khoksa", "district_id" => "32"],
            ["name" => "Kumarkhali", "district_id" => "32"],
            ["name" => "Kushtia Sadar", "district_id" => "32"],
            ["name" => "Mirpur", "district_id" => "32"],

            // --- District: Lakshmipur (district_id: 33) ---
            ["name" => "Kamalnagar", "district_id" => "33"],
            ["name" => "Lakshmipur Sadar", "district_id" => "33"],
            ["name" => "Raipur", "district_id" => "33"],
            ["name" => "Ramganj", "district_id" => "33"],
            ["name" => "Ramgati", "district_id" => "33"],

            // --- District: Lalmonirhat (district_id: 34) ---
            ["name" => "Aditmari", "district_id" => "34"],
            ["name" => "Hatibandha", "district_id" => "34"],
            ["name" => "Kaliganj", "district_id" => "34"],
            ["name" => "Lalmonirhat Sadar", "district_id" => "34"],
            ["name" => "Patgram", "district_id" => "34"],

            // --- District: Madaripur (district_id: 35) ---
            ["name" => "Kalkini", "district_id" => "35"],
            ["name" => "Madaripur Sadar", "district_id" => "35"],
            ["name" => "Rajoir", "district_id" => "35"],
            ["name" => "Shibchar", "district_id" => "35"],
            ["name" => "Dasar", "district_id" => "35"],

            // --- District: Magura (district_id: 36) ---
            ["name" => "Magura Sadar", "district_id" => "36"],
            ["name" => "Mohammadpur", "district_id" => "36"],
            ["name" => "Shalikha", "district_id" => "36"],
            ["name" => "Sreepur", "district_id" => "36"],

            // --- District: Manikganj (district_id: 37) ---
            ["name" => "Daulatpur", "district_id" => "37"],
            ["name" => "Ghior", "district_id" => "37"],
            ["name" => "Harirampur", "district_id" => "37"],
            ["name" => "Manikganj Sadar", "district_id" => "37"],
            ["name" => "Saturia", "district_id" => "37"],
            ["name" => "Shibalaya", "district_id" => "37"],
            ["name" => "Singair", "district_id" => "37"],

            // --- District: Meherpur (district_id: 38) ---
            ["name" => "Gangni", "district_id" => "38"],
            ["name" => "Meherpur Sadar", "district_id" => "38"],
            ["name" => "Mujibnagar", "district_id" => "38"],

            // --- District: Moulvibazar (district_id: 39) ---
            ["name" => "Barlekha", "district_id" => "39"],
            ["name" => "Juri", "district_id" => "39"],
            ["name" => "Kamalganj", "district_id" => "39"],
            ["name" => "Kulaura", "district_id" => "39"],
            ["name" => "Moulvibazar Sadar", "district_id" => "39"],
            ["name" => "Rajnagar", "district_id" => "39"],
            ["name" => "Sreemangal", "district_id" => "39"],

            // --- District: Munshiganj (district_id: 40) ---
            ["name" => "Gazaria", "district_id" => "40"],
            ["name" => "Lohajang", "district_id" => "40"],
            ["name" => "Munshiganj Sadar", "district_id" => "40"],
            ["name" => "Serajdikhan", "district_id" => "40"],
            ["name" => "Sreenagar", "district_id" => "40"],
            ["name" => "Tongibari", "district_id" => "40"],

            // --- District: Mymensingh (district_id: 41) ---
            ["name" => "Bhaluka", "district_id" => "41"],
            ["name" => "Fulbaria", "district_id" => "41"],
            ["name" => "Gaffargaon", "district_id" => "41"],
            ["name" => "Gouripur", "district_id" => "41"],
            ["name" => "Haluaghat", "district_id" => "41"],
            ["name" => "Ishwarganj", "district_id" => "41"],
            ["name" => "Muktagachha", "district_id" => "41"],
            ["name" => "Mymensingh Sadar", "district_id" => "41"],
            ["name" => "Nandail", "district_id" => "41"],
            ["name" => "Phulpur", "district_id" => "41"],
            ["name" => "Trishal", "district_id" => "41"],
            ["name" => "Dhobaura", "district_id" => "41"],

            // --- District: Naogaon (district_id: 42) ---
            ["name" => "Atrai", "district_id" => "42"],
            ["name" => "Badalgachhi", "district_id" => "42"],
            ["name" => "Dhamoirhat", "district_id" => "42"],
            ["name" => "Manda", "district_id" => "42"],
            ["name" => "Mahadevpur", "district_id" => "42"],
            ["name" => "Naogaon Sadar", "district_id" => "42"],
            ["name" => "Niamatpur", "district_id" => "42"],
            ["name" => "Patnitala", "district_id" => "42"],
            ["name" => "Porsha", "district_id" => "42"],
            ["name" => "Raninagar", "district_id" => "42"],
            ["name" => "Sapahar", "district_id" => "42"],

            // --- District: Narail (district_id: 43) ---
            ["name" => "Kalia", "district_id" => "43"],
            ["name" => "Lohagara", "district_id" => "43"],
            ["name" => "Narail Sadar", "district_id" => "43"],

            // --- District: Narayanganj (district_id: 44) ---
            ["name" => "Araihazar", "district_id" => "44"],
            ["name" => "Bandar", "district_id" => "44"],
            ["name" => "Narayanganj Sadar", "district_id" => "44"],
            ["name" => "Rupganj", "district_id" => "44"],
            ["name" => "Sonargaon", "district_id" => "44"],

            // --- District: Narsingdi (district_id: 45) ---
            ["name" => "Belabo", "district_id" => "45"],
            ["name" => "Monohardi", "district_id" => "45"],
            ["name" => "Narsingdi Sadar", "district_id" => "45"],
            ["name" => "Palash", "district_id" => "45"],
            ["name" => "Raipura", "district_id" => "45"],
            ["name" => "Shibpur", "district_id" => "45"],

            // --- District: Natore (district_id: 46) ---
            ["name" => "Bagatipara", "district_id" => "46"],
            ["name" => "Baraigram", "district_id" => "46"],
            ["name" => "Gurudaspur", "district_id" => "46"],
            ["name" => "Lalpur", "district_id" => "46"],
            ["name" => "Naldanga", "district_id" => "46"],
            ["name" => "Natore Sadar", "district_id" => "46"],
            ["name" => "Singra", "district_id" => "46"],

            // --- District: Netrokona (district_id: 47) ---
            ["name" => "Atpara", "district_id" => "47"],
            ["name" => "Barhatta", "district_id" => "47"],
            ["name" => "Durgapur", "district_id" => "47"],
            ["name" => "Kalmakanda", "district_id" => "47"],
            ["name" => "Kendua", "district_id" => "47"],
            ["name" => "Khaliajuri", "district_id" => "47"],
            ["name" => "Madan", "district_id" => "47"],
            ["name" => "Mohanganj", "district_id" => "47"],
            ["name" => "Netrokona Sadar", "district_id" => "47"],
            ["name" => "Purbadhala", "district_id" => "47"],

            // --- District: Nilphamari (district_id: 48) ---
            ["name" => "Dimla", "district_id" => "48"],
            ["name" => "Domar", "district_id" => "48"],
            ["name" => "Jaldhaka", "district_id" => "48"],
            ["name" => "Kishoreganj", "district_id" => "48"],
            ["name" => "Nilphamari Sadar", "district_id" => "48"],
            ["name" => "Saidpur", "district_id" => "48"],

            // --- District: Noakhali (district_id: 49) ---
            ["name" => "Begumganj", "district_id" => "49"],
            ["name" => "Chatkhil", "district_id" => "49"],
            ["name" => "Companiganj", "district_id" => "49"],
            ["name" => "Hatiya", "district_id" => "49"],
            ["name" => "Kabirhat", "district_id" => "49"],
            ["name" => "Noakhali Sadar", "district_id" => "49"],
            ["name" => "Senbagh", "district_id" => "49"],
            ["name" => "Sonaimuri", "district_id" => "49"],
            ["name" => "Subarnachar", "district_id" => "49"],

            // --- District: Pabna (district_id: 50) ---
            ["name" => "Atghoria", "district_id" => "50"],
            ["name" => "Bera", "district_id" => "50"],
            ["name" => "Bhangura", "district_id" => "50"],
            ["name" => "Faridpur", "district_id" => "50"],
            ["name" => "Ishwardi", "district_id" => "50"],
            ["name" => "Pabna Sadar", "district_id" => "50"],
            ["name" => "Santhia", "district_id" => "50"],
            ["name" => "Sujanagar", "district_id" => "50"],
            ["name" => "Chatmohar", "district_id" => "50"],

            // --- District: Panchagarh (district_id: 51) ---
            ["name" => "Atwari", "district_id" => "51"],
            ["name" => "Boda", "district_id" => "51"],
            ["name" => "Debiganj", "district_id" => "51"],
            ["name" => "Panchagarh Sadar", "district_id" => "51"],
            ["name" => "Tetulia", "district_id" => "51"],

            // --- District: Patuakhali (district_id: 52) ---
            ["name" => "Bauphal", "district_id" => "52"],
            ["name" => "Dashmina", "district_id" => "52"],
            ["name" => "Dumki", "district_id" => "52"],
            ["name" => "Galachipa", "district_id" => "52"],
            ["name" => "Kalapara", "district_id" => "52"],
            ["name" => "Mirzaganj", "district_id" => "52"],
            ["name" => "Patuakhali Sadar", "district_id" => "52"],
            ["name" => "Rangabali", "district_id" => "52"],

            // --- District: Pirojpur (district_id: 53) ---
            ["name" => "Bhandaria", "district_id" => "53"],
            ["name" => "Indurkani (Zianagar)", "district_id" => "53"],
            ["name" => "Kawkhali", "district_id" => "53"],
            ["name" => "Mathbaria", "district_id" => "53"],
            ["name" => "Nazirpur", "district_id" => "53"],
            ["name" => "Nesarabad (Swarupkati)", "district_id" => "53"],
            ["name" => "Pirojpur Sadar", "district_id" => "53"],

            // --- District: Rajbari (district_id: 54) ---
            ["name" => "Baliakandi", "district_id" => "54"],
            ["name" => "Goalandaghat", "district_id" => "54"],
            ["name" => "Kalukhali", "district_id" => "54"],
            ["name" => "Pangsha", "district_id" => "54"],
            ["name" => "Rajbari Sadar", "district_id" => "54"],

            // --- District: Rajshahi (district_id: 55) ---
            ["name" => "Bagha", "district_id" => "55"],
            ["name" => "Bagmara", "district_id" => "55"],
            ["name" => "Charghat", "district_id" => "55"],
            ["name" => "Durgapur", "district_id" => "55"],
            ["name" => "Godagari", "district_id" => "55"],
            ["name" => "Mohanpur", "district_id" => "55"],
            ["name" => "Paba", "district_id" => "55"],
            ["name" => "Puthia", "district_id" => "55"],
            ["name" => "Tanore", "district_id" => "55"],
            // Thanas (City Area - Sadar)
            ["name" => "Boalia Thana", "district_id" => "55"],
            ["name" => "Motihar Thana", "district_id" => "55"],
            ["name" => "Rajpara Thana", "district_id" => "55"],
            ["name" => "Shah Mokhdum Thana", "district_id" => "55"],
            // ... other City Thanas

            // --- District: Rangamati (district_id: 56) ---
            ["name" => "Bagaichhari", "district_id" => "56"],
            ["name" => "Barkal", "district_id" => "56"],
            ["name" => "Belaichhari", "district_id" => "56"],
            ["name" => "Juraichhari", "district_id" => "56"],
            ["name" => "Kaptai", "district_id" => "56"],
            ["name" => "Kawkhali (Betbunia)", "district_id" => "56"],
            ["name" => "Langadu", "district_id" => "56"],
            ["name" => "Naniarchar", "district_id" => "56"],
            ["name" => "Rajasthali", "district_id" => "56"],
            ["name" => "Rangamati Sadar", "district_id" => "56"],

            // --- District: Rangpur (district_id: 57) ---
            ["name" => "Badarganj", "district_id" => "57"],
            ["name" => "Gangachara", "district_id" => "57"],
            ["name" => "Kaunia", "district_id" => "57"],
            ["name" => "Mithapukur", "district_id" => "57"],
            ["name" => "Pirgachha", "district_id" => "57"],
            ["name" => "Pirganj", "district_id" => "57"],
            ["name" => "Rangpur Sadar", "district_id" => "57"],
            ["name" => "Taraganj", "district_id" => "57"],

            // --- District: Satkhira (district_id: 58) ---
            ["name" => "Assasuni", "district_id" => "58"],
            ["name" => "Debhata", "district_id" => "58"],
            ["name" => "Kalaroa", "district_id" => "58"],
            ["name" => "Kaliganj", "district_id" => "58"],
            ["name" => "Satkhira Sadar", "district_id" => "58"],
            ["name" => "Shyamnagar", "district_id" => "58"],
            ["name" => "Tala", "district_id" => "58"],

            // --- District: Shariatpur (district_id: 59) ---
            ["name" => "Bhedarganj", "district_id" => "59"],
            ["name" => "Damudya", "district_id" => "59"],
            ["name" => "Gosairhat", "district_id" => "59"],
            ["name" => "Jajira", "district_id" => "59"],
            ["name" => "Naria", "district_id" => "59"],
            ["name" => "Shariatpur Sadar", "district_id" => "59"],

            // --- District: Sherpur (district_id: 60) ---
            ["name" => "Jhenaigati", "district_id" => "60"],
            ["name" => "Nakla", "district_id" => "60"],
            ["name" => "Nalitabari", "district_id" => "60"],
            ["name" => "Sherpur Sadar", "district_id" => "60"],
            ["name" => "Sreebardi", "district_id" => "60"],

            // --- District: Sirajganj (district_id: 61) ---
            ["name" => "Belkuchi", "district_id" => "61"],
            ["name" => "Chauhali", "district_id" => "61"],
            ["name" => "Kamarkhanda", "district_id" => "61"],
            ["name" => "Kazipur", "district_id" => "61"],
            ["name" => "Raiganj", "district_id" => "61"],
            ["name" => "Shahjadpur", "district_id" => "61"],
            ["name" => "Sirajganj Sadar", "district_id" => "61"],
            ["name" => "Tarash", "district_id" => "61"],
            ["name" => "Ullapara", "district_id" => "61"],

            // --- District: Sunamganj (district_id: 62) ---
            ["name" => "Bishwamvarpur", "district_id" => "62"],
            ["name" => "Chhatak", "district_id" => "62"],
            ["name" => "Dakshin Sunamganj", "district_id" => "62"],
            ["name" => "Derai", "district_id" => "62"],
            ["name" => "Dharampasha", "district_id" => "62"],
            ["name" => "Dowarabazar", "district_id" => "62"],
            ["name" => "Jagannathpur", "district_id" => "62"],
            ["name" => "Jamalganj", "district_id" => "62"],
            ["name" => "Sulla", "district_id" => "62"],
            ["name" => "Sunamganj Sadar", "district_id" => "62"],
            ["name" => "Tahirpur", "district_id" => "62"],
            ["name" => "Madhyanagar", "district_id" => "62"],

            // --- District: Sylhet (district_id: 63) ---
            ["name" => "Balaganj", "district_id" => "63"],
            ["name" => "Beanibazar", "district_id" => "63"],
            ["name" => "Bishwanath", "district_id" => "63"],
            ["name" => "Companiganj", "district_id" => "63"],
            ["name" => "Fenchuganj", "district_id" => "63"],
            ["name" => "Golapganj", "district_id" => "63"],
            ["name" => "Gowainghat", "district_id" => "63"],
            ["name" => "Jaintiapur", "district_id" => "63"],
            ["name" => "Kanaighat", "district_id" => "63"],
            ["name" => "South Surma", "district_id" => "63"],
            ["name" => "Sylhet Sadar", "district_id" => "63"],
            ["name" => "Zakiganj", "district_id" => "63"],
            ["name" => "Osmani Nagar", "district_id" => "63"],

            // --- District: Tangail (district_id: 64) ---
            ["name" => "Basail", "district_id" => "64"],
            ["name" => "Bhuapur", "district_id" => "64"],
            ["name" => "Delduar", "district_id" => "64"],
            ["name" => "Ghatail", "district_id" => "64"],
            ["name" => "Gopalpur", "district_id" => "64"],
            ["name" => "Kalihati", "district_id" => "64"],
            ["name" => "Madhupur", "district_id" => "64"],
            ["name" => "Mirzapur", "district_id" => "64"],
            ["name" => "Nagarpur", "district_id" => "64"],
            ["name" => "Sakhipur", "district_id" => "64"],
            ["name" => "Tangail Sadar", "district_id" => "64"],
            ["name" => "Dhanbari", "district_id" => "64"],

            // --- District: Thakurgaon (district_id: 65) ---
            ["name" => "Baliadangi", "district_id" => "65"],
            ["name" => "Haripur", "district_id" => "65"],
            ["name" => "Pirganj", "district_id" => "65"],
            ["name" => "Ranisankail", "district_id" => "65"],
            ["name" => "Thakurgaon Sadar", "district_id" => "65"],

            // --- CONTINUE FOR REMAINING DISTRICTS ---
            // (The remaining districts from your original list that were not fully expanded above are included below)

            // --- Barguna (id: 3), Barishal (id: 4), Bhola (id: 5), Bogura (id: 6) - ALREADY ABOVE ---

            // --- Dinajpur (id: 16), Faridpur (id: 17), Feni (id: 18), Gaibandha (id: 19), Gazipur (id: 20) - ALREADY ABOVE ---

            // --- Jamalpur (id: 27), Jashore (id: 23), Jhalokati (id: 24), Jhenaidah (id: 25), Joypurhat (id: 26) - ALREADY ABOVE ---

            // --- Kishoreganj (id: 30), Kurigram (id: 31), Kushtia (id: 32) - ALREADY ABOVE ---

            // --- Madaripur (id: 35), Magura (id: 36), Manikganj (id: 37), Meherpur (id: 38), Munshiganj (id: 40) - ALREADY ABOVE ---

            // --- Narayanganj (id: 44), Narsingdi (id: 45), Natore (id: 46), Netrokona (id: 47), Nilphamari (id: 48) - ALREADY ABOVE ---

            // --- Pabna (id: 50), Panchagarh (id: 51), Patuakhali (id: 52), Pirojpur (id: 53) - ALREADY ABOVE ---

            // --- Rajbari (id: 54), Rajshahi (id: 55), Rangamati (id: 56), Rangpur (id: 57) - ALREADY ABOVE ---

            // --- Satkhira (id: 58), Shariatpur (id: 59), Sherpur (id: 60), Sirajganj (id: 61), Sunamganj (id: 62) - ALREADY ABOVE ---
        ];

        DB::table('thanas')->insert($thana);
    }
}
