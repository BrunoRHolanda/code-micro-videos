<?php

namespace Tests\Traits;

use Exception;
use Illuminate\Testing\TestResponse;

trait TestSaves {
    abstract protected function routeStore();
    abstract protected function routeUpdate();
    abstract protected function model();

    protected function assertStore(array $sendData, array $testData, array $testDataJson = null)
    {
        /**
         * @var TestResponse $response
         */
        $response = $this->json('POST', $this->routeStore(), $sendData);

        if ($response->status() !== 201) {
            throw new Exception("Response status must be, 201 given {$response->status()}:\n{$response->content()}");
        }

        $this->assertInDatabase($response, $testData);
        $this->assertJsonResponseContent($response, $testData, $testDataJson);
    }

    protected function assertUpdate(array $sendData, array $testData, array $testDataJson = null)
    {
        /**
         * @var TestResponse $response
         */
        $response = $this->json('PUT', $this->routeUpdate(), $sendData);

        if ($response->status() !== 200) {
            throw new Exception("Response status must be, 200 given {$response->status()}:\n{$response->content()}");
        }

        $this->assertInDatabase($response, $testData);
        $this->assertJsonResponseContent($response, $testData, $testDataJson);
    }

    private function assertInDatabase (TestResponse $response, array $testData)
    {
        $model = $this->model();
        $table = (new $model)->getTable();

        $this->assertDatabaseHas(
            $table,
            $testData + ['id' => $response->json('id')]
        );
    }

    private function assertJsonResponseContent(TestResponse $response, array $testData, ?array $testDataJson)
    {
        $testResponse = $testDataJson? $testDataJson : $testData;

        $response->assertJsonFragment($testResponse + ['id' => $response->json('id')]);
    }
}
