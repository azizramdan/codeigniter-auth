<?php

namespace App\Database\Seeds;

use App\Models\Pegawai;
use App\Models\UserModel;
use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $this->db->transStart();

        $userModel = new UserModel();

        $userModel->save(new User([
            'username' => 'superadmin',
            'email'    => 'superadmin',
            'name'     => 'Super Admin',
            'password' => 'dev',
        ]));

        $userModel->findById($userModel->getInsertID())->addGroup('superadmin');

        $nip = '123456789012345678';

        (new Pegawai())->save([
            'nip'  => $nip,
            'nama' => 'Pegawai 1',
        ]);

        $userModel->save(new User([
            'username' => $nip,
            'email'    => $nip,
            'name'     => 'Pegawai 1',
            'nip'      => $nip,
            'password' => 'dev',
        ]));

        $userModel->findById($userModel->getInsertID())->addGroup('user');

        $this->db->transComplete();
    }
}
