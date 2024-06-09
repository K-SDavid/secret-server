<?php

namespace Tests\Feature;

use App\Models\Secret;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\ArrayToXml\ArrayToXml;
use Tests\TestCase;

class SecretTest extends TestCase
{
    # store function tests
    public function test_add_new_secret_json_success(): void
    {
        $response = $this->postJson('api/secret', [
            'secret' => 'This is a test.',
            'expireAfterViews' => 10,
            'expireAfter' => 60
        ]);

        $response->assertJson(
            ['message' => 'Successfully added a secret!']);
        $response->assertStatus(201);
    }

    public function test_add_new_secret_xml_success(): void
    {
        $response = $this->post('api/secret', [
            'secret' => 'This is a test.',
            'expireAfterViews' => 10,
            'expireAfter' => 60
        ], ['Accept' => 'application/xml']);

        $expectedResult = ['message' => 'Successfully added a secret!'];
        $expectedResult = ArrayToXml::convert($expectedResult);

        $response->assertStatus(201);
        $response->assertContent($expectedResult);
    }

    public function test_add_new_secret_json_wrong_inputs(): void
    {
        $response = $this->post('api/secret', [
            'secret' => 10,
            'expireAfterViews' => 'test',
        ], ['Accept' => 'application/json']);

        $response->assertStatus(400);
        $response->assertJson([
            'secret' => 
                [0 => 'The secret field must be a string.'],
            'expireAfterViews' => 
                [0 => 'The expire after views field must be a number.'],
            'expireAfter' => 
                [0 => 'The expire after field is required.']
        ]);
    }

    public function test_add_new_secret_xml_wrong_inputs(): void
    {
        $response = $this->post('api/secret', [
            'secret' => 10,
            'expireAfterViews' => 'test',
        ], ['Accept' => 'application/xml']);

        $expectedResult = [
            'secret' => 
                [0 => 'The secret field must be a string.'],
            'expireAfterViews' => 
                [0 => 'The expire after views field must be a number.'],
            'expireAfter' => 
                [0 => 'The expire after field is required.']
        ];
        $expectedResult = ArrayToXml::convert($expectedResult);

        $response->assertStatus(400);
        $response->assertContent($expectedResult);
    }

    #details function tests
    public function test_get_a_secret_by_hash_json_success(): void
    {
        $secret = Secret::factory()->create();
        $response = $this->getJson('api/secret/'.$secret->hash);

        $expectedSecret = $secret->toArray();
        $expectedSecret['remainingViews'] -= 1;

        $response->assertStatus(200);
        $response->assertJson($expectedSecret);
    }

    public function test_get_a_secret_by_hash_Xml_success(): void
    {
        $secret = Secret::factory()->create();
        $response = $this->get('api/secret/'.$secret->hash,
            ['Accept' => 'application/xml']);

        $expectedSecret = $secret->toArray();
        $expectedSecret['remainingViews'] -= 1;

        $expectedSecret = ArrayToXml::convert($expectedSecret);

        $response->assertStatus(200);
        $response->assertContent($expectedSecret);
    }

    public function test_remainingViews_is_zero_json(): void
    {
        $secret = Secret::factory()->create();
        $secret->update(['remainingViews' => 0]);
        $response = $this->getJson('api/secret/'.$secret->hash);

        $response->assertStatus(403);
        $response->assertJson(
            ['error' => 'The chosen secret can not be viewed anymore!']);
    }

    public function test_remainingViews_is_zero_xml(): void
    {
        $secret = Secret::factory()->create();
        $secret->update(['remainingViews' => 0]);
        $response = $this->get('api/secret/'.$secret->hash,
            ['Accept' => 'application/xml']);

        $expectedResult = ArrayToXml::convert(
            ['error' => 'The chosen secret can not be viewed anymore!']);

        $response->assertStatus(403);
        $response->assertContent($expectedResult);
    }

    public function test_secret_has_expired_json(): void
    {
        $secret = Secret::factory()->create();
        $secret->update(['expiresAt' 
            => Carbon::createFromTimeString("2014-12-22 05:23:52.534")]);
        $response = $this->getJson('api/secret/'.$secret->hash);

        $response->assertStatus(403);
        $response->assertJson(
            ['error' => 'The chosen secret has expired!']);
    }

    public function test_secret_has_expired_xml(): void
    {
        $secret = Secret::factory()->create();
        $secret->update(['expiresAt' 
            => Carbon::createFromTimeString("2014-12-22 05:23:52.534")]);
        $response = $this->get('api/secret/'.$secret->hash,
            ['Accept' => 'application/xml']);

        $expectedResult = ArrayToXml::convert(
            ['error' => 'The chosen secret has expired!']);

        $response->assertStatus(403);
        $response->assertContent($expectedResult);
    }
}
