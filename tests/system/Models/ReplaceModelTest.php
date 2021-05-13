<?php

namespace CodeIgniter\Models;

use Tests\Support\Models\UserModel;

final class ReplaceModelTest extends LiveModelTestCase
{
    public function testReplaceRespectsUseTimestamps(): void
    {
        $this->createModel(UserModel::class);

        $data = [
            'name'    => 'Amanda Holmes',
            'email'   => 'amanda@holmes.com',
            'country' => 'US',
        ];

        $id = $this->model->insert($data);

        $data['id']      = $id;
        $data['country'] = 'UK';

        $sql = $this->model->replace($data, true);
        $this->assertStringNotContainsString('updated_at', $sql);

        $this->model = $this->createModel(UserModel::class);
        $this->setPrivateProperty($this->model, 'useTimestamps', true);
        $sql = $this->model->replace($data, true);
        $this->assertStringContainsString('updated_at', $sql);
    }
}
