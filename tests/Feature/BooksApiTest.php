<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase {
    use RefreshDatabase;
    /** @test */
    function can_get_all_books(){
        $books = Book::factory(4)->create();

        // route('books.index') por si la ruta llegase a cambiar
        $response = $this->getJson(route('books.index'));
        $response->assertJsonFragment([
            'title'=>$books[0]->title
        ]);
    }

    /** @test */
    function can_get_one_book(){
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show', $book));
        $response->assertJsonFragment([
            'title'=>$book->title
        ]);
    }

    /** @test */
    function can_create_books(){
        $this->postJson(route('books.store'),[])->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'),[
           'title'=>'libro de prueba 01'
        ])->assertJsonFragment([
            'title'=>'libro de prueba 01'
        ]);

        $this->assertDatabaseHas('books',[
            'title'=>'libro de prueba 01'
        ]);
    }

    /** @test */
    function can_update_books(){
        $book = Book::factory()->create();

        $this->putJson(route('books.update', $book),[])->assertJsonValidationErrorFor('title');

        $this->putJson(route('books.update', $book),[
            'title'=>'Libro editado'
        ])->assertJsonFragment([
            'title'=>'Libro editado'
        ]);

        $this->assertDatabaseHas('books',[
            'title'=>'Libro editado'
        ]);
    }

    /** @test */
    function can_delete_books(){
        $book = Book::factory()->create();
        $this->deleteJson(route('books.destroy',$book))->assertNoContent();

        $this->assertDatabaseCount('books',0);
    }
}
