<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Função para exibir uma lista de livros
    public function index()
    {
        $books = Book::with(['author', 'publisher', 'categories'])->get();
        return view('books.index', compact('books'));
    }

    // Função para exibir um livro específico
    public function show($id)
    {
        $book = Book::with(['author', 'publisher', 'categories'])->findOrFail($id);
        return view('books.show', compact('book'));
    }

    // Função para exibir o formulário de criação de um novo livro
    public function create()
    {
        $authors = Author::all();
        $publishers = Publisher::all();
        $categories = Category::all();
        return view('books.create', compact('authors', 'publishers', 'categories'));
    }

    // Função para armazenar um novo livro no banco de dados
    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|integer',
            'publisher_id' => 'required|integer',
            'published_year' => 'required|integer',
            'categories' => 'required|array',
            
            'images' => 'required',
        ]);
    
        // Verificar e processar o upload da imagem, se presente
        
        $imagePath = null;

 
        $name = uniqid(date('HisYmd'));
        $extension = $request->images->getClientOriginalExtension();
        $nameFile = "{$name}.{$extension}";
        $imagePath = $request->images->storeAs('imagens', $nameFile, 'public');
        
        if (!$imagePath) {
            
            return redirect()->back()->with('error', 'Falha no upload da imagem.')->withInput();
        }

    
        // Adiciona o caminho da imagem aos dados validados, se houver
        $validatedData['images'] = $imagePath;
    
        // Cria o livro com os dados validados
        $book = Book::create($validatedData);
    
        $book->categories()->attach($request->categories);
    
        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso!');
    }

    // Função para exibir o formulário de edição de um livro
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $authors = Author::all();
        $publishers = Publisher::all();
        $categories = Category::all();
        return view('books.edit', compact('book', 'authors', 'publishers', 'categories'));
    }

    // Função para atualizar um livro no banco de dados
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|integer',
            'publisher_id' => 'required|integer',
            'published_year' => 'required|integer',
            'categories' => 'required|array',
            
            'images' => 'required',
        ]);
                
        $imagePath = null;
        var_dump($request->images);
        $name = uniqid(date('HisYmd'));
        $extension = $request->images->getClientOriginalExtension();
        $nameFile = "{$name}.{$extension}";
        $imagePath = $request->images->storeAs('imagens', $nameFile, 'public');
        
        if (!$imagePath) {
            
            return redirect()->back()->with('error', 'Falha no upload da imagem.')->withInput();
        }

    
        // Adiciona o caminho da imagem aos dados validados, se houver
        $validatedData['images'] = $imagePath;

        $book = Book::findOrFail($id);
        $book->update($validatedData);
        
        $book->categories()->attach($request->categories);
    
        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso!');
    }

    // Função para excluir um livro do banco de dados
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book_image = "public/" . $book->images;
        
        if (Storage::exists($book_image)) {

            Storage::delete($book_image);
        }
        $book->categories()->detach();
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Livro excluído com sucesso!');
    }
}

