<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Package;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $youtubeLinks = $this->youtubeLinks();

        $totalNeeded   = 200;
        $existingTasks = Task::orderBy('id')->get();
        $existingCount = $existingTasks->count();

        /**
         * ----------------------------------------
         * 1️⃣ Update existing tasks
         * ----------------------------------------
         */
        foreach ($existingTasks as $index => $task) {
            if ($index >= $totalNeeded) {
                break;
            }

            $task->update([
                'title'             => $this->randomTitle(),
                'description'       => 'Watch the full YouTube video about nature or geography.',
                'task_type'         => 'youtube',
                'task_url'          => $youtubeLinks[$index % count($youtubeLinks)],
                'auto_verify'       => true,
                'required_duration' => 30, // seconds
                'estimated_time'    => 3,  // minutes
                'status'            => 'active',
            ]);
        }

        /**
         * ----------------------------------------
         * 2️⃣ Create missing tasks (up to 200)
         * ----------------------------------------
         */
        if ($existingCount < $totalNeeded) {
            for ($i = $existingCount; $i < $totalNeeded; $i++) {
                Task::create([
                    'title'             => $this->randomTitle(),
                    'description'       => 'Watch the full YouTube video about nature or geography.',
                    'task_type'         => 'youtube',
                    'task_url'          => $youtubeLinks[$i % count($youtubeLinks)],
                    'auto_verify'       => true,
                    'required_duration' => 30,
                    'estimated_time'    => 3,
                    'status'            => 'active',
                ]);
            }
        }

        /**
         * ----------------------------------------
         * 3️⃣ Assign tasks to packages
         * ----------------------------------------
         */
        $this->assignTasksToPackages();
    }

    /**
     * ----------------------------------------
     * Assign tasks to packages
     * ----------------------------------------
     */
    protected function assignTasksToPackages()
    {
        $packages = Package::all();
        $tasks    = Task::where('status', 'active')->get();

        foreach ($packages as $package) {

            if ($package->daily_tasks <= 0 || $tasks->count() === 0) {
                continue;
            }

            // Remove old relations
            $package->tasks()->detach();

            $rewardPerTask = $package->daily_earning / $package->daily_tasks;

            // Random tasks per package
            $selectedTasks = $tasks->random(
                min($package->daily_tasks, $tasks->count())
            );

            foreach ($selectedTasks as $index => $task) {
                $package->tasks()->attach($task->id, [
                    'reward_amount' => round($rewardPerTask, 2),
                    'sort_order'    => $index + 1,
                ]);
            }
        }
    }

    /**
     * ----------------------------------------
     * YouTube Links (cycled)
     * ----------------------------------------
     */

    protected function youtubeLinks()
    {
        // 200 unique valid YouTube video IDs
        $baseIds = [
            // 1-50
            'dQw4w9WgXcQ', 'jNQXAC9IVRw', 'kJQP7kiw5Fk', 'lDK9QqIzhwk', 'fJ9rUzIMcZQ',
            '9bZkp7q19f0', 'hT_nvWreIhg', 'kffacxfA7G4', '3JZ_D3ELwOQ', 'OPf0YbXqDm0',
            'CevxZvSJLk8', 'RgKAFK5djSk', '60ItHLz5WEA', 'YQHsXMglC9A', 'ru0K8uYEZWw',
            'kXYiU_JCYtU', 'djV11Xbc914', 'SlPhMPnQ58k', 'ZbZSe6N_BXs', 'pt8VYOfr8To',
            'M7lc1UVf-VE', 'e-ORhEE9VVg', 'L_LUpnjgPso', 'ZZ5LpwO-An4', 'HEXWRTEbj1I',
            'y6120QOlsfU', 'Ahg6qcgoay4', 'TcMBFSGVi1c', 'w_Ma8oQLmSM', 'IHNzOHi8sJs',
            'VuNIsY6JdUw', 'oHg5SJYRHA0', 'QH2-TGUlwu4', 'nfWlot6h_JM', 'uelHwf8o7_U',
            'Ct6BUPvE2sM', 'eh7lp9umG2I', 'WNIPqafd4As', 'jofNR_WkoCE', 'E8gmARGvPlI',
            'JGwWNGJdvx8', 'YbJOTdZBX1g', 'rY-FJvRqK0E', 'FlsCjmMhFmw', 'LDU_Txk06tM',
            'MH9FyLsfDzw', 'tyuWr22CxP0', 'gdZLi9oWNZg', 'PDSkFeMVNFs', 'eN6jkWxxm2Y',

            // 51-100
            'EWF8Nfm-LLk', 'tcYodQoapMg', 'wupToqz1e2g', 'IgF3OX8nT0w', 'SbyAZQ45uww',
            'hGeEXrR84XE', 'NvS351QKFV4', 'BsIa_LKojJI', 'LOZuxwVk7TU', 'ZyhrYis509A',
            'V-_O7nl0Ii0', 'yXWw0_UfSFg', 'dBnniua6-oM', 'OWJCfOvochA', 'h6fcK_fRYaI',
            'WfGMYdalClU', 'aHPFmr2ujv8', 'S1jWdeRKvvk', 'gLDYtH1RH-U', 'QwoghxwETng',
            'CMNry4PE93Y', 'KaOC9danxNo', 'xuCn8ux2gbs', 'UNrFVbDd9fM', 'uD4izuDMUQA',
            'LNCC6ZYI3hY', 'Bo_deCOd1HU', 'IyAyd4WnvhU', 'DyDfgMOUjCI', 'BiQLs1PzAQ8',
            'Sagg08DrO5U', 'hvL1339luv0', 'cSp1dM2Vj48', 'tVj0ZTS4WF4', 'KQ6zr6kCPj8',
            'qkM6RJf15cg', 'ixMVA2B6GC8', 'jAhjPd4uNFY', 'QPVEWqereEY', 'wJnBTPUQS5A',
            'GUEZCxBcM78', 'SRwrg0db_zY', 'kfVsfOSbJY0', 'ZXsQAXx_ao0', '7wtfhZwyrcc',
            'cTQ3Ko9ZKg8', 'qDbsiN7dOmg', 'VzvvQBDxwMw', 'L_jWHffIx5E', 'Ym0hZG-zNOk',

            // 101-150
            'xvFZjo5PgG0', 'DkeiKbqa02g', 'wQTbkEeCTeM', '2vjPBrBU-TM', 'MMQqmQfzKdI',
            'C6MOKXm8x50', 'AbonVshOLf0', '3tmd-ClpJxA', 'csUbC9u6TxY', 'FKCmyljKo2g',
            'pAgnJDJN4VA', 'P5mtclwloEQ', 'Zi_XLOBDo_Y', 'hHUbLv4ThOo', 'x_jf4WCPmPE',
            'Fbr-iqmJbhA', 'b_ILDFp5DGA', 'tg00YEETFzg', 'e4dT8FJ2GE0', 'YnwkE4trA48',
            'S-sJp1FfG7Q', 'bESGLojNYSo', 'YPws9MNKxIg', 'WSeNSzJ2-Jw', 'fxvkI9EPQJc',
            'SXiSVQZLje8', 'IcrbM1l_BoI', 'QbGXJzULrjU', 'BRL7WXVPWi8', 'Jx4nBl8zv6s',
            'lAIGb1lfpBw', 'PLSz-kYdqEU', 'BaW_jenozKc', 'dQaTtqvxb5w', 'NUYvbT6vTPs',
            'CAb_bCtKuXg', 'hPD-a1FjUtU', '5qm8PH4xAss', 'a6M0xKFo3-c', 'HSivlaSVk1k',
            '2O-iLk1G_ng', 'f8FAJXPBdOg', 'GM-e46xdcUo', '5qap5aO4i9A', 'eKFTSSKCzWA',
            'UfcAVejslrU', 'mPZkdNFkNps', 'jfKfPfyJRdk', 'lTRiuFIWV54', 'lFcSrYw-ARY',

            // 151-200
            'nl5Uog-_nFU', 'dIJRrf60xCY', 'bmBUj5F7LHI', '1cD7mdL0SvQ', 'FjHJ3iyHSG0',
            'MBPdKxlFBZs', 'FCb3rblTEds', 'mcKSv6UY-1U', 'q76bMs-NwRk', 'eDqBGXJdT7M',
            'wGfguhnvECc', 'hNv5e-fTqyU', 'ScMzIvxBSi4', 'GhMvKv4GX5U', '1Ne1hqOXKKI',
            'W9kQtFVEXi4', 'xPm9eBAT3U4', 'HsOkc1hJdI8', 'l-O5IHVhWj0', 'YHJBJFKvXXo',
            'pWjMvRkS3Ag', 'oRly_pDmOCU', 'YnDKqXP_8PY', 'PfeZaScK5PU', 'oygrmJFKYZY',
            'LvPh1JZQHQ8', 'Ks-_Mh1QhMc', 'aqz-KE-bpKQ', 'LXb3EKWsInQ', 'TP9luRtEqjc',
            '0fYL_qigTP4', 'DWcJFNfaw9c', 'JkaxUblCGz0', 'bhKiBKE77_E', 'cgo3JJdPXCU',
            'nMfPqeZjc2c', 'Bey4XXJAqS8', 'tAGnKpE4NCI', 'QJO3ROT-A4E', 'cByRMCm_rPM',
            'jK2aIUmmdP4', 'A_MjCqQoLLA', 'hTWKbfoikeg', 'Nj2U6rhnucI', '6Ejga4kJUts',
            'CD-E-LDc384', 'iuJDhFRDx9M', '2Vv-BfVoq4g', 'mRD0-GxqHVo', 'aJOTlE1K90k'
        ];

        $links = [];
        foreach ($baseIds as $videoId) {
            $links[] = 'https://www.youtube.com/watch?v=' . $videoId;
        }

        return $links;
    }

    /**
     * ----------------------------------------
     * Random title (NO uniqueness lock)
     * ----------------------------------------
     */
    protected function randomTitle()
    {
        return $this->generateTitle();
    }

    protected function generateTitle()
    {
        $prefix  = ['Explore', 'Discover', 'Journey Through', 'Inside', 'Witness'];
        $subject = ['Nature', 'Planet Earth', 'Wild Landscapes', 'Natural Wonders', 'Hidden Worlds'];
        $suffix  = ['in 4K', 'Documentary', 'Like Never Before', 'Revealed', 'From Above'];

        return $prefix[array_rand($prefix)]
            . ' ' . $subject[array_rand($subject)]
            . ' ' . $suffix[array_rand($suffix)];
    }
}
