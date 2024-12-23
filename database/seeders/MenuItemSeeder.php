<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Menubuilder\app\Models\Menus;
use Modules\Menubuilder\app\Models\MenuItem;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Menubuilder\app\Enums\DefaultMenusEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class MenuItemSeeder extends Seeder
{
    use GenerateTranslationTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultMenusList = DefaultMenusEnum::getAll();

        foreach ($defaultMenusList as $menu) {
            $menuItem = new MenuItem();
            $menuItem->label = $menu->name == 'Rumah' ? 'Beranda' : $menu->name;
            $menuItem->link = $menu->url;
            $menuItem->role_id = 0;
            $menuItem->menu_id = 9;
            $menuItem->sort = MenuItem::getNextSortRoot(9);
            $menuItem->save();

            request()->merge(['label' => $menu->name]);

            $this->generateTranslations(
                TranslationModels::MenuItem,
                $menuItem,
                'menu_item_id',
                request(),
            );
        }

        $footerColOneList = $defaultMenusList->filter(function ($menu) {
            return $menu->name !== 'Instruktur';
        });

        foreach ($footerColOneList as $menu) {
            $menuItem = new MenuItem();
            $menuItem->label = $menu->name == 'Rumah' ? 'Beranda' : $menu->name;
            $menuItem->link = $menu->url;
            $menuItem->role_id = 0;
            $menuItem->menu_id = 10;
            $menuItem->sort = MenuItem::getNextSortRoot(10);
            $menuItem->save();

            request()->merge(['label' => $menu->name]);

            $this->generateTranslations(
                TranslationModels::MenuItem,
                $menuItem,
                'menu_item_id',
                request(),
            );
        }

        $footerColTwoList = [
            (object) [
                'name' => __('Contact'),
                'url' => '/contact',
            ],
            (object) [
                'name' => 'Privacy Policy',
                'url' => '/page/privacy-policy',
            ],
            (object) [
                'name' => 'Terms and Conditions',
                'url' => '/page/terms-and-conditions',
            ],
            (object) [
                'name' => 'Menjadi Peserta',
                'url' => '/register',
            ],
            (object) [
                'name' => __('All Instructors'),
                'url' => '/all-instructors',
            ],
        ];

        foreach ($footerColTwoList as $menu) {
            $menuItem = new MenuItem();
            $menuItem->label = $menu->name == 'Rumah' ? 'Beranda' : $menu->name;
            $menuItem->link = $menu->url;
            $menuItem->role_id = 0;
            $menuItem->menu_id = 13;
            $menuItem->sort = MenuItem::getNextSortRoot(13);
            $menuItem->save();

            request()->merge(['label' => $menu->name]);

            $this->generateTranslations(
                TranslationModels::MenuItem,
                $menuItem,
                'menu_item_id',
                request(),
            );
        }

        $footerColThreeList = [
            (object) [
                'name' => 'Privacy Policy',
                'url' => '/page/privacy-policy',
            ],
            (object) [
                'name' => 'Terms and Conditions',
                'url' => '/page/terms-and-conditions',
            ],
        ];

        foreach ($footerColThreeList as $menu) {
            $menuItem = new MenuItem();
            $menuItem->label = $menu->name == 'Rumah' ? 'Beranda' : $menu->name;
            $menuItem->link = $menu->url;
            $menuItem->role_id = 0;
            $menuItem->menu_id = 14;
            $menuItem->sort = MenuItem::getNextSortRoot(14);
            $menuItem->save();

            request()->merge(['label' => $menu->name]);

            $this->generateTranslations(
                TranslationModels::MenuItem,
                $menuItem,
                'menu_item_id',
                request(),
            );
        }
    }
}
