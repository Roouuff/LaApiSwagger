<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_swagger_documentation_is_available(): void
    {
        $this->get('/api/documentation')->assertOk();
    }
}
