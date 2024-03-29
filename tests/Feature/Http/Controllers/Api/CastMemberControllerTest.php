<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class CastMemberControllerTest extends TestCase
{
    use DatabaseMigrations, TestSaves, TestValidations;

    protected CastMember $castMember;

    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = CastMember::factory()->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('cast-members.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->castMember->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('cast-members.show', ['cast_member' => $this->castMember->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->castMember->toArray());
    }

    public function testFieldNameValidation()
    {
        $missingNameInDataValidation = [
            'type' => 1
        ];

        $nameWitchNumberInDataValidation = [
            'name' => 123,
            'type' => 1
        ];

        $bigStringInFieldNameToDataValidation = [
            'name' => str_repeat('a', 300),
            'type' => 1
        ];

        $expectedInvalidFields = [
            'name'
        ];

        $this->assertValidationErrorsInStore(
            $missingNameInDataValidation,
            $expectedInvalidFields,
            'required'
        );
        $this->assertValidationErrorsInStore(
            $nameWitchNumberInDataValidation,
            $expectedInvalidFields,
            'string'
        );
        $this->assertValidationErrorsInStore(
            $bigStringInFieldNameToDataValidation,
            $expectedInvalidFields,
            'max.string',
            [
                'max' => 255
            ]
        );
    }

    public function testStore(): void
    {
        $data = [
            'name' => 'Diretor',
            'type' => 1
        ];
        $this->assertStore(
            $data,
            $data + ['deleted_at' => null]
        );

        $data = [
            'name' => 'Actor',
            'type' => 2
        ];
        $this->assertStore($data, $data + ['deleted_at' => null]);
    }

    public function testUpdate(): void
    {
        $this->category = CastMember::factory()->create([
            'name' => 'Actor',
            'type' => 2
        ]);
        $data = [
            'name' => 'Actor edited'
        ];
        $this->assertUpdate(
            $data,
            $data + ['deleted_at' => null]
        );
    }

    protected function routeStore()
    {
        return route('cast-members.store');
    }

    protected function routeUpdate()
    {
        return route('cast-members.update', ['cast_member' => $this->castMember->id]);
    }

    protected function model()
    {
        return CastMember::class;
    }
}
