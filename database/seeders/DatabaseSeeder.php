<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Desk;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Voorgeprogrammeerde Testaccounts
        $manager = User::firstOrCreate(
            ['email' => 'manager@wms.test'],
            [
                'name' => 'Kantoor Manager',
                'password' => Hash::make('password'),
                'role' => 'manager',
            ]
        );

        $employee = User::firstOrCreate(
            ['email' => 'medewerker@wms.test'],
            [
                'name' => 'Jan de Medewerker',
                'password' => Hash::make('password'),
                'role' => 'employee',
            ]
        );

        // Extra testcollega's voor 'Wie is er op kantoor?'
        $sarah = User::firstOrCreate(
            ['email' => 'sarah.dev@wms.test'],
            [
                'name' => 'Sarah van Dijk',
                'password' => Hash::make('password'),
                'role' => 'employee',
            ]
        );

        $thomas = User::firstOrCreate(
            ['email' => 'thomas.it@wms.test'],
            [
                'name' => 'Thomas Bakker',
                'password' => Hash::make('password'),
                'role' => 'employee',
            ]
        );

        $lisa = User::firstOrCreate(
            ['email' => 'lisa.design@wms.test'],
            [
                'name' => 'Lisa Vermeer',
                'password' => Hash::make('password'),
                'role' => 'employee',
            ]
        );

        // 2. Desks
        $desksData = [
            // IT-Vleugel
            ['name' => 'Werkplek A1', 'zone' => 'IT-Vleugel', 'features' => ['sit_stand', 'dual_screen', 'usb_c_dock'], 'is_active' => true],
            ['name' => 'Werkplek A2', 'zone' => 'IT-Vleugel', 'features' => ['dual_screen', 'usb_c_dock'], 'is_active' => true],
            ['name' => 'Werkplek A3', 'zone' => 'IT-Vleugel', 'features' => ['sit_stand', 'dual_screen'], 'is_active' => true],
            // Stiltezone
            ['name' => 'Werkplek B1', 'zone' => 'Stiltezone', 'features' => ['sit_stand', 'usb_c_dock'], 'is_active' => true],
            ['name' => 'Werkplek B2', 'zone' => 'Stiltezone', 'features' => ['sit_stand'], 'is_active' => true],
            ['name' => 'Werkplek B3', 'zone' => 'Stiltezone', 'features' => ['dual_screen'], 'is_active' => true],
            // Flexzone
            ['name' => 'Werkplek C1', 'zone' => 'Flexzone', 'features' => ['usb_c_dock'], 'is_active' => true],
            ['name' => 'Werkplek C2', 'zone' => 'Flexzone', 'features' => ['sit_stand', 'usb_c_dock'], 'is_active' => true],
            ['name' => 'Werkplek C3', 'zone' => 'Flexzone', 'features' => [], 'is_active' => true],
            // Creatieve Hub
            ['name' => 'Werkplek D1', 'zone' => 'Creatieve Hub', 'features' => ['dual_screen', 'usb_c_dock'], 'is_active' => true],
            ['name' => 'Werkplek D2', 'zone' => 'Creatieve Hub', 'features' => ['sit_stand', 'dual_screen', 'usb_c_dock'], 'is_active' => true],
            ['name' => 'Werkplek D3', 'zone' => 'Creatieve Hub', 'features' => ['sit_stand'], 'is_active' => true],
        ];

        $createdDesks = [];
        foreach ($desksData as $data) {
            $createdDesks[] = Desk::updateOrCreate(['name' => $data['name']], $data);
        }

        // 3. Actieve Boekingen voor vandaag
        $today = Carbon::today()->toDateString();

        Booking::firstOrCreate(
            ['desk_id' => $createdDesks[0]->id, 'booking_date' => $today, 'status' => 'active'],
            ['user_id' => $sarah->id, 'work_hours_notes' => '08:30 - 17:00 (Focus Sprint)']
        );

        Booking::firstOrCreate(
            ['desk_id' => $createdDesks[3]->id, 'booking_date' => $today, 'status' => 'active'],
            ['user_id' => $thomas->id, 'work_hours_notes' => '09:00 - 16:30 (Stilteoverleg)']
        );

        Booking::firstOrCreate(
            ['desk_id' => $createdDesks[9]->id, 'booking_date' => $today, 'status' => 'active'],
            ['user_id' => $lisa->id, 'work_hours_notes' => '10:00 - 18:00 (Design review)']
        );

        // 4. Schademelding / Ticket (zodat Werkplek C1 direct als Defect / Amber zichtbaar is)
        Ticket::firstOrCreate(
            ['title' => 'Dual monitor kabel (HDMI/USB-C) ontbreekt'],
            [
                'user_id' => $thomas->id,
                'desk_id' => $createdDesks[6]->id, // Werkplek C1
                'description' => 'De HDMI/USB-C aansluitkabel is meegenomen of kwijt. Tweede scherm geeft momenteel geen signaal.',
                'status' => 'open',
            ]
        );
    }
}
