<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Lang;
use Illuminate\Testing\TestResponse;

trait TestValidations
{
    protected function assertValidationErrorsInStore(
        array $dataToStore,
        array $expectedInvalidFields,
        string $rule,
        array $rulesParams = []
    ): void {
        $response = $this->json('POST', $this->routeStore(), $dataToStore);

        $this->assertValidationErrors(
            $response,
            $expectedInvalidFields,
            $rule,
            $rulesParams
        );
    }

    protected function assertValidationErrorsInUpdate(
        array $dataToUpdate,
        array $expectedInvalidFields,
        string $rule,
        array $rulesParams = []
    ): void {
        $response = $this->json('PUT', $this->routeUpdate(), $dataToUpdate);

        $this->assertValidationErrors(
            $response,
            $expectedInvalidFields,
            $rule,
            $rulesParams
        );
    }

    protected function assertValidationErrors(
        TestResponse $response,
        array $invalidFields,
        string $rule,
        array $ruleParams = []
    ): void {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors($invalidFields);

        foreach ($invalidFields as $invalidField) {
            $fieldName = str_replace('_', ' ', $invalidField);
            $expectedMessage = $this->getValidationRuleMessage(
                $rule,
                ['attribute' => $fieldName] + $ruleParams
            );

            $errors = $response->json("errors");

            $this->assertArrayHasKey($invalidField, $errors);
            $this->assertContains($expectedMessage, $errors[$invalidField]);
        }
    }

    private function getValidationRuleMessage(string $rule, array $replace): string
    {
        return Lang::get("validation.{$rule}", $replace);
    }
}
