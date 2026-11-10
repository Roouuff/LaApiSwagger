<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_swagger_book_api_flow_returns_expected_responses(): void
    {
        $this->seed();

        $login = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assertOk()
            ->assertJsonPath('token_type', 'Bearer');

        $token = $login->json('token');
        $this->assertIsString($token);

        $books = $this->withToken($token)->getJson('/api/books')
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.total', 20)
            ->assertJsonPath('meta.last_page', 2);

        $bookData = [
            'title' => 'Laskar Pelangi',
            'author' => 'Andrea Hirata',
            'publisher' => 'Bentang Pustaka',
            'isbn' => '9789793062792',
            'published_year' => 2005,
            'stock' => 10,
            'description' => 'Novel tentang anak-anak Belitung.',
        ];

        $created = $this->withToken($token)->postJson('/api/books', $bookData)
            ->assertCreated()
            ->assertJsonPath('data.isbn', $bookData['isbn']);
        $bookId = $created->json('data.id');

        $this->withToken($token)->postJson('/api/books', $bookData)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('isbn');

        $updatedBookData = [...$bookData, 'title' => 'Laskar Pelangi (Edisi Baru)'];
        $this->withToken($token)->putJson("/api/books/{$bookId}", $updatedBookData)
            ->assertOk()
            ->assertJsonPath('data.title', $updatedBookData['title']);

        $this->withToken($token)->getJson("/api/books/{$bookId}")
            ->assertOk()
            ->assertJsonPath('data.id', $bookId);

        $this->withToken($token)->deleteJson("/api/books/{$bookId}")
            ->assertOk()
            ->assertJsonPath('message', 'Buku berhasil dihapus');

        $this->withToken($token)->postJson('/api/logout')
            ->assertOk()
            ->assertJsonPath('message', 'Logout berhasil');

        $this->assertDatabaseCount('personal_access_tokens', 0);
        Auth::forgetGuards();
        $this->getJson('/api/books')->assertUnauthorized();

        $this->assertDatabaseMissing('books', ['id' => $bookId]);
        $this->assertSame(20, Book::count());
    }
}
